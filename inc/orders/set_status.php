<?php
include('../../constants.php');
include('../../header.php');
include('../../db.php');
include('../mail_lib/mail.php');

$drivers_table = "drivers";	
$orders_table = "orders";	
$zones_table = "zones";	
$stores_table = "stores";	
$driver_id =$_SESSION['driver_id'];


$api_url = 'https://6143cddd141541615eb006119cd232be:4f79e7163888d6bf77af717e923f37e9@24sevens.myshopify.com';


$query_get_drivers = "SELECT * FROM ".$drivers_table." WHERE driver_id = ".$driver_id ;
$result = $conn->query($query_get_drivers);

if ($result->num_rows > 0) {
   
    while($row = $result->fetch_assoc()) {
    	$driver_email = $row['driver_email'];
    	$driver_fname = $row['driver_name'];
    	$driver_mname = $row['driver_mname'];
    	$driver_lname = $row['driver_lname'];
    }
} 
//if(isset($_POST['submit'])) {

	
	if($_POST['order_status']=="select"){

		echo "<script>window.location = '".HOME_URL."'</script>";

	}

	else{

		$order_stat = $_POST['order_status'];
		$order_id = $_POST['order_id'];
		$customer_email = $_POST['customer_email'];

		$timline_array = array(1=>'Picked UP',2=>'On the way',3=>'Deliverd',4=>'FULFILLED');

		foreach ($timline_array as $key => $value) {
			if($key==$order_stat){
				$tag_status = $value;
			}
		}



	


		$query_order = "SELECT * FROM ".$orders_table." WHERE order_id = " .$order_id;
		$result_order = $conn->query($query_order);
		while($row = $result_order->fetch_assoc()) {
			$all_record = $row['all_record'];
		}

		$order_data  = json_decode($all_record);
		
		$invoice = array("total_price"=>$order_data->total_price, "subtotal_price"=>$order_data->subtotal_price, "total_tax"=>$order_data->total_tax);	
		$i=0;
		foreach ($order_data->line_items as $item_details) {
			/*
			//$order_data->line_items[$j]['j'] = 'k';
			// print_r($item_details);
			$item_details['l']=4;
			print_r($item_details);*/
			$query_store_id = "SELECT product_store_list FROM products where product_id = $item_details->product_id";
			$result_store_id = $conn->query($query_store_id);
			while($row_store_id = $result_store_id->fetch_assoc()) {
				$store_ids = unserialize($row_store_id['product_store_list']);
				$j=0;	
				foreach($store_ids as $single_store_id)
				{
				$order_data->line_items[$i]->stores_details[$j]['id'] = $single_store_id;
				$query_store_email = "SELECT store_email FROM $stores_table where store_id = $single_store_id";
				
				$result_store_email = $conn->query($query_store_email);
				while($row_store_email = $result_store_email->fetch_assoc()) {
					$order_data->line_items[$i]->stores_details[$j]['email'] = $row_store_email['store_email'];
				
				}
				$j+=1;
				}			
			}
			$invoice['product'][$i]['title'] = $item_details->title;
			$invoice['product'][$i]['price'] = $item_details->price;
			$invoice['product'][$i]['quantity'] = $item_details->quantity;
			$invoice['product'][$i]['total'] = ($item_details->quantity*$item_details->price);
			$i+=1;
		}
		// print_r($order_data);
		
		

		$item_array = array();
		foreach ($order_data->line_items as  $value) {
			$item_array_id = array("id" =>$value->id,"quantity"=>$value->quantity);
			array_push($item_array,$item_array_id);
		}	



		$tag_array = array(
		    "order"=>array(
		      "id" => $order_data->id,
		      "tags" =>  $tag_status               
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
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($tag_array));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec ($curl);
		curl_close ($curl);

		$message_content = array(	
			'subject' => 'Congratulation',
			'msg_head' => 'Congratulation',
			'msg_body_line_1' => ' Hi, '.$driver_fname .' '.$driver_mname .' '.$driver_lname .' ',
			'msg_body_line_2' => 'you have been completed this order #'.$order_id .' '
		);



		$query = "UPDATE " . $orders_table . " SET order_status = '" . $order_stat . "' WHERE order_id = " .$order_id;
		

		if(($conn->query($query)) === TRUE)
		{ 
			if($order_stat == '1')
			{

				$url = $api_url . '/admin/shop.json';
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$response_of_store = curl_exec ($curl);
				curl_close ($curl);
				$store_email = json_decode($response_of_store, true)['shop']['email'];
				// print_r($store_email);die;

			$message_content_for_customer = array(	
			'subject' => 'Order Completion',
			'msg_head' => 'Order Completion',
			'msg_body_line_1' => ' Hi, ',
			'msg_body_line_2' => 'your order #'.$order_id .' detail below.',
			'msg_body_line_3' => $invoice
			);
				send_email($store_email, $message_content_for_store,' ');
				send_email($customer_email, $message_content_for_customer,' ');
				send_email($driver_email, $message_content,' ');
			}
			if($order_stat == '4')
			{

				$products_array = array(
				    "fulfillment"=>array(
				        'line_items' => $item_array
				    )
				);
				
				$id_order = $order_data->id;

 				$url = $api_url . '/admin/orders/'.$id_order.'/fulfillments.json';

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_VERBOSE, 0);
				curl_setopt($curl, CURLOPT_HEADER, 1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($products_array));
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$response = curl_exec ($curl);
				curl_close ($curl);
				//echo $customer_email;die;
				$message_content_for_customer = array(	
			'subject' => 'Order Completion',
			'msg_head' => 'Order Completion',
			'msg_body_line_1' => ' Hi, ',
			'msg_body_line_2' => 'your order #'.$order_id .' detail below.',
			'msg_body_line_3' => $invoice
		);
				send_email($customer_email, $message_content_for_customer,'invoice');
				send_email($driver_email, $message_content,' ');
			}
			echo "<script>window.location = '".HOME_URL."'</script>";
		}
	}

			
		//}
 ?>
