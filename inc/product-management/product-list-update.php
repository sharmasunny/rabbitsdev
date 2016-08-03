<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
require('../../constants.php');
require('../../db.php');
$products_table = "products";	
$stores_table = "stores";	
//$users_sql = "select * from ".$products_table." id DESC";
//$users_records = $conn->query($users_sql) or die('Error 101: Not able to connect to Server'.mysqli_error($conn));

$info = isset($_GET['info']) ? $_GET['info'] : '';
if ( !empty( $info ) ) {

	if($info="edit") {
		$editlid = $_GET["eid"];
		if ( !empty( $editlid ) ) { 


			/*
			*	@this query will select record from product table 
			*	@field id
			*/
			$selected_product_sql = "select * from ".$products_table." where id = ".$editlid." ";


			$selected_product_records = $conn->query($selected_product_sql) or die('Error 102: Not able to connect to Server');;
			
			if ( $selected_product_records->num_rows > 0 ) {
				while($selected_product_record = $selected_product_records->fetch_assoc()) {	
				
				?>
				<div class="wrap"> 
					<h2>Edit Product Details</h2>
					<br/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css"/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/bootstrap.css"/>
					 <script src="<?php echo APP_URL; ?>/js/jquery-1.11.3.min.js"></script>
					 <script src="<?php echo APP_URL; ?>/js/bootstrap.min.js"></script>
					 <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
					 <form role="form" action="product-list-func.php" method="post" id="editproductform">
					  <div class="form-group">
					    <label for="editid">ID:</label>
					    <input type="text" class="form-control" id="editproductid" name="editproductid" readonly value="<?php echo $selected_product_record['id']; ?>">
					  </div>
					  <div class="form-group">
					    <label for="editproductname">Product Name:</label>
					    <input type="text" class="form-control" id="editproductname" name="editproductname" readonly value="<?php echo $selected_product_record['product_name']; ?>">
					  </div>
					  <div class="form-group">
					  <label for="producteditstores">Select Stores:</label>
						  <select class="form-control" id="producteditstores" name="producteditstores[]" multiple="multiple" size="18">
						  	<?php
						  	$productstorelist = $selected_product_record['product_store_list'];
							$productstorelistarr = array();
							$productstorelistarr = unserialize($productstorelist);


							/*
							*	@this query will select record from stores table 
							*	@field id
							*/
						  	$all_stores_sql 	  = "select * from ".$stores_table." where store_is_deleted='0' ";


							$all_store_records 	  = $conn->query($all_stores_sql);
							if ( $all_store_records->num_rows > 0 ) {
								while($all_store_record = $all_store_records->fetch_assoc()) {
									 if(in_array($all_store_record['store_id'],$productstorelistarr)) {
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
			else
			{
				echo "Product has been either removed or not available";
			}
		 }
	}
}	
?>
<script>
if(typeof jQuery == 'undefined'){
        document.write('<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></'+'script>');
  }
  jQuery.noConflict();
	jQuery(document).ready(function() {
		 $("#editproductform").validate();
    });		 
</script>