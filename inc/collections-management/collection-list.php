<script>
	jQuery(document).ready(function() {
		jQuery("a.piframe").fancybox({
			maxWidth	: 2000,
			width		: '60%',
			height		: '20%',
			autoSize	: true,
			type		: 'iframe',
		});
	});
</script>
<?php
session_start();
include('../../db.php');
echo $_SESSION['shop'];
echo $_SESSION['token'];
$collections_table = "collections";	
$stores_table = "stores";


/*
*	@this query will select all record  from collections table in descending Order where collection_is_deleted is zero
*	@field collection_is_deleted
*/

$users_sql = "select * from collections where collection_is_deleted='0' order by id DESC";


$users_records = $conn->query($users_sql);
if ( $users_records->num_rows > 0 ) {
	while($users_record = $users_records->fetch_assoc()) {
		$collection_ids[] = $users_record['collection_id'];
	}	
}	


/*
*	@Shopify API Key URL
*/

$api_url = 'https://6143cddd141541615eb006119cd232be:4f79e7163888d6bf77af717e923f37e9@24sevens.myshopify.com';


/*
*	@Shopify API  Get List all collections
* 	@Refrance Document url - https://help.shopify.com/api/reference/customcollection
*/

$counts_url = $api_url . '/admin/custom_collections/count.json';



$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_URL, $counts_url);
$result2 = curl_exec($ch2);
curl_close($ch2);
$count_json = json_decode( $result2, true);
$counts = $count_json['count'];
$pagesize = round($counts/50);
$page = $pagesize + 1;
$collections = array(); 
$orgcollection = array();
for($i=1;$i<=$page;$i++){	


	$collections_url = $api_url . '/admin/custom_collections.json?status=any&limit=50&page='.$i;	
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch1, CURLOPT_URL, $collections_url);
	$result1 = curl_exec($ch1);
	curl_close($ch1);
	$collection_json = json_decode($result1, true);
	$collections = $collection_json['custom_collections'];
	if(!empty($collections)) {
		$orgcollection = array_merge_recursive($orgcollection,$collections);	
	} else {
		$collections = array();
		$orgcollection = array_merge_recursive($orgcollection,$collections);	
	}
	/*echo '<pre/>';
	print_r($orgcollection);*/
	foreach($orgcollection as $res) {
		$fcollection_id = $res['id'];
		$f_collection_ids[]  = $res['id'];
		$collection_name = $res['title'];
		$collection_desc = $res['body_html'];
		$collection_url = $res['handle'];
		$collection_image = $res['image']['src'];
		$add_collection = "INSERT INTO ".$collections_table."(collection_id, collection_name, collection_description,collection_handle,collection_img,collection_store_list) VALUES ('".$fcollection_id."', '".$collection_name."', '".$collection_desc."', '".$collection_url."', '".$collection_image."','') ON DUPLICATE KEY UPDATE collection_name = VALUES(collection_name),collection_description = VALUES(collection_description),collection_handle = VALUES(collection_handle),collection_img = VALUES(collection_img)";
		//echo $add_collection;
		$conn->query($add_collection);

	}
	/*$result = array_diff($collection_ids,$f_collection_ids);
	if(!empty($result)) {
		foreach($result as $delel) {
			$del_collection = "DELETE FROM ".$collections_table." WHERE collection_id=".$delel." ";
			$conn->query($del_collection);
		}
	}*/
}





/*
*	@this query will select all record  from collections table put all record in collections.js
*	@field collection_is_deleted
*/
$users_sql = "select * from ".$collections_table." where collection_is_deleted='0' order  by id DESC";


$users_records = $conn->query($users_sql);

$output = 'var collection_db = {';
$count = 0;
if ( $users_records->num_rows > 0 ) { 
	while($obj = $users_records->fetch_assoc()) {
		$store_ids = array();
		$flstores = '';
		$stores = '';
		$collectionstorelist = $obj['collection_store_list'];
		$collectionstorelistarr = array();
		$collectionstorelistarr = unserialize($collectionstorelist);
		if (is_array($collectionstorelistarr) || is_object($collectionstorelistarr))
		{
		    foreach($collectionstorelistarr as $storeid) {
		    	
				$stores_sql = "select store_id from ".$stores_table." where store_id = ".$storeid." and store_is_deleted='0' ";
				$store_records = $conn->query($stores_sql);
				if ( $store_records->num_rows > 0 ) {

					while($store_record = $store_records->fetch_assoc()) {
						$store_ids[] = $store_record['store_id'];
					}	
				} 
			} 
			foreach($store_ids as $store_id) {
				$stores .= $store_id.", ";
			}
			$flstores = trim($stores,', ');
		}
		if(!empty($obj['collection_store_list'])) {
			$output .= "'$count': {'collection_id': '" . $obj['collection_id'] . "', 'collection_name': '" . str_replace("'", "\'", $obj['collection_name']) . "','collection_handle': '" . $obj['collection_handle'] . "','collection_img':'".str_replace("'", "\'", $obj['collection_img']) ."','collection_store_list':'".$flstores."'},\n";
		}
		$count++;
	}	
}
$output = substr($output, 0, -2);
$output .= '};';
file_put_contents('js/collections.js', $output);




