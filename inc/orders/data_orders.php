<?php
include('../../db.php');
include('../../constants.php');

// include('constants.php');
session_start();
$orders_table = "orders";	
$drivers_table = "drivers";
$driver_id =$_SESSION['driver_id'];
/*function set_selected($desired_value, $new_value)
{

    if($desired_value==$new_value)
    {
	
	echo ' selected="selected"';
    }

   // if($status_disabled==0){
	//echo ' disabled="disabled"';	
    //}
	
}*/

$status_disabled = 0;
$users_sql1 = "SELECT driver_zone_id FROM " . $drivers_table . " WHERE driver_id = " .$driver_id;
$users_records1 = $conn->query($users_sql1);
 while($users_record1 = $users_records1->fetch_assoc()) {
	$in = implode(",",unserialize($users_record1['driver_zone_id']));
 }

$users_sql = "select * from ".$orders_table.
" where ((order_driver_id = ". $driver_id ." 
) or (order_zone_id in(". $in .")  AND order_driver_id='0')) order by id DESC";
// echo $users_sql;die;
 $users_records = $conn->query($users_sql);
 $i = 0;
 if ( $users_records->num_rows > 0 ) {
 	 while($users_record = $users_records->fetch_assoc()) {
 	 	$confirm_value = $users_record['confirmation'];
 	 	$selected_value = $users_record['order_status'];
 	 	$order_number	= $users_record['order_number'];
		$customer_email 	  = $users_record['customer_email'];
		$id = $users_record['order_id'];
			$index_id 		= $users_record['id'];
			$order_id = json_decode($users_record['all_record'])->order_id; 
			$destination = json_decode($users_record['all_record'])->line_items['0']->destination_location; 
			$title = json_decode($users_record['all_record'])->line_items['0']->title; 
			$quantity = json_decode($users_record['all_record'])->line_items['0']->quantity; 
			
			$name = json_decode($users_record['all_record'])->line_items['0']->name; 
			$original_location_name = json_decode($users_record['all_record'])->line_items['0']->origin_location->name; 
			$original_location_address1 = json_decode($users_record['all_record'])->line_items['0']->origin_location->address1; 
			$original_location_address2 = json_decode($users_record['all_record'])->line_items['0']->origin_location->address2; 
			$original_location_city = json_decode($users_record['all_record'])->line_items['0']->origin_location->city; 
			$original_location_zip = json_decode($users_record['all_record'])->line_items['0']->origin_location->zip; 
			$subtotal_price = json_decode($users_record['all_record'])->subtotal_price; 
			$total_price = json_decode($users_record['all_record'])->total_price; 



 	 	// echo $selected_value;die;
 	 	//$row[$i]['order_id'] = $id;
 	 	$row[$i]['order_id'] = '<button type="button" class="btn btn-link  btn-lg orderdetails-model" id="'.$index_id.'"  value ="'.$id.'" >#'.$id.'</button>';
 	 			if(empty($source_zip = json_decode($users_record['all_record'])->line_items['0']->origin_location->zip))
						{
							$row[$i]['pickup_zone'] = "No Postal Code";
						}
						else{
							
						$row[$i]['pickup_zone'] = '<button type="button"   class="btn btn-link  btn-lg pickup-zone-model" 
						value ="' . $source_zip .'">
						' . $source_zip .'
						</button>';
						
						 }
						 if(empty($dest_zip = json_decode($users_record['all_record'])->line_items['0']->destination_location->zip))
						{
							$row[$i]['drop_zone'] = "No Postal Code";
						}
						else{
							
						$row[$i]['drop_zone'] = '<button type="button" class="btn btn-link  btn-lg dropoff-zone-model" 
						value ="'. $dest_zip .'">
						' . $dest_zip .'
						</button>';
						
						 }

		 if($confirm_value != 2){ 
					$row[$i]['action'] = '<form action="inc/orders/confirm_stat.php" method="post" accept-charset="utf-8">
									<input type="submit" name="confirm_stat_submit" class="btn btn-success" value="Accept"/> 
									<input type="submit" name="confirm_stat_submit" class="btn btn-danger" value="Reject"/> 
									<input type="hidden" name="order_id" value= "'. $id .'" style="display:none;">
								</form>';
							}
							 
							else{

									switch ($selected_value) {
										case 1:
											$row[$i]['action'] =  '<input type="button" data-id="'.$id.'" class="btn btn-primary picked-up-item"  value="Picked up item"/>';
										break;
										case 2:
											$row[$i]['action'] = '<input type="button" data-id="'.$id.'" class="btn btn-primary" id="delivered" value="Delivered"/>';
										break;
										case 3:
											$row[$i]['action'] = '<input type="button" data-id="'.$id.'" class="btn btn-primary" id="delivered" value="Fulfilled"/>';
										break;
										case 4:
											$row[$i]['action'] = "ORDER IS FULFILLED";
										break;
										default:
											$row[$i]['action'] = "ORDER IS FULFILLED";
										break;
									}


								// if($selected_value =='4'){
								// $row[$i]['action'] = "FULFILLED";
								// }
								// else{		$selected = "selected";		 		
								// 		$row[$i]['action'] = '<form id="set-status" action="inc/orders/set_status.php" method="post" accept-charset="utf-8">
								// 			<input type="hidden" name="order_number" value="'. $order_number .'"/>
								// 			<input type="hidden" name="customer_email" value="'. $customer_email .'"/>
								// 			<input type="hidden" name="order_id" value= "'. $id .'" style="display:none;">
								// 			<select name="order_status" class="order_stat">
								// 				<option value="select" required>Please select status</option>
								// 				<option value="1" '.($selected_value=="1"?$selected:"").' > Picked UP</option>
								// 				<option value="2" '.($selected_value=="2"?$selected:"").' >On the way</option>
								// 				<option value="3" '.($selected_value=="3"?$selected:"").'>Deliverd</option>
								// 				<option value="4" '.($selected_value=="4"?$selected:"").' >FULFILLED</option>
												
								// 			</select>
								// 		</form>';

							 //}	
							
}
$i +=1;
 	 }
		$Data=json_encode($row); 
		echo $newData= '{"data":'.$Data.'}';die;
 	}
?>