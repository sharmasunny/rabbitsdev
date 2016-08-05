<?php
include('../../db.php');
include('../../constants.php');
session_start();

$orders_table = "orders";	
$drivers_table = "drivers";

$id = $_POST['id'];

$order_sql = "SELECT * FROM " . $orders_table . " WHERE order_id =" .$id;
$order_records = $conn->query($order_sql);
while($order_record = $order_records->fetch_assoc()) {
	

		// print_r(json_decode($order_record['all_record'])); die();

		$order	= json_decode($order_record['all_record']);




		$html = '<table class="table">
			    <thead>
			      <tr>
			        <th>Order Id</th>
			        <th>Title</th>
			        <th>Quantity</th>
			        <th>Name</th>
			        <th>Pickup location</th>
			        <th>Drop off location</th>
			      </tr>
			    </thead>
			    <tbody>';

			foreach ($order->line_items as $key => $item) {
				$html .= '<tr>
							<td>'.$order_record['order_id'].'</td>
							<td>'.$item->title.'</td>
							<td>'.$item->quantity.'</td>
							<td>'.$item->name.'</td>
							<td>'.( $item->origin_location ? $item->origin_location->name .' , '.$item->origin_location->address1 .'</br>'.$item->origin_location->city.' , '.$item->origin_location->province_code .' , '.$item->origin_location->country_code.'<br/>'.$item->origin_location->zip  : 'No Pickup location' ).'</td>
							<td>'.( $item->destination_location  ? $item->destination_location->name.' , '.$item->destination_location->address1 .'</br>'.$item->destination_location->city.' , '.$item->destination_location->province_code .' , '.$item->destination_location->country_code.'<br/>'.$item->destination_location->zip  : 'No Drop off Location' ).'</td>
						 </tr>';
			  
			}  

		$html .= '</tbody>
			</table>';

	 echo $html;
	exit;

}
	
?>

