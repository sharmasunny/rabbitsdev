<?php
include('../../constants.php');
include('../../db.php');
$stores_table = "stores";	
$zones_table = "zones";	

/*
*	@this query will select all records from  stores table 
*/
$users_sql = "select * from ".$stores_table." where store_is_deleted='0' order by store_id DESC";
$users_records = $conn->query($users_sql);

$info = isset($_GET['info']) ? $_GET['info'] : '';
if ( !empty( $info ) ) {

	if ( $info=="del" ) {
		$delid = $_GET["did"];
		if ( !empty( $delid ) ) {
			

			/*
			*	@this query will delete store record from stores table 
			*/
			$sql = "update ".$stores_table." set store_is_deleted='1' where store_id=".$delid." ";

			if ($conn->query($sql) === TRUE) {

			   echo "<script>alert('Record Deleted !!');</script>";
			   echo "<script>window.location = '".HOME_URL."'</script>";
			  
			} else {
				echo "<script>alert('Error while Deleting Record !!');</script>";
				echo "<script>window.location = '".HOME_URL."'</script>";
			}

		}
	}
	if($info == "delall") {
		if(isset($_POST['delete'])) {
			$delete_id = $_POST['usercodeid'];
			$id = count($delete_id );
			if (count($id) > 0) {
				if(!empty($delete_id)) {
					foreach ($delete_id as $id_d) {
						/*
						*	@this query will delete selected store record from stores table 
						*/
						$delete = "update ".$stores_table." set store_is_deleted='1' where store_id=".$id_d." ";
						$conn->query($delete);
					}
				} else {
					echo "<div style='clear:both;'></div><div class='updated' id='message'><p><strong>".__('No records selected.')."</strong></p></div>";
				}
				
			}
			if($delete) {
				echo "<script>alert('Selected Records Deleted !!');</script>";
				echo "<script>window.location = '".HOME_URL."'</script>";
			}
		}
	}
	if($info="edit") {
		$editlid = $_GET["eid"];
		if ( !empty( $editlid ) ) { 
			/*
			*	@this query will select all record from stores table 
			*/
			$selected_store_sql = "select * from ".$stores_table." where store_id = ".$editlid." ";
			$selected_store_records = $conn->query($selected_store_sql);
			
			if ( $selected_store_records->num_rows > 0 ) {
				while($selected_store_record = $selected_store_records->fetch_assoc()) {	
					$storezoneid = $selected_store_record['store_zone_id'];
					$zones_sql 		  = "select zone_name from ".$zones_table." where zone_id = ".$storezoneid." ";
					$zone_records 	  = $conn->query($zones_sql);
					if ( $zone_records->num_rows > 0 ) {
						while($zone_record = $zone_records->fetch_assoc()) {
							$zone_name = $zone_record['zone_name'];
						}	
					}
				?>
				<div class="wrap"> 
					<h2>Edit Store Details</h2>
					<br/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css"/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/jquery-ui.css"/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/jquery-ui-timepicker-addon.css"/>
					 <link rel="stylesheet" href="<?php echo APP_URL; ?>/css/bootstrap.css"/>
					 <script src="<?php echo APP_URL; ?>/js/jquery-1.11.3.min.js"></script>
					 <script src="<?php echo APP_URL; ?>/js/bootstrap.min.js"></script>
					 <script src="<?php echo APP_URL; ?>/js/jquery-ui.js"></script>
					 <script src="<?php echo APP_URL; ?>/js/jquery-ui-timepicker-addon.js"></script>
					 <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
					 

					 <form role="form" enctype="multipart/form-data" action="store-list-func.php" method="post" id="editstoreform">
					  <div class="form-group">
					    <label for="editid">ID:</label>
					    <input type="text" class="form-control" id="editstoreid" name="editstoreid" readonly value="<?php echo $selected_store_record['store_id']; ?>">
					  </div>
					  <div class="form-group">
					    <label for="editstorename">Store Name:</label>
					    <input type="text" class="form-control" id="editstorename" name="editstorename" value="<?php echo $selected_store_record['store_name']; ?>">
					  </div>
					   <div class="form-group">
					    <label for="editstorename">Address:</label>
					    <textarea rows="4" class="form-control required" id="editstoreaddr" name="editstoreaddr"><?php echo $selected_store_record['store_address']; ?></textarea>
					  </div>
					  <div class="form-group">
					    <label for="editstorename">City:</label>
					    <input type="text" class="form-control" id="editstorecity" name="editstorecity" value="<?php echo $selected_store_record['store_city']; ?>">
					  </div>
					   <div class="form-group">
					    <label for="editstorename">State:</label>
					    <input type="text" class="form-control" id="editstorestate" name="editstorestate" value="<?php echo $selected_store_record['store_state']; ?>">
					   </div>
					   <div class="form-group">
					    <label for="editstorename">Country:</label>
					    <input type="text" class="form-control" id="editstorecountry" name="editstorecountry" value="<?php echo $selected_store_record['store_country']; ?>">
					   </div>
					   <div class="form-group">
					    <label for="editstorename">Postal Code:</label>
					    <input type="text" class="form-control required" id="editstorepincode" name="editstorepincode" value="<?php echo $selected_store_record['store_postalcode']; ?>">
					   </div>
					    <div class="form-group">
			    <label for="addstorename">Image:</label>
			    <input type="file" class="form-control" id="editstoreimage" name="editstoreimage" value="">
			   
			   <?php 
						if($selected_store_record['image'] !== "" || !empty($selected_store_record['image'])){
						if (file_exists($_SERVER['DOCUMENT_ROOT'].'/app/uploads/'.$selected_store_record['image'])) {  ?>
	<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/app/uploads/'.$selected_store_record['image'];?>" style="width:50px;height:50px;">
						<?php } 
						}
else
{
	?>
	<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/app/uploads/no_image.png';?>" style="width:50px;height:50px;">
	<?php
}	?></div>
		

		  <h3>Store Operating Hours:</h3>
			   	<div class="form-group">
					<label class="col-sm-2">Monday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_monday_open" name="editstoreoperatinghours_monday_open" value="<?php echo $selected_store_record['store_operating_hours_monday_open']; ?>" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_monday_close" name="editstoreoperatinghours_monday_close" value="<?php echo $selected_store_record['store_operating_hours_monday_close']; ?>" placeholder="Close">
                    </div>
				</div>
				 <div class="form-group">
					<label class="col-sm-2">Tuesday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_tuesday_open" name="editstoreoperatinghours_tuesday_open" value="<?php echo $selected_store_record['store_operating_hours_tuesday_open']; ?>" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_tuesday_close" name="editstoreoperatinghours_tuesday_close" value="<?php echo $selected_store_record['store_operating_hours_tuesday_close']; ?>" placeholder="Close">
                    </div>
				</div>
				 <div class="form-group">
					<label class="col-sm-2">Wednesday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_wednesday_open" name="editstoreoperatinghours_wednesday_open" value="<?php echo $selected_store_record['store_operating_hours_wednesday_open']; ?>" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_wednesday_close" name="editstoreoperatinghours_wednesday_close" value="<?php echo $selected_store_record['store_operating_hours_wednesday_close']; ?>" placeholder="Close">
                    </div>
				</div>
				 <div class="form-group">
					<label class="col-sm-2">Thursday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_thursday_open" name="editstoreoperatinghours_thursday_open" value="<?php echo $selected_store_record['store_operating_hours_thursday_open']; ?>" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_thursday_close" name="editstoreoperatinghours_thursday_close" value="<?php echo $selected_store_record['store_operating_hours_thursday_close']; ?>" placeholder="Close">
                    </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2">Friday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_friday_open" name="editstoreoperatinghours_friday_open" value="<?php echo $selected_store_record['store_operating_hours_friday_open']; ?>" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_friday_close" name="editstoreoperatinghours_friday_close" value="<?php echo $selected_store_record['store_operating_hours_friday_close']; ?>" placeholder="Close">
                    </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2">Saturday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_saturday_open" name="editstoreoperatinghours_saturday_open" value="<?php echo $selected_store_record['store_operating_hours_saturday_open']; ?>" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_saturday_close" name="editstoreoperatinghours_saturday_close" value="<?php echo $selected_store_record['store_operating_hours_saturday_close']; ?>" placeholder="Close">
                    </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2">Sunday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_sunday_open" name="editstoreoperatinghours_sunday_open" value="<?php echo $selected_store_record['store_operating_hours_sunday_open']; ?>" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="editstoreoperatinghours_sunday_close" name="editstoreoperatinghours_sunday_close" value="<?php echo $selected_store_record['store_operating_hours_sunday_close']; ?>" placeholder="Close">
                    </div>
				</div>

				
				 <div class="form-group"> 
					  <label for="editrealtedzones">Related Zones:</label>
						   <select class="form-control" id="relatedzones" name="relatedzones[]" multiple="multiple" size="20">
						  	<?php
						  	$related_zones = $selected_store_record['related_zones'];
							$related_zoneslistarr = array();
							$related_zoneslistarr = unserialize($related_zones);
							/*
							*	@this query will select all record from zones table 
							*/
						  	$all_zones_sql 	  = "select * from ".$zones_table;
							$all_zones_records 	  = $conn->query($all_zones_sql);
							if ( $all_zones_records->num_rows > 0 ) {
								while($all_zones_record = $all_zones_records->fetch_assoc()) {
									 if(in_array($all_zones_record['zone_id'],$related_zoneslistarr)) {
									 	$selected_zone = "selected=selected";
									 } else {
									 	$selected_zone = "";
									 }
									 echo "<option id=".$all_zones_record['zone_id']." ".$selected_zone." value=".$all_zones_record['zone_id'].">".$all_zones_record['zone_name']."</option>";
								}	
							}
						  	?>
							</select>
				</div>


				<div class="form-group">
				 	<label for="discount">Discount %</label>
				 	<input type="text" class="form-control required" id="edit_discount" name="edit_discount" value="<?php echo $selected_store_record['store_discount']; ?>">
				 </div>

					  <button type="submit" class="btn btn-default">Update</button>
					</form>
				</div>
				<?php
				}	
			}
		 }
	}
}	
?>
<script>
	jQuery(document).ready(function() {
		$("#editstoreform").validate();
		
		$('.timer-picker').timepicker({
			timeFormat: 'hh:mm tt'
		});
	});
</script>

	  <!--<div class="form-group">
					  <label for="storeeditzone">Select Zone:</label>
						  <select class="form-control" id="storeeditzone" name="storeeditzone">
						    <option id="0" value="0">Select</option>
						  	<?php
						  	$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
							$all_zone_records 	  = $conn->query($all_zones_sql);
							if ( $all_zone_records->num_rows > 0 ) {
								while($all_zone_record = $all_zone_records->fetch_assoc()) {
									 if($all_zone_record['zone_id'] == $storezoneid) {
									 	$selected_zone = "selected=selected";
									 } else {
									 	$selected_zone = "";
									 }
									 echo "<option id=".$all_zone_record['zone_id']." ".$selected_zone." value=".$all_zone_record['zone_id'].">".$all_zone_record['zone_name']."</option>";
								}	
							}
						  	?>
						  </select>
					 </div>-->