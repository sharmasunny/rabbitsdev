<?php
include('../../constants.php');
include('../../db.php');
$drivers_table = "drivers";	
$adddrivername = $_POST['adddrivername'];

$adddriveremail = $_POST['adddriveremail'];
$adddriverpwd = $_POST['adddriverpwd'];

$adddriverzone = serialize($_POST['driveraddzone']); 

/*
*	@this query will insert record in driver table 
*	@field driver_name
*	@field driver_zone_id
*/

			$selected_driver_sql = "select * from ".$drivers_table." where driver_email = '".$adddriveremail."' ";
			$selected_driver_records = $conn->query($selected_driver_sql);
			
			if ( $selected_driver_records->num_rows > 0 ) {
					 echo "<script>alert('Email already exists, please try again with different email address !!');</script>";
					 echo "<script>window.location = '".HOME_URL."'</script>";
			}else{
				$sql = "INSERT INTO ".$drivers_table." (driver_name,driver_email,password, driver_zone_id) VALUES ('".$adddrivername."','".$adddriveremail."','".md5($adddriverpwd)."', '".$adddriverzone."')";


				if ($conn->query($sql) === TRUE) {
				   echo "<script>alert('Record Added !!');</script>";
				   echo "<script>window.location = '".HOME_URL."'</script>";
				  
				} else {
					echo "<script>alert('Error while Adding Record !!');</script>";
					echo "<script>window.location = '".HOME_URL."'</script>";
				}
			
			}



?>
