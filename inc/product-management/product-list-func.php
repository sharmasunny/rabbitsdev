<?php
include('../../constants.php');
include('../../db.php');
$products_table = "products";	
$editproductid = $_POST['editproductid'];
$editproductstores = serialize($_POST['producteditstores']);


/*
*	@this query will update product store list record in products table 
*	@field product_store_list
*	@field id
*/

$sql = "UPDATE ".$products_table." SET product_store_list='".$editproductstores."' WHERE id=".$editproductid." ";


//echo $editproductstores;
//echo $sql;die;
$conn->query($sql);
$stores_table = "stores";	




/*
*	@this query will select all record from stores table 
*	@field store_is_deleted
*	@field store_postalcode
*/
$stores_sql = "select * from ".$stores_table." where store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";


$storeproductlistf = array();
$stores_records = $conn->query($stores_sql);
	
$editproductid = $_POST['editproductid'];
while($stores_record = $stores_records->fetch_assoc()) {
	/*echo 'hey';*/
	/*print_r($stores_record);
	echo '<br/>';*/
	$storeproductlistarr = array();
	$storeproductlist = $stores_record['store_product_list'];
	/*print_r($storeproductlist);
	die;*/
	$storeid = $stores_record['store_id'];
	if(!empty($storeproductlist)) {
		
		$storeproductlistar = unserialize($storeproductlist);
		if(count($storeproductlistarr) <= 1) {
			$storeproductlistarr = array($storeproductlistar);
		} else {
			$storeproductlistarr = $storeproductlistar;
		}
		if(is_array($storeproductlistarr)) {
			if (($key = array_search($editproductid, $storeproductlistarr)) !== false) {
		    	unset($storeproductlistarr[$key]);
			}
			//print_r($storeproductlistarr);die;
			$storeproductlistf = serialize($storeproductlistarr);



			/*
			*	@this query will update store product list record in stores table 
			*	@field store_product_list
			*	@field store_id
			*/
			$storesql = "UPDATE ".$stores_table." SET store_product_list='".$storeproductlistf."' WHERE store_id=".$prodstoreid." ";



			$conn->query($storesql);
		}
	}	
}	

foreach($_POST['producteditstores'] as $prodstore) {



	/*
	*	@this query will select all record where store id match from stores table  
	*	@field store_id
	*	@field store_is_deleted
	*	@field store_postalcode
	*/
	$stores_sql = "select * from ".$stores_table." where store_id=".$prodstore." and store_is_deleted='0' and store_postalcode!='00000'";
	
	$stores_records = $conn->query($stores_sql);
	$storeproductlistarr = array();
	$prodarr = array();
	$resultarr = array();
	while($stores_record = $stores_records->fetch_assoc()) {
		$storeproductlist = $stores_record['store_product_list'];
		$storeid = $stores_record['store_id'];
		$storeproductlistf = '';
		if(!empty($storeproductlist)) {
			$storeproductlistarr[] = unserialize($storeproductlist);
			/*$prodarr = $_POST['producteditstores'];
			print_r($prodarr);
			print_r($storeproductlistarr);
			$resultarr = array_intersect($prodarr, $storeproductlistarr);
			$newprodlistf = serialize($resultarr);
			print_r($resultarr);
			die;
			$storesql = "UPDATE ".$stores_table." SET store_product_list='".$newprodlistf."' WHERE store_id=".$storeid." ";
			$conn->query($storesql);*/
			
			if(!in_array($editproductid,$storeproductlistarr)) {
				array_push($storeproductlistarr,$editproductid);	
			}
			//print_r($storeproductlistarr);
			$storeproductlistf = serialize($storeproductlistarr);


			/*
			*	@this query will update store product list record in stores table 
			*	@field store_product_list
			*	@field store_id
			*/

			$storesql = "UPDATE ".$stores_table." SET store_product_list='".$storeproductlistf."' WHERE store_id=".$storeid." ";
			
			//echo $storesql;die;
			$conn->query($storesql);
		} else {
			$storeproductlistf = serialize($editproductid);
			

			/*
			*	@this query will update store product list record in stores table 
			*	@field store_product_list
			*	@field store_id
			*/
			$storesql = "UPDATE ".$stores_table." SET store_product_list='".$storeproductlistf."' WHERE store_id=".$storeid." ";
			
			$conn->query($storesql);

		}	
		
	}	
}

if ($conn->query($sql) === TRUE) {
   
   	 echo "<script>alert('Record Updated !!');</script>";
     echo "<script>parent.jQuery.fancybox.close();</script>";
     echo "<script>parent.location.reload(true);</script>";
   
  
} else {
	echo "<script>alert('Error while Updating Record !!');</script>";
	echo "<script>parent.jQuery.fancybox.close();</script>";
	echo "<script>parent.location.reload(true);</script>";
}
?>