<?php
include('../../constants.php');
include('../../db.php');
$products_table = "products";	
$stores_table = "stores";
$data = $_POST['fdata'];

foreach($_POST['fdata'] as $key=>$value)
{
	$post[$value['name']] = $value['value'];	
}

$delivery_postal_code = $post['user_postalcode'];
$collection = $post['collection'];
$response['status'] = 1;
$response['userpostalcode'] = $delivery_postal_code;
$response['usercollection'] = $collection;
echo json_encode($response);	
die(); 
?>