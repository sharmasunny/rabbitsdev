<?php
include('../../constants.php');
include('../../db.php');

include('../../header.php');
$drivers_table = "drivers";	
$orders_table = "orders";	
$zones_table = "zones";	

$api_url = 'https://6143cddd141541615eb006119cd232be:4f79e7163888d6bf77af717e923f37e9@24sevens.myshopify.com';


$driver_id =$_SESSION['driver_id'];
// echo $driver_id;die;

if(isset($_POST['confirm_stat_submit'])) {

	$order_id = $_POST['order_id'];

	$query_order = "SELECT * FROM ".$orders_table." WHERE order_id = " .$order_id;
	$result_order = $conn->query($query_order);
	while($row = $result_order->fetch_assoc()) {
		$all_record = $row['all_record'];
	}

	$order_data  = json_decode($all_record);

	$confirm_data = $_POST['confirm_stat_submit'];
	$confirm_status = ($confirm_data=='Accept'?2:0);

	$products_array = array(
	    "order"=>array(
	      "id" => $order_data->id,
	      "tags" =>  $confirm_data               
	    )
	);

	$url = $api_url . '/admin/orders/'.$order_data->id.'.json';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_VERBOSE, 0);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($products_array));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec ($curl);
	curl_close ($curl);


	
	// echo $order_stat;
	// echo $order_id;
	if($confirm_status != 0)
	{
	$query = "UPDATE " . $orders_table . " SET ".($confirm_status==2?"order_status=1 ,":"")." confirmation = '" . $confirm_status . "'
	, order_driver_id = '" . $driver_id . "'
	 WHERE order_id = " .$order_id;
	// ECHO $query;die;
	$conn->query($query);
	}
	
	//echo "<script>alert('Order is Accepted !!');</script>";
	echo "<script>window.location.href = '".HOME_URL."/'</script>";

			
	}
 ?>
