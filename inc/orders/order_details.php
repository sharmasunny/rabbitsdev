<?php
include('../../db.php');
include('../../constants.php');
session_start();

$orders_table = "orders";	
$drivers_table = "drivers";

$id = $_POST['id'];

$order_sql = "SELECT * FROM " . $orders_table . " WHERE order_id =" .$id;
$order_records = $conn->query($order_sql);
while($order_records = $order_records->fetch_assoc()) {
	print_r($order_records);

}
	die();
?>