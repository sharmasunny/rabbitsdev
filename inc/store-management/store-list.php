 <script>
	jQuery(document).ready(function() {
		jQuery("a.siframe").fancybox({
			maxWidth	: 2000,
			width		: '60%',
			height		: '20%',
			autoSize	: true,
			type		: 'iframe',
		});
	});
</script>
<?php
include('db.php');
class pointLocation {
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices?
 
   

    /*
	*	@method pointLocation
	*/
    function pointLocation() {
    }
 


    /*
	*	@method pointInPolygon
	*	@parm  $point
	*	@parm  $polygon
	*	@parm  $pointOnVertex
	*/
    function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
 
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
 
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }
 
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
 
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is odd, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }



 
 	/*
	*	@method pointOnVertex
	*	@parm  $point
	*	@parm  $vertices
	*/
    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
 
    }
 

 	/*
	*	@method pointStringToCoordinates
	*	@parm  $pointString
	*/
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(",", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
 
}


$stores_table = "stores";	
$zones_table = "zones";	

/*
*	@this query will select all record from stores table 
*	@field store_is_deleted
*	@store_postalcode
*/
$users_sql = "select * from ".$stores_table." where store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";

$users_records = $conn->query($users_sql);
$output = 'var store_db = {';
$count = 0;
if ( $users_records->num_rows > 0 ) { 
	while($obj = $users_records->fetch_assoc()) {
		$output .= "'$count': {'store_id': '" . $obj['store_id'] . "', 'store_name': '" . str_replace("'", "\'", $obj['store_name']) . "','store_address':'".str_replace("'", "\'", $obj['store_address']) ."','store_city':'".$obj['store_city']."','store_state':'".$obj['store_state']."','store_country':'".$obj['store_country']."','store_postalcode':'".$obj['store_postalcode']."','store_latitude':'".$obj['store_latitude']."','store_longitude':'".$obj['store_longitude']."','store_zone_id':'".$obj['store_zone_id']."'},\n";
		$count++;
	}	
}
$output = substr($output, 0, -2);
$output .= '};';
file_put_contents('js/stores.js', $output);
?>
<div class="wrap"> 
	<h2>Stores Listing</h2>
	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#add-store">Add New Store</button>
	<!-- Modal -->
	<div id="add-store" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Add New Store</h4>
	      </div>
	      <div class="modal-body">
	        <form role="form" enctype="multipart/form-data" action="inc/store-management/store-list-add.php" method="post" id="addstoreform">
			  <div class="form-group">
			    <label for="addstorename">Store Name:</label>
			    <input type="text" class="form-control required" id="addstorename" name="addstorename" value="">
			  </div>
			  <div class="form-group">
			    <label for="addstorename">Address:</label>
			    <textarea rows="4" class="form-control required" id="addstoreaddr" name="addstoreaddr"></textarea>
			  </div>
			  <div class="form-group">
			    <label for="addstorename">City:</label>
			    <input type="text" class="form-control" id="addstorecity" name="addstorecity" value="">
			  </div>
			   <div class="form-group">
			    <label for="addstorename">State:</label>
			    <input type="text" class="form-control" id="addstorestate" name="addstorestate" value="">
			   </div>
			   <div class="form-group">
			    <label for="addstorename">Country:</label>
			    <input type="text" class="form-control" id="addstorecountry" name="addstorecountry" value="">
			   </div>
			   <div class="form-group">
			    <label for="addstorename">Postal Code:</label>
			    <input type="text" class="form-control required" id="addstorepincode" name="addstorepincode" value="">
			   </div>
			   <div class="form-group">
			    <label for="addstorename">Image:</label>
			    <input type="file" class="form-control" id="addstoreimage" name="addstoreimage" value="">
			   </div>
			   
			    <h3>Store Operating Hours:</h3>
			   	<div class="form-group">
					<label class="col-sm-2">Monday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_monday_open" name="addstoreoperatinghours_monday_open" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_monday_close" name="addstoreoperatinghours_monday_close" placeholder="Close">
                    </div>
				</div>
				 <div class="form-group">
					<label class="col-sm-2">Tuesday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_tuesday_open" name="addstoreoperatinghours_tuesday_open" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_tuesday_close" name="addstoreoperatinghours_tuesday_close" placeholder="Close">
                    </div>
				</div>
				 <div class="form-group">
					<label class="col-sm-2">Wednesday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_wednesday_open" name="addstoreoperatinghours_wednesday_open" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_wednesday_close" name="addstoreoperatinghours_wednesday_close" placeholder="Close">
                    </div>
				</div>
				 <div class="form-group">
					<label class="col-sm-2">Thursday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_thursday_open" name="addstoreoperatinghours_thursday_open" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_thursday_close" name="addstoreoperatinghours_thursday_close" placeholder="Close">
                    </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2">Friday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_friday_open" name="addstoreoperatinghours_friday_open" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_friday_close" name="addstoreoperatinghours_friday_close" placeholder="Close">
                    </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2">Saturday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_saturday_open" name="addstoreoperatinghours_saturday_open" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_saturday_close" name="addstoreoperatinghours_saturday_close" placeholder="Close">
                    </div>
				</div>
				<div class="form-group">
					<label class="col-sm-2">Sunday</label>
				 	<div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_sunday_open" name="addstoreoperatinghours_sunday_open" placeholder="Open">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" class="form-control timer-picker " id="addstoreoperatinghours_sunday_close" name="addstoreoperatinghours_sunday_close" placeholder="Close">
                    </div>
				</div>

			  	<div class="form-group"> 
				  <label for="editrealtedzones">Related Zones:</label>
					  <select class="form-control" id="relatedzones" name="relatedzones[]" multiple="multiple" size="20">
					  	<?php


					  	/*
						*	@this query will select all record from zones table 
						*/
					  	$all_zones_sql 	  = "select * from ".$zones_table;

						$all_zones_records 	  = $conn->query($all_zones_sql);
						if ( $all_zones_records->num_rows > 0 ) {
							while($all_zones_record = $all_zones_records->fetch_assoc()) {
								 echo "<option id=".$all_zones_record['zone_id']." value=".$all_zones_record['zone_id'].">".$all_zones_record['zone_name']."</option>";
							}	
						}
					  	?>
					  </select>
				 </div>

				 <div class="form-group">
				 	<label for="discount">Discount %</label>
				 	<input type="text" class="form-control required" id="add_discount" name="add_discount" value="">
				 </div>


			  <!--<div class="form-group">
			  <label for="storeaddzone">Select Zone:</label>
				  <select class="form-control" id="storeaddzone" name="storeaddzone">
				    <option id="0" value="0">Select</option>
				  	<?php
				  	$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
					if ( $all_zone_records->num_rows > 0 ) {
						while($all_zone_record = $all_zone_records->fetch_assoc()) {
							 echo "<option id=".$all_zone_record['zone_id']." value=".$all_zone_record['zone_id'].">".$all_zone_record['zone_name']."</option>";
						}	
					}
				  	?>
				  </select>
			 </div>-->
			  <button type="submit" class="btn btn-default">ADD</button>
			</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
	<br/>
	<br/>
	<?php
	$users_sql = "select * from ".$stores_table." where store_is_deleted='0' and store_postalcode!='00000' order by store_id DESC";
	$users_records = $conn->query($users_sql);
	?>
	<form name="stores_list" method="post" action="inc/store-management/store-list-update.php?info=delall">	
	<table class="" id="storelist" style="width:100%">
		<thead>
			<tr>
				<th width="10px" style="background:none"><input type="checkbox" id="alluser" /></th>
				<th width="14px">ID</th>
				<th>Name</th> 
				<!--<th width="215px">Address</th>
				<th width="215px">City</th>
				<th width="215px">State</th>
				<th width="215px">Country</th>  
				<th width="215px">Postal Code</th> -->
				<th width="215px">Zone Name</th>
				<th width="215px">Image</th>
				<!--<th width="215px">Related Zones</th> -->
				<th width="215px">Discount</th>			
				<th style="text-align: center;">Action</th>
			</tr>
		</thead>
		<tbody>				     
			<?php
			$no = 1;
			if ( $users_records->num_rows > 0 ) { ?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('#storelist').dataTable({ 
							"aaSorting": [[ 0, "desc" ]],
						});
						jQuery('#alluser').click(function(event) {  //on click
						if(this.checked) { // check select status
						    jQuery('.userid').each(function() { //loop through each checkbox
						        this.checked = true;  //select all checkboxes with class "checkbox1"              
						    });
						}else{
						    jQuery('.userid').each(function() { //loop through each checkbox
						        this.checked = false; //deselect all checkboxes with class "checkbox1"                      
						    });        
						}
						});
					});	
				</script>
