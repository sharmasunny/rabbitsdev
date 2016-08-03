 <?php
include('db.php');
$zones_table="zones";
$zone_detail_table = "medium_zone_detail";	
$users_sql = "select * from ".$zone_detail_table;
$users_records = $conn->query($users_sql);
/*
*	@this query will selct all record from zone table 
*/
$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
$zone_list=array();
 while($user_record = $all_zone_records->fetch_assoc()) {
	 $zone_list[$user_record['zone_id']]=$user_record['zone_name'];
 }
 
?>
<div class="wrap"> 
	<h2>Medium Size Delivery Price List</h2>
	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#add-medium-option">Add New Zone Option</button>
	<!-- Modal -->
	<div id="add-medium-option" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Add New Zone</h4>
	      </div>
	      <div class="modal-body">
	        <form role="form" action="inc/zone-management/medium-option-add.php" method="post" id="mediumaddzoneform">
			  <div class="form-group">
			    <label for="zonefrom">Zone From:</label>
			    <select class="form-control required" id="zone_from" name="zone_from">
				    <option id="0" value="">Select</option>
				  	<?php
				  	/*
					*	@this query will selct all record from zone table 
					*/
				  	$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
					if ( $all_zone_records->num_rows > 0 ) {
						while($all_zone_record = $all_zone_records->fetch_assoc()) {
							
							 echo "<option id=".$all_zone_record['zone_id']." value='".$all_zone_record['zone_id']."'>".$all_zone_record['zone_name']."</option>";
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
					*	@this query will selct all record from zone table 
					*/
					$all_zones_sql 		  = "select * from ".$zones_table." where zone_is_deleted='0' ";
					$all_zone_records 	  = $conn->query($all_zones_sql);
				  	if ( $all_zone_records->num_rows > 0 ) {
						
						while($all_zone_record = $all_zone_records->fetch_assoc()) {
							 echo "<option id=".$all_zone_record['zone_id']." value='".$all_zone_record['zone_id']."'>".$all_zone_record['zone_name']."</option>";
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
	*	@this query will selct all record from zone details table 
	*/
	$users_sql = "select * from ".$zone_detail_table;
	$users_records = $conn->query($users_sql);
	?>
	<form name="medium_option_list" method="post" action="inc/zone-management/medium-option-update.php?info=delall">	
	<table class="" id="mediumzoneoptionlist" style="width:100%">
		<thead>
			<tr>
				<th width="10px" style="background:none"><input type="checkbox" id="mediumallzoneopt" /></th>
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
						jQuery('#mediumzoneoptionlist').dataTable({ 
							"aaSorting": [[ 0, "desc" ]],
						});
						jQuery('#mediumallzoneopt').click(function(event) {  //on click
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
				$zone_from=$user_record['zone_from'];
				$zone_to=$user_record['zone_to'];
				$price_regular=$user_record['price_regular'];
				$price_hot=$user_record['price_hot'];
				$price_urgent=$user_record['price_urgent'];
					?>
					<tr>
					<th><input class="zoneoptid" type="checkbox" name="zoneid[]" id="zoneid" value="<?php echo $zone_opt_id;?>" /></th>
						<td><?php echo $zone_opt_id; ?></td>
						<td nowrap><?php echo $zone_from; ?></td>
						<td nowrap style="width:240px;"><?php echo $zone_to; ?></td>
						<td nowrap><?php echo $price_regular; ?></td>
						<td nowrap><?php echo $price_hot; ?></td>
						<td nowrap><?php echo $price_urgent; ?></td>
						               
						
						<td style="text-align: center;">							
							<a onclick="javascript:return confirm('Are you sure, want to delete record ?')" href="inc/zone-management/medium-option-update.php?info=del&did=<?php echo $zone_opt_id;?>">
							<img src="images/delete.png" title="Delete" alt="Delete" />
							</a>
							&nbsp;&nbsp;&nbsp;
							<a href="inc/zone-management/medium-option-update.php?info=edit&eid=<?php echo $zone_opt_id;?>" class="siframe">
							<img src="images/edit.png" title="Edit" alt="Edit" />
							</a>
						</td>             
					</tr>
				<?php $no += 1;							
				}
				$conn->close();
			} else {
				echo '<h3>No Records Found !!</h3>';
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
		 $("#mediumaddzoneform").validate();
	});
</script>