<?php
include('../../constants.php');
include('../../db.php');
$stores_table = "stores";	



$editstoreid = $_POST['editstoreid'];
$editstorename = $_POST['editstorename'];
$editstoreaddr = $_POST['editstoreaddr'];
$editstorecity = $_POST['editstorecity'];
$editstorestate = $_POST['editstorestate'];
$editstorecountry = $_POST['editstorecountry'];
$editstorepincode = $_POST['editstorepincode'];
$editstoreoperationhours = $_POST['editstoreoperationhours'];
$editstoreoperatinghours_monday_open = $_POST['editstoreoperatinghours_monday_open']; 
$editstoreoperatinghours_monday_close = $_POST['editstoreoperatinghours_monday_close']; 
$editstoreoperatinghours_tuesday_open = $_POST['editstoreoperatinghours_tuesday_open']; 
$editstoreoperatinghours_tuesday_close = $_POST['editstoreoperatinghours_tuesday_close'];
$editstoreoperatinghours_wednesday_open = $_POST['editstoreoperatinghours_wednesday_open']; 
$editstoreoperatinghours_wednesday_close = $_POST['editstoreoperatinghours_wednesday_close']; 
$editstoreoperatinghours_thursday_open = $_POST['editstoreoperatinghours_thursday_open']; 
$editstoreoperatinghours_thursday_close = $_POST['editstoreoperatinghours_thursday_close']; 
$editstoreoperatinghours_friday_open = $_POST['editstoreoperatinghours_friday_open']; 
$editstoreoperatinghours_friday_close = $_POST['editstoreoperatinghours_friday_close']; 
$editstoreoperatinghours_saturday_open = $_POST['editstoreoperatinghours_saturday_open']; 
$editstoreoperatinghours_saturday_close = $_POST['editstoreoperatinghours_saturday_close']; 
$editstoreoperatinghours_sunday_open =  $_POST['editstoreoperatinghours_sunday_open']; 
$editstoreoperatinghours_sunday_close =  $_POST['editstoreoperatinghours_sunday_close']; 
$relatedzones = serialize($_POST['relatedzones']);
/*$editstorezone = $_POST['storeeditzone'];*/
$edit_discount = $_POST['edit_discount'];

$finaladdr = $editstoreaddr." ".$editstorecity." ".$editstorestate." ".$editstorecountry." ".$editstorepincode;
$edcoordinates = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($finaladdr) . '&sensor=true');
$edcoordinates = json_decode($edcoordinates);
$editstorelat  = $edcoordinates->results[0]->geometry->location->lat;
$editstorelong = $edcoordinates->results[0]->geometry->location->lng;

if (!empty($_FILES['editstoreimage']['name']))
	{
		$target_dir = "../../uploads/";
		$current_time=time();
		$target_file = $target_dir .$current_time.'_'.basename($_FILES["editstoreimage"]["name"]);

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		if ($_FILES["editstoreimage"]["size"] > 500000) {
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
			if (move_uploaded_file($_FILES["editstoreimage"]["tmp_name"], $target_file)) {
				$filename = basename( $_FILES["editstoreimage"]["name"]);
				$uploaded_image= $current_time.'_'.$filename;
			} else {
				echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
		
			}
			}
	
	}
if (empty($_FILES['editstoreimage']['name']))
		{
		/*
		*	@this query will update a record in stores table
		*	@field  store_name
		*	@field 	store_address
		* 	@field  store_city
		*	@field  store_state
		*	@field 	store_country
		*	@field  store_postalcode
		*	@field 	store_latitude
		*	@field 	store_longitude
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

		$sql = "UPDATE ".$stores_table." SET store_name='".$editstorename."', store_address='".$editstoreaddr."',store_city='".$editstorecity."',store_state='".$editstorestate."',store_country='".$editstorecountry."',store_postalcode='".$editstorepincode."', store_latitude='".$editstorelat."',store_longitude='".$editstorelong."' ,store_zone_id ='', store_operating_hours_monday_open='".$editstoreoperatinghours_monday_open."', 	store_operating_hours_monday_close='".$editstoreoperatinghours_monday_close."', 	store_operating_hours_tuesday_open='".$editstoreoperatinghours_tuesday_open."', store_operating_hours_tuesday_close='".$editstoreoperatinghours_tuesday_close."', 	store_operating_hours_wednesday_open='".$editstoreoperatinghours_wednesday_open."', store_operating_hours_wednesday_close='".$editstoreoperatinghours_wednesday_close."',	store_operating_hours_thursday_open='".$editstoreoperatinghours_thursday_open."',	store_operating_hours_thursday_close='".$editstoreoperatinghours_thursday_close."',store_operating_hours_friday_open='".$editstoreoperatinghours_friday_open."',	store_operating_hours_friday_close='".$editstoreoperatinghours_friday_close."',store_operating_hours_saturday_open='".$editstoreoperatinghours_saturday_open."',	store_operating_hours_saturday_close='".$editstoreoperatinghours_saturday_close."',	store_operating_hours_sunday_open='".$editstoreoperatinghours_sunday_open."',store_operating_hours_sunday_close='".$editstoreoperatinghours_sunday_close."' , related_zones='".$relatedzones."' , store_discount='".$edit_discount."' WHERE store_id=".$editstoreid." ";
		}
		else{

		/*
		*	@this query will update a record in stores table
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
			$sql = "UPDATE ".$stores_table." SET store_name='".$editstorename."', store_address='".$editstoreaddr."',store_city='".$editstorecity."',store_state='".$editstorestate."',store_country='".$editstorecountry."',store_postalcode='".$editstorepincode."', store_latitude='".$editstorelat."',store_longitude='".$editstorelong."' ,image='".$uploaded_image."' ,store_zone_id ='', store_operating_hours_monday_open='".$editstoreoperatinghours_monday_open."', 	store_operating_hours_monday_close='".$editstoreoperatinghours_monday_close."', 	store_operating_hours_tuesday_open='".$editstoreoperatinghours_tuesday_open."', store_operating_hours_tuesday_close='".$editstoreoperatinghours_tuesday_close."', 	store_operating_hours_wednesday_open='".$editstoreoperatinghours_wednesday_open."', store_operating_hours_wednesday_close='".$editstoreoperatinghours_wednesday_close."',	store_operating_hours_thursday_open='".$editstoreoperatinghours_thursday_open."',	store_operating_hours_thursday_close='".$editstoreoperatinghours_thursday_close."',store_operating_hours_friday_open='".$editstoreoperatinghours_friday_open."',	store_operating_hours_friday_close='".$editstoreoperatinghours_friday_close."',store_operating_hours_saturday_open='".$editstoreoperatinghours_saturday_open."',	store_operating_hours_saturday_close='".$editstoreoperatinghours_saturday_close."',	store_operating_hours_sunday_open='".$editstoreoperatinghours_sunday_open."',store_operating_hours_sunday_close='".$editstoreoperatinghours_sunday_close."', related_zones='".$relatedzones."' , store_discount='".$edit_discount."'   WHERE store_id=".$editstoreid." ";
		}

if ($conn->query($sql) === TRUE) {
   echo "<script>alert('Record Updated !!');</script>";
   echo "<script>parent.jQuery.fancybox.close();</script>";
   echo "<script>parent.location.reload(true);</script>";
  
} else {
	echo "<script>alert('Error while Updating Record !!');</script>";
	echo "<script>parent.jQuery.fancybox.close();</script>";
    echo "<script>parent.location.reload(true);</script>";
}
?>