<?php
include('../../constants.php');
include('../../db.php');
$old_driver=$_POST['old_driver'];
$new_driver=$_POST['ordereditdriver'];
$order_id=$_POST['editordername'];
$orders_table = "orders";	
$order_driver_history_table="order_driver_history";
$editorderid = $_POST['editorderid'];
$editorderdriver = $_POST['ordereditdriver'];
$date=date("Y-m-d");

/*
*	@this query will insert record in order driver history table
*	@field order_id
*	@field date
*	@field from_driver
*	@field to_driver
*/
$qr = "INSERT INTO ".$order_driver_history_table." (order_id, date,from_driver,to_driver ) VALUES (".$order_id.", '".$date."', ".$old_driver.",".$new_driver.")";


$result=$conn->query($qr);





/*
*	@this query will update record of order driver id in orders table
*	@field order_driver_id
*	@field id
*/
$sql = "UPDATE ".$orders_table." SET order_driver_id='".$editorderdriver."' WHERE id=".$editorderid." ";


$conn->query($sql);
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