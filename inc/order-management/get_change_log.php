<?php
include('../../constants.php');
include('../../db.php');
?>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css"/>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/jquery.dataTables.css"/>
 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/bootstrap.css"/>
 <script src="<?php echo APP_URL; ?>/js/jquery-1.11.3.min.js"></script>
 <script src="<?php echo APP_URL; ?>/js/bootstrap.min.js"></script>

<script src="<?php echo APP_URL; ?>/js/jquery.dataTables.js"></script>
 <script>
$(document).ready(function(){
	$('#changelog').dataTable({ 
	});
});
	</script>
	<?php

$orders_table = "orders";	
$drivers_table = "drivers";	
$zones_table="zones";
$order_driver_history_table="order_driver_history";
$driver_list=array();
$zone_list=array();




/*
*	@this query will select all record from drivers table
*	@field zone_is_deleted
*/

$users_sql = "select * from ".$drivers_table." ";


$users_records = $conn->query($users_sql);
if ($users_records->num_rows > 0) {
			while($driver = $users_records->fetch_assoc()) {
				$driver_list[$driver['driver_id']]=$driver;
					}
		}

$all_zones_sql = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
					if ( $all_zone_records->num_rows > 0 ) {
						while($all_zone_record = $all_zone_records->fetch_assoc()) {
							$zone_list[$all_zone_record['zone_id']]=$all_zone_record['zone_name'];
						}	
					}		
//echo "<pre>";
//print_r($driver_list);
//echo "</pre>";
?>
<div class="wrap"> 
					<h2>Driver change log for order <?php echo $_GET['oid']; ?></h2>
					<br/>
					
					<?php
$order_id = isset($_GET['oid']) ? $_GET['oid'] : '';
if ( !empty( $order_id ) ) {




	/*
	*	@this query will select Order id record order driver history table
	*	@field order_id
	*/

	$qr=" select * from ".$order_driver_history_table." where order_id=".$order_id." order by id DESC ";


		$result = $conn->query($qr);
		if ($result->num_rows > 0) {
			echo "<table id='changelog'>";
			$i=0;
			while($row = $result->fetch_assoc()) {
				if($i%2 !== 0){$str="odd";}else{$str="";}
				
				//print_r($driver_list[$row['from_driver']]);
				$from_driver=$driver_list[$row['from_driver']]['driver_name'];
				$to_driver=$driver_list[$row['to_driver']]['driver_name'];
				$from_store=$zone_list[$driver_list[$row['from_driver']]['driver_zone_id']];
				$to_store=$zone_list[$driver_list[$row['to_driver']]['driver_zone_id']];
				echo "<tr class=$str>";
				echo "<td>";
				echo $row['date'];
				echo "</td>";
				echo "<td>";
				echo "Driver <b>'$from_driver'</b> of <b>'$from_store'</b> store delivered parcel to driver <b>'$to_driver'</b> of <b>'$to_store'</b>";
				echo "</td>";
				echo "</tr>";
				$i++;
			}
			echo "</table>";
		}
}

?>