<?php

$zone_coors = array();


/*
*	@this query will select all record from zones table 
*/
$zonecoquery = "select * from zones";


$zonecoorinates = $conn->query($zonecoquery);
$count = 0;
while($zonecoorinate = $zonecoorinates->fetch_assoc()){
	$coordinate = $zonecoorinate['zone_coordinates'];
	$coordinate = explode(" ",$coordinate);
	$zone_coors[$count]['coor'] = $coordinate;
	$zone_coors[$count]['zones'] = $zonecoorinate['zone_id'];
	//$zone_coors[] = $coordinate;
	$count++;	

}
$fzone_coor = array();
//echo "<pre>";
//print_r($zone_coors);
$b = 0;
foreach($zone_coors as $key => $zone_coor) {
	$a = 0;
	foreach($zone_coor['coor'] as $zone){
		$explode_zone = explode(",",$zone);		
		$fzone_coor[$key]['coordy'][$a] = $explode_zone[0];
		$fzone_coor[$key]['coordx'][$a] = $explode_zone[1];
		$fzone_coor[$key]['zonename'][$a] = $zone_coor['zones'];
		$a++;	
	}
	$b++;		
}

				 while($users_record = $users_records->fetch_assoc()) {
					$id        	  = $users_record['store_id'];
					$storename 	  = $users_record['store_name'];
					$storeaddr 	  = $users_record['store_address'];
					$storecity 	  = $users_record['store_city'];
					$storestate   = $users_record['store_state'];
					$storecountry = $users_record['store_country'];
					$storepincode = $users_record['store_postalcode'];
					$storezoneid  = $users_record['store_zone_id'];
					$storeimage	= $users_record['image'];
					$store_discount = $users_record['store_discount'];

					if($storezoneid == "0") {
						$zone_name = "-------";
					} else {
						/*
						*	@this query will select zone_name where zone_id match from zones table 
						*	@field  zone_id
						*	@field 	zone_is_deleted
						*/
						$zones_sql 	  = "select zone_name from ".$zones_table." where zone_id = ".$storezoneid." and zone_is_deleted='0' ";
						$zone_records = $conn->query($zones_sql);
						if ( $zone_records->num_rows > 0 ) {
							while($zone_record = $zone_records->fetch_assoc()) {
								$zone_name = $zone_record['zone_name'];
							}	
						} else {
								$zone_name = "-------";
						}
					}
					$finaladdr = $storeaddr." ".$storecity." ".$storestate." ".$storecountry." ".$store_postalcode;
					//echo $finaladdr;
					$edcoordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($finaladdr) . '&sensor=true&key='.API_KEY);
					
					$edcoordinates = json_decode($edcoordinates);
					$longitude_x  = $edcoordinates->results[0]->geometry->location->lat;
					$latitude_y = $edcoordinates->results[0]->geometry->location->lng; 
					$z = 0;
					//echo $longitude_x."<br/>";
					//echo $latitude_y;

					
					?>
					<tr>
					<th><input class="userid" type="checkbox" name="usercodeid[]" id="usercodeid" value="<?php echo $id; ?>" /></th>
						<td><?php echo $id; ?></td>
						<td nowrap><?php echo $storename; ?></td>
					<!--	<td nowrap style="width:240px;"><?php echo $storeaddr; ?></td>
						<td nowrap><?php echo $storecity; ?></td>
						<td nowrap><?php echo $storestate; ?></td>
						<td nowrap><?php echo $storecountry; ?></td>
						<td nowrap><?php echo $storepincode; ?></td> -->               
						<td nowrap>
							<?php
								
							 foreach($fzone_coor as $indx=>$fzone_coordinate) {
						
								$zonearr = array_unique($fzone_coordinate['zonename']);
								
								$vertices_x = $fzone_coordinate['coordx'];
								$vertices_y = $fzone_coordinate['coordy'];
								
								$points_polygon = count($vertices_x) - 1; 
								
								//if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)){
									$pointLocation = new pointLocation();
									$polygon = $zone_coors[$indx]['coor'];
									//echo "<br>ELSE checking if ".$latitude_y.",".$longitude_x." are in polygon<br>";
									//print_r($polygon);
									if($pointLocation->pointInPolygon($latitude_y.",".$longitude_x, $polygon)=='inside')
									{
										//$zonelist1[] = $zonearr1[0];
								  	$zonequery = "select * from zones where zone_id = '".$zonearr[0]."'";
								  	
								  	$zone_records = $conn->query($zonequery);
								  	while($zone_record = $zone_records->fetch_assoc()){
										$storezonelistarr = array();
									$storezonelist = $zone_record['zone_id'];
									echo $zone_record['zone_name'];
									if(!empty($storezonelist)) {
										
										$storezonelistar = unserialize($storezonelist);
										//print_r($storezonelistar);
										if(count($storezonelistarr) <= 1) {
											$storezonelistarr = array($storezonelistar);
										} else {
											$storezonelistarr = $storezonelistar;
										}
										
										if(is_array($storezonelistarr)) {
											//
											//print_r($storeproductlistarr);
											$storezonelistf = serialize($storezonelistarr);

											/*
											*	@this query will update store zone id record in store table
											*	@field  store_zone_id
											*	@field 	store_id
											*/
											$storesql = "UPDATE ".$stores_table." SET store_zone_id='".$storezonelist."' WHERE store_id=".$id." ";
											
											$conn->query($storesql);
									}
								  	}
									}


									/*
									*	@this query will update zone central zip code record in zones table
									*	@field  zone_central_zipcode
									*	@field 	zone_id
									*/
								  	$zonesmid_sql 	  = "UPDATE zones SET zone_central_zipcode = '$storepincode' WHERE zone_id = '$zonearr[0]'";
								  	
								  	$zonemid_records = $conn->query($zonesmid_sql);
								  	
								}else{
									
								}
							}
							?>
						</td>
						<td>
						<?php 
						if($storeimage !== "" || !empty($storeimage)){
						if (file_exists($_SERVER['DOCUMENT_ROOT'].'/app/uploads/'.$storeimage)) {  ?>
	<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/app/uploads/'.$storeimage;?>" style="width:50px;height:50px;">
						<?php } 
						}
