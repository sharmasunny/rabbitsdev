<?php
include('../../constants.php');
include('../../db.php');
$stores_table = "stores";

if(!empty($_POST)) {
	$addstorename = $_POST['addstorename'];
	$addstoreaddr = $_POST['addstoreaddr'];
	$addstorecity = $_POST['addstorecity'];
	$addstorestate = $_POST['addstorestate'];
	$addstorecountry = $_POST['addstorecountry'];
	$addstorepincode = $_POST['addstorepincode'];
	$addstorezone = $_POST['storeaddzone']; 
	$addstoreoperatinghours_monday_open = $_POST['addstoreoperatinghours_monday_open']; 
    $addstoreoperatinghours_monday_close = $_POST['addstoreoperatinghours_monday_close']; 
    $addstoreoperatinghours_tuesday_open = $_POST['addstoreoperatinghours_tuesday_open']; 
    $addstoreoperatinghours_tuesday_close = $_POST['addstoreoperatinghours_tuesday_close'];
    $addstoreoperatinghours_wednesday_open = $_POST['addstoreoperatinghours_wednesday_open']; 
    $addstoreoperatinghours_wednesday_close = $_POST['addstoreoperatinghours_wednesday_close']; 
    $addstoreoperatinghours_thursday_open = $_POST['addstoreoperatinghours_thursday_open']; 
    $addstoreoperatinghours_thursday_close = $_POST['addstoreoperatinghours_thursday_close']; 
    $addstoreoperatinghours_friday_open = $_POST['addstoreoperatinghours_friday_open']; 
    $addstoreoperatinghours_friday_close = $_POST['addstoreoperatinghours_friday_close']; 
    $addstoreoperatinghours_saturday_open = $_POST['addstoreoperatinghours_saturday_open']; 
    $addstoreoperatinghours_saturday_close = $_POST['addstoreoperatinghours_saturday_close']; 
    $addstoreoperatinghours_sunday_open =  $_POST['addstoreoperatinghours_sunday_open']; 
    $addstoreoperatinghours_sunday_close =  $_POST['addstoreoperatinghours_sunday_close']; 
    $relatedzones = serialize($_POST['relatedzones']);
    $add_discount = $_POST['add_discount'];

	$finaladdr = $addstoreaddr." ".$addstorecity." ".$addstorestate." ".$addstorecountry." ".$addstorepincode;
	$coordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($finaladdr) . '&sensor=true&key='. API_KEY);
	
	$coordinates = json_decode($coordinates);
	$addstorelat = $coordinates->results[0]->geometry->location->lat;
	$addstorelong = $coordinates->results[0]->geometry->location->lng;
	
	//$target_dir = "uploads/";
	$target_dir = "../../uploads/";
	$current_time=time();
	$target_file = $target_dir .$current_time.'_'.basename($_FILES["addstoreimage"]["name"]);

	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	
		
	if(!empty($_FILES["addstoreimage"]["name"]))
	{
		if ($_FILES["addstoreimage"]["size"] > 500000) {
			echo "<script>alert('Sorry, your file is too large.');</script>";
			$uploadOk = 0;
		}
		
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
			$uploadOk = 0;
		}
		
		if ($uploadOk == 0) {
			echo "<script>alert('Sorry, your file was not uploaded.');</script>";
		
		} else {
			if (move_uploaded_file($_FILES["addstoreimage"]["tmp_name"], $target_file)) {
				$filename = basename( $_FILES["addstoreimage"]["name"]);
				$uploaded_filename= $current_time.'_'.$filename;
			} else {
				echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
			
			}
			}
	}
	/*$sql = "INSERT INTO ".$stores_table." (store_name, store_address, store_city, store_state, store_country, store_postalcode, store_latitude, store_longitude ,store_zone_id) VALUES ('".$addstorename."', '".$addstoreaddr."', '".$addstorecity."', '".$addstorestate."', '".$addstorecountry."', '".$addstorepincode."', '".$addstorelat."', '".$addstorelong."' ,".$addstorezone.")";
	*/



	

	/*
	*	@this query will insert a record in stores table
	*	@field  store_name
	*	@field 	store_address
	* 	@field  store_city
	*	@field  store_state
	*	@field 	store_country
	*	@field  store_postalcode
	*	@field 	store_latitude
	*	@field 	store_longitude
	*	@field  image
	*	@field  store_operating_hours_monday_open
	*	@field  store_operating_hours_monday_close
	*	@field  store_operating_hours_tuesday_open
	*	@field  store_operating_hours_tuesday_close
	*	@field  store_operating_hours_wednesday_open
	*	@field	store_operating_hours_wednesday_close
	*	@field	store_operating_hours_thursday_open
	*	@field	store_operating_hours_thursday_close
	*	@field	store_operating_hours_friday_open
	*	@field	store_operating_hours_friday_close
	*	@field	store_operating_hours_saturday_open
	*	@field	store_operating_hours_saturday_close
	*	@field	store_operating_hours_sunday_open
	*	@field	store_operating_hours_sunday_close
	*	@field	related_zones,store_discount
	*/

	$sql = "INSERT INTO ".$stores_table." (store_name, store_address, store_city, store_state, store_country, store_postalcode, store_latitude, store_longitude, image ,store_operating_hours_monday_open, 	store_operating_hours_monday_close, 	store_operating_hours_tuesday_open, store_operating_hours_tuesday_close, 	store_operating_hours_wednesday_open, store_operating_hours_wednesday_close,	store_operating_hours_thursday_open,	store_operating_hours_thursday_close,store_operating_hours_friday_open,	store_operating_hours_friday_close,store_operating_hours_saturday_open,	store_operating_hours_saturday_close,	store_operating_hours_sunday_open, store_operating_hours_sunday_close,related_zones,store_discount  ) VALUES ('".$addstorename."', '".$addstoreaddr."', '".$addstorecity."', '".$addstorestate."', '".$addstorecountry."', '".$addstorepincode."', '".$addstorelat."', '".$addstorelong."','".$uploaded_filename."', '".$addstoreoperatinghours_monday_open."','".$addstoreoperatinghours_monday_close."','".$addstoreoperatinghours_tuesday_open."','".$addstoreoperatinghours_tuesday_close."','".$addstoreoperatinghours_wednesday_open."','".$addstoreoperatinghours_wednesday_close."','".$addstoreoperatinghours_thursday_open."','".$addstoreoperatinghours_thursday_close."','".$addstoreoperatinghours_friday_open."','".$addstoreoperatinghours_friday_close."','".$addstoreoperatinghours_saturday_open."','".$addstoreoperatinghours_saturday_close."','".$addstoreoperatinghours_sunday_open."','".$addstoreoperatinghours_sunday_close."','".$relatedzones."','".$add_discount."' )";

	if ($conn->query($sql) === TRUE) {
	   echo "<script>alert('Record Added !!');</script>";
	   echo "<script>window.location = '".HOME_URL."'</script>";
	  
	} else {
		echo "<script>alert('Error while Adding Record !!');</script>";
		echo "<script>window.location = '".HOME_URL."'</script>";
	}
} else {
	echo "<script>window.location = '".HOME_URL."'</script>";
}

?>