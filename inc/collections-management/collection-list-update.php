<?php
include('../../constants.php');
include('../../db.php');
$collections_table = "collections";	
$stores_table = "stores";	


/*
*	@this query will select all record of collections table 
*/	
$users_sql = "select * from ".$collections_table." order by id DESC";

$users_records = $conn->query($users_sql);

$info = isset($_GET['info']) ? $_GET['info'] : '';
if ( !empty( $info ) ) {


	if ( $info=="del" ) {
		$delid = $_GET["did"];
		if ( !empty( $delid ) ) {
			
			/*
			*	@this query will delete the record in collections table 
			*	@field collection_is_deleted
			*	@field id 
			*/
			$sql = "update ".$collections_table." set collection_is_deleted='1' where id=".$delid." ";

			if ($conn->query($sql) === TRUE) {

			   echo "<script>alert('Record Deleted !!');</script>";
			   echo "<script>window.location = '".HOME_URL."'</script>";
			  
			} else {
				echo "<script>alert('Error while Deleting Record !!');</script>";
				echo "<script>window.location = '".HOME_URL."'</script>";
			}

		}
	}

	if($info="edit") {
		$editlid = $_GET["eid"];
		if ( !empty( $editlid ) ) { 

			/*
			*	@this query will select all record of collections table 
			*/
			$selected_collection_sql = "select * from ".$collections_table." where id = ".$editlid." ";
			
			$selected_collection_records = $conn->query($selected_collection_sql);
			
			if ( $selected_collection_records->num_rows > 0 ) {
				while($selected_collection_record = $selected_collection_records->fetch_assoc()) {	
				
				?>
				<div class="wrap"> 
					<h2>Edit collection Details</h2>
					<br/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css"/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/bootstrap.css"/>
					 <script src="<?php echo APP_URL; ?>/js/jquery-1.11.3.min.js"></script>
					 <script src="<?php echo APP_URL; ?>/js/bootstrap.min.js"></script>
					 <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
					 <form role="form" action="collection-list-func.php" method="post" id="editcollectionform">
					  <div class="form-group">
					    <label for="editid">ID:</label>
					    <input type="text" class="form-control" id="editcollectionid" name="editcollectionid" readonly value="<?php echo $selected_collection_record['id']; ?>">
					  </div>
					  <div class="form-group"> 
					    <label for="editproductname">Collection Name:</label>
					    <input type="text" class="form-control" id="editcollectionname" name="editcollectionname" readonly value="<?php echo $selected_collection_record['collection_name']; ?>">
					  </div>
					  <div class="form-group"> 
					  <label for="producteditstores">Select Stores:</label>
						  <select class="form-control" id="collectioneditstores" name="collectioneditstores[]" multiple="multiple" size="15">
						  	<?php
						  	$collectionstorelist = $selected_collection_record['collection_store_list'];
							$collectionstorelistarr = array();
							$collectionstorelistarr = unserialize($collectionstorelist);
							/*
							*	@this query will select all record from stores table 
							*	@field store_is_deleted
							*/
						  	$all_stores_sql 	  = "select * from ".$stores_table." where store_is_deleted='0' ";
							$all_store_records 	  = $conn->query($all_stores_sql);
							if ( $all_store_records->num_rows > 0 ) {
								while($all_store_record = $all_store_records->fetch_assoc()) {
									 if(in_array($all_store_record['store_id'],$collectionstorelistarr)) {
									 	$selected_store = "selected=selected";
									 } else {
									 	$selected_store = "";
									 }
									 echo "<option id=".$all_store_record['store_id']." ".$selected_store." value=".$all_store_record['store_id'].">".$all_store_record['store_name']."</option>";
								}	
							}
						  	?>
						  </select>
					 </div>
					  <button type="submit" class="btn btn-default">Update</button>
					</form>
				</div>
				<?php
				}	
			}
		 }
	}
}	
?>
<script>
	$(document).ready(function() {
		 $("#editcollectionform").validate();
    });		 
</script>