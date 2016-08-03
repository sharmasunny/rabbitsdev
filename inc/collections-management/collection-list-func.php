<?php
include('../../constants.php');
include('../../db.php');
$collections_table = "collections";	
$editcollectionid = $_POST['editcollectionid'];
$editproductstores = serialize($_POST['collectioneditstores']);

/*
*	@this query will update record of collections table field collection Store list
*	@field collection_store_list
*	@field id
*/

$sql = "UPDATE ".$collections_table." SET collection_store_list='".$editproductstores."' WHERE id=".$editcollectionid." ";

$conn->query($sql);
$stores_table = "stores";

/*
*	@this query will select all record of stores table 
*	@field store_is_deleted
*	@field store_postalcode
*/	
$stores_sql = "select * from ".$stores_table." where store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";
$storeproductlistf = array();
$stores_records = $conn->query($stores_sql);
	
$editcollectionid = $_POST['editcollectionid'];
while($stores_record = $stores_records->fetch_assoc()) {
	/*echo 'hey';*/
	/*print_r($stores_record);
	echo '<br/>';*/
	$storecollectionlistarr = array();
	$storecollectionlist = $stores_record['store_collection_list'];
	/*print_r($storeproductlist);
	die;*/
	$storeid = $stores_record['store_id'];
	if(!empty($storecollectionlist)) {
		
		$storecollectionlistar = unserialize($storecollectionlist);
		if(count($storecollectionlistarr) <= 1) {
			$storecollectionlistarr = array($storecollectionlistar);
		} else {
			$storecollectionlistarr = $storecollectionlistar;
		}
		if(is_array($storecollectionlistarr)) {
			if (($key = array_search($editcollectionid, $storecollectionlistarr)) !== false) {
		    	unset($storecollectionlistarr[$key]);
			}
			//print_r($storeproductlistarr);die;
			$storecollectionlistf = serialize($storecollectionlistarr);




			/*
			*	@this query will update record of stores table 
			*	@field store_collection_list
			*	@field store_id
			*/	

			$storesql = "UPDATE ".$stores_table." SET store_collection_list='".$storecollectionlistf."' WHERE store_id=".$prodstoreid." ";
			$conn->query($storesql);
		}
	}	
}	

foreach($_POST['collectioneditstores'] as $collectionstore) {


	/*
	*	@this query will select all record of stores table 
	*	@field store_is_deleted
	*	@field store_postalcode
	*/	

	$stores_sql = "select * from ".$stores_table." where store_id=".$collectionstore." and store_is_deleted='0' and store_postalcode!='00000'";

	$stores_records = $conn->query($stores_sql);
	$storecollectionlistarr = array();
	$prodarr = array();
	$resultarr = array();
	while($stores_record = $stores_records->fetch_assoc()) {
		$storeproductlist = $stores_record['store_product_list'];
		$storeid = $stores_record['store_id'];
		$storecollectionlistf = '';
		if(!empty($storecollectionlist)) {
			$storecollectionlistarr[] = unserialize($storecollectionlist);
			/*$prodarr = $_POST['producteditstores'];
			print_r($prodarr);
			print_r($storeproductlistarr);
			$resultarr = array_intersect($prodarr, $storeproductlistarr);
			$newprodlistf = serialize($resultarr);
			print_r($resultarr);
			die;
			$storesql = "UPDATE ".$stores_table." SET store_product_list='".$newprodlistf."' WHERE store_id=".$storeid." ";
			$conn->query($storesql);*/
			
			if(!in_array($editcollectionid,$storecollectionlistarr)) {
				array_push($storecollectionlistarr,$editcollectionid);	
			}
			$storeproductlistf = serialize($storecollectionlistarr);

			/*
			*	@this query will update record of stores table 
			*	@field store_collection_list
			*	@field store_id
			*/	

			$storesql = "UPDATE ".$stores_table." SET store_collection_list='".$storecollectionlistf."' WHERE store_id=".$storeid." ";
			
			$conn->query($storesql);
		} else {
			$storecollectionlistf = serialize($editcollectionid);

			/*
			*	@this query will update record of stores table 
			*	@field store_collection_list
			*	@field store_id
			*/	
			
			$storesql = "UPDATE ".$stores_table." SET store_collection_list='".$storecollectionlistf."' WHERE store_id=".$storeid." ";
			
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