/*
*	@this query will show all record from collections table
*	@field collection_is_deleted
*/

$users_sql = "select * from ".$collections_table." where collection_is_deleted='0' order by id DESC";
$users_records = $conn->query($users_sql);

?>
<div class="wrap"> 
	<h2>Collections Listing</h2>
	<form name="products_list" method="get" action="inc/collections-management/collection-list-update.php?info=delall">	
	<table class="" id="cuserlist" style="width:100%">
		<thead>
			<tr>
				<th width="14px">ID</th>
				<th>Collection Image</th>
				<th>Collection ID</th>
				<th>Collection Name</th>
				<!--<th>Product Description</th>--> 
				<th width="215px">Selected Stores</th>                             
				<th style="text-align: center;">Action</th>
			</tr>
		</thead>
		<tbody>				     
			<?php
			$no = 1;
			if ( $users_records->num_rows > 0 ) { ?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('#cuserlist').dataTable({ 
							"aaSorting": [[ 0, "desc" ]],
						});
						jQuery('#alluser').click(function(event) {  //on click
						if(this.checked) { // check select status
						    jQuery('.userid').each(function() { //loop through each checkbox
						        this.checked = true;  //select all checkboxes with class "checkbox1"              
						    });
						}else{
						    jQuery('.userid').each(function() { //loop through each checkbox
						        this.checked = false; //deselect all checkboxes with class "checkbox1"                      
						    });        
						}
						});
					});	
				</script>
				<?php
				 while($users_record = $users_records->fetch_assoc()) {
				 	$snames 		  = "";
				 	$store_names 	  = array();
				 	$id        		  = $users_record['id'];
					$collectionid        = $users_record['collection_id'];
					$collectionname 	  = $users_record['collection_name'];
					$collectionimg 	  = $users_record['collection_img'];
					$collectiondec 	  = $users_record['collection_description'];
					$collectionstorelist = $users_record['collection_store_list'];
					$collectionstorelistarr = array();
					$collectionstorelistarr = unserialize($collectionstorelist);
					if (is_array($collectionstorelistarr) || is_object($collectionstorelistarr))
					{
					    foreach($collectionstorelistarr as $storeid) {
					    	/*
							*	@this query will get stores name 
							*/
							$stores_sql 	  = "select store_name from ".$stores_table." where store_id = ".$storeid." and store_is_deleted='0' ";
							$store_records = $conn->query($stores_sql);
							if ( $store_records->num_rows > 0 ) {
								while($store_record = $store_records->fetch_assoc()) {
									$store_names[] = $store_record['store_name'];
								}	
							} else {
								$store_names = array();
							}
						}
					} else {
						$store_names = array();
					}
					?>
					<tr>
						<td><?php echo $id; ?></td>
						<td><?php if(!empty($collectionimg)) { ?><img src="<?php echo $collectionimg; ?>" style="width: 50px;height: 50px;"><?php } ?></td>
						<td><?php echo $collectionid; ?></td>
						<td><?php echo $collectionname; ?></td>
						<!--<td style="width: 200px;"><?php echo strip_tags($productdec); ?></td>-->
						<td><?php if(!empty($store_names)) { foreach ($store_names as $value) {  $snames .= $value.", ";  } echo trim($snames,', ');  } else { echo 'No stores selected!'; } ?></td>                
						<td style="text-align: center;">
							<a onclick="javascript:return confirm('Are you sure, want to delete record of <?php echo $storename; ?>?')" href="inc/collections-management/collection-list-update.php?info=del&did=<?php echo $id;?>">
							<img src="images/delete.png" title="Delete" alt="Delete" />
							</a>
							&nbsp;&nbsp;&nbsp;							
							<a href="inc/collections-management/collection-list-update.php?info=edit&eid=<?php echo $id;?>" class="piframe">
							<img src="images/edit.png" title="Edit" alt="Edit" />
							</a>
						</td>             
					</tr>
				<?php $no += 1;							
				}
				$conn->close();
			} else {
				echo '<h3>No Collection Found !!</h3>';
			} ?>					
		</tbody>
	</table>
	<p></p>
	</form>
</div>