else
{
	?>
	<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/app/uploads/no_image.png';?>" style="width:50px;height:50px;">
	<?php
}	?></td>
<!--<td nowrap><?php 
					$related_zones        = $users_record['related_zones'];
					$related_zoneslistarr1 = array();
					$related_zoneslistarr1 = unserialize($related_zones);
					$zone_names = array();
					if (is_array($related_zoneslistarr1) || is_object($related_zoneslistarr1))
					{

					    foreach($related_zoneslistarr1 as $zoneid) {
							$zone_sql 	  = "select zone_name from ".$zones_table." where zone_id = ".$zoneid."  ";

							$zone_records = $conn->query($zone_sql);

							if ( $zone_records->num_rows==1 ) {
								while($zone_record = $zone_records->fetch_assoc()) {
									$zone_names[] = $zone_record['zone_name'];

								}	
							} else {
								$zone_names = array();
							}
						}
					} else {
						$zone_names = array();
					}
					$zznames = '';
 					if(!empty($zone_names)) { foreach ($zone_names as $value) {   $zznames .= $value.", ";  } echo trim($zznames,', ');  } else { echo 'No Zone selected!'; } ?>
</td>--> 
<td><?php echo $store_discount; ?></td>
						<td style="text-align: center;">							
							<a onclick="javascript:return confirm('Are you sure, want to delete record of <?php echo $storename; ?>?')" href="inc/store-management/store-list-update.php?info=del&did=<?php echo $id;?>">
							<img src="images/delete.png" title="Delete" alt="Delete" />
							</a>
							&nbsp;&nbsp;&nbsp;
							<a href="inc/store-management/store-list-update.php?info=edit&eid=<?php echo $id;?>" class="siframe">
							<img src="images/edit.png" title="Edit" alt="Edit" />
							</a>
						</td>             
					</tr>
				<?php $no += 1;							
				}
				$conn->close();
			} else {
				echo '<h3>No Store Records Found !!</h3>';
			} ?>					
		</tbody>
	</table>
	<p>
		<input type="submit" name="delete" class="add-new-h2 button-secondary" onclick="javascript:return confirm('Are you sure, want to delete all checked record?')" value="Delete">
	</p>
	</form>
</div>
<script>
	jQuery(document).ready(function() {
		 $("#addstoreform").validate();

		$('.timer-picker').timepicker({
			timeFormat: 'hh:mm tt'
		});
	});
</script>