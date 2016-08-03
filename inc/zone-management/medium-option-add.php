<?php
include('../../constants.php');
include('../../db.php');
$zone_table = "medium_zone_detail";	
if(!empty($_POST)) {
	$zone_from = $_POST['zone_from'];
	$zone_to = $_POST['zone_to'];
	$price = $_POST['price'];
	$price_hot = $_POST['price_hot'];
	$price_urgent = $_POST['price_urgent'];
	if($zone_from == "" || $zone_to == "")
	{
		echo "<script>alert('Error while Adding Record. Please select zone!!');</script>";
		echo "<script>window.location = '".HOME_URL."'</script>";
	}
	else
	{	
		/*
		*	@this query will count all record from zone table 
		*/
		$qr=" select count(*) as total from ".$zone_table." where zone_from=".$zone_from." and zone_to =".$zone_to." ";
		$result = $conn->query($qr);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if($row['total'] > 0)
				{
				echo "<script>alert('Error while Adding Record. Zone option already exists!!');</script>";
				echo "<script>window.location = '".HOME_URL."'</script>";
				}
			}
		}
		/*
		*	@this query will insert record from zone table 
		*/
		$sql = "INSERT INTO ".$zone_table." (zone_from, zone_to, price_regular, price_hot, price_urgent ) VALUES (".$zone_from.", ".$zone_to.", ".$price.", ".$price_hot.", ".$price_urgent.")";
		if ($conn->query($sql) === TRUE) {
		   echo "<script>alert('Record Added !!');</script>";
		   echo "<script>window.location = '".HOME_URL."'</script>";
		  
		} else {
			echo "<script>alert('Error while Adding Record !!');</script>";
			echo "<script>window.location = '".HOME_URL."'</script>";
		}
	}
} else {
	echo "<script>window.location = '".HOME_URL."'</script>";
}

?>