<?php
include('../../db.php');
include('../../header.php');

$orders_table = "orders";	
$drivers_table = "drivers";
$driver_id =$_SESSION['driver_id'];


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
 if ( $users_records->num_rows > 0 ) {
 	 while($users_record = $users_records->fetch_assoc()) {
 //	 	array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");
$row = array("id" => "121","name" => "122","create" => "123","update" => "124" );
 	 	echo '{"data":[' . json_encode($row) ']}';
//echo '{"data":[{"id":3939019654,"email":"srikant@mobilyte.com","closed_at":"hey","created_at":"het"}]}';
//die;

 	// print_r($users_record);die;
 	 }
 	}
?>