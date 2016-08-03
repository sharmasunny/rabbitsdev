 <?php
include('db.php');
$zones_table="zones";
$zone_detail_table = "zone_detail";	
$users_sql = "select * from ".$zone_detail_table;
$users_records = $conn->query($users_sql);
/*
*	@this query will select all record from zone table 
*/
$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
$zone_list=array();
 while($user_record = $all_zone_records->fetch_assoc()) {
	 $zone_list[$user_record['zone_id']]=$user_record['zone_name'];
 }
 
?>
<div class="wrap"> 
	<!--<h2>Zone Options Listing</h2>-->
	<h2>Medium Size Delivery Price List</h2>
	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#add-zone-option">Add New Zone Option</button>
	<!-- Modal -->
	<div id="add-zone-option" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Add New Zone</h4>
	      </div>
	      <div class="modal-body">
	        <form role="form" action="inc/zone-management/zone-option-add.php" method="post" id="addzoneform">
			  <div class="form-group">
			    <label for="zonefrom">Zone From:</label>
			    <select class="form-control required" id="zone_from" name="zone_from">
				    <option id="0" value="">Select</option>
				  	<?php
				  	/*
					*	@this query will select all record from zone table 
					*/
				  	$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
					if ( $all_zone_records->num_rows > 0 ) {
						while($all_zone_record = $all_zone_records->fetch_assoc()) {
							
							 echo "<option id=".$all_zone_record['zone_id']." value='".$all_zone_record['zone_id']."'>".$all_zone_record['zone_id']." - ".$all_zone_record['zone_name']."</option>";
						}	
					}
				  	?>
				  </select>
			  </div> 
			  <div class="form-group">
			    <label for="zoneto">Zone To:</label>
			   <select class="form-control required" id="zone_to" name="zone_to">
				    <option id="0" value="">Select</option>
				  	<?php
				  	/*
					*	@this query will select all record from zone table 
					*/
					$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
				  	if ( $all_zone_records->num_rows > 0 ) {
						
						while($all_zone_record = $all_zone_records->fetch_assoc()) {
							 echo "<option id=".$all_zone_record['zone_id']." value='".$all_zone_record['zone_id']."'>".$all_zone_record['zone_id']." - ".$all_zone_record['zone_name']."</option>";
						}	
					}
				  	?>
				  </select>
			  </div>
			  <div class="form-group">
			    <label for="addstorename">Regular Price:</label>
			    <input type="text" class="form-control required" id="price" name="price" value="">
			  </div>
			   <div class="form-group">
			    <label for="addstorename">Hot Price:</label>
			    <input type="text" class="form-control required" id="price_hot" name="price_hot" value="">
			  </div>
			   <div class="form-group">
			    <label for="addstorename">Urgent Price:</label>
			    <input type="text" class="form-control required" id="price_urgent" name="price_urgent" value="">
			  </div>
			   
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
	/*
	*	@this query will join record between zone table  and zone_detail table 
	*/
	$users_sql = "select zd.id,zd.zone_from,zd.zone_to,zd.price_regular,zd.price_hot,zd.price_urgent,zf.zone_name as from_zone_name ,zt.zone_name as to_zone_name from ".$zone_detail_table." as zd join zones as zf on zf.zone_id=zd.zone_from join zones as zt on zt.zone_id=zd.zone_to" ;
	$users_records = $conn->query($users_sql);
	?>
	<form name="zone_option_list" method="post" action="inc/zone-management/zone-option-update.php?info=delall">	
	<table class="" id="zoneoptionlist" style="width:100%">
		<thead>
			<tr>
				<th width="10px" style="background:none"><input type="checkbox" id="allzoneopt" /></th>
				<th width="14px">ID</th>
				<th>Zone From</th> 
				<th width="215px">Zone To</th>
				<th width="215px">Regular Price</th>
				<th width="215px">Hot Price</th>
				<th width="215px">Urgent Price</th>
				                        
				<th style="text-align: center;">Action</th>
			</tr>
		</thead>
		<tbody>				     
			<?php
			$no = 1;
			if ( $users_records->num_rows > 0 ) { ?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('#zoneoptionlist').dataTable({ 
							"aaSorting": [[ 0, "desc" ]],
						});
						jQuery('#allzoneopt').click(function(event) {  //on click
						if(this.checked) { // check select status
						    jQuery('.zoneoptid').each(function() { //loop through each checkbox
						        this.checked = true;  //select all checkboxes with class "checkbox1"              
						    });
						}else{
						    jQuery('.zoneoptid').each(function() { //loop through each checkbox
						        this.checked = false; //deselect all checkboxes with class "checkbox1"                      
						    });        
						}
						}); 
					});	
				</script>
				<?php
				 while($user_record = $users_records->fetch_assoc()) {
				$zone_opt_id=$user_record['id'];
				$zone_from=$user_record['zone_from']." - ".$user_record['from_zone_name'];
				$zone_to=$user_record['zone_to']." - ".$user_record['to_zone_name'];
				$price_regular=$user_record['price_regular'];
				$price_hot=$user_record['price_hot'];
				$price_urgent=$user_record['price_urgent'];
					?>
					<tr>
					<th><input class="zoneoptid" type="checkbox" name="zoneid[]" id="zoneid" value="<?php echo $zone_opt_id;?>" /></th>
						<td><?php echo $zone_opt_id; ?></td>
						<td nowrap>
						<?php /*$query = "Select * from zones where zone_name like '%".trim($zone_from)."%'";
						//echo $query;
						$records = $conn->query($query);
						$record = $records->fetch_assoc();*/
					    echo $zone_from; 	
						?>
						</td>
						<td nowrap style="width:240px;">
						<?php /*$query1 = "Select * from zones where zone_name like '%".trim($zone_to)."%'";
						$records1 = $conn->query($query1);
						$record1 = $records1->fetch_assoc();*/
						?>
						<?php echo $zone_to; ?>
						</td>
						<td nowrap><?php echo $price_regular; ?></td>
						<td nowrap><?php echo $price_hot; ?></td>
						<td nowrap><?php echo $price_urgent; ?></td>
						               
						
						<td style="text-align: center;">							
							<a onclick="javascript:return confirm('Are you sure, want to delete record ?')" href="inc/zone-management/zone-option-update.php?info=del&did=<?php echo $zone_opt_id;?>">
							<img src="images/delete.png" title="Delete" alt="Delete" />
							</a>
							&nbsp;&nbsp;&nbsp;
							<a href="inc/zone-management/zone-option-update.php?info=edit&eid=<?php echo $zone_opt_id;?>" class="siframe">
							<img src="images/edit.png" title="Edit" alt="Edit" />
							</a>
						</td>             
					</tr>
				<?php $no += 1;							
				}
				$conn->close();
			} else {
				echo '<h3>No Zone Options Found !!</h3>';
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
		 $("#addzoneform").validate();
	});
</script>