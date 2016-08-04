<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>

  #logout_btn
  {
  	margin-top: 16px !important;
  	float:right !important;
  }
  table#userlist
  {
  	
  	 border: 1px solid #d3d3d3;
  }
  </style>

  <script>
	jQuery(document).ready(function() {
		jQuery("a.iframe").fancybox({
			maxWidth	: 2000,
			maxHeight	: 500,
			width		: '60%',
			height		: '20%',
			autoSize	: true,
			type		: 'iframe',
		});
	});
		function logout(){
		 jQuery.ajax({
   		 url:"inc/driver-management/logout.php",  
        success: function () {     
        window.location= 'localhost:8080/rabbitsdev/'   
        }
      });
	

}

</script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>

<?php

include('db.php');
include('../header.php');

$orders_table = "orders";	
$drivers_table = "drivers";
$driver_id =$_SESSION['driver_id'];


$status_disabled = 0;
function set_selected($desired_value, $new_value)
{

    if($desired_value==$new_value)
    {
	
	echo ' selected="selected"';
    }

   // if($status_disabled==0){
	//echo ' disabled="disabled"';	
    //}
	
}

// echo $driver_id;

/* 
*	@this query will select all record from orders table
*/
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

?>
<nav role="navigation" class="navbar navbar-default">
			<!-- .container-fluid -->
		</nav>
<div class="container-fluid"> 
	<div class="row">
  
<div class="col-xs-6 col-sm-6 col-md-10 col-lg-10">
 <h2>Orders Listing</h2>
  </div> 
  <div class="col-xs-6 col-sm-6 col-md-2  col-lg-2 "><button class="btn btn-danger btn-lg" id="logout_btn" onclick="logout()" type="button" style="margin-top: 14px; float: right;">Logout</button></div>
</div>
	
	<br/>
	<br/>
	<!-- <form name="orders_list" method="post" action="inc/driver-management/driver-list-update.php?info=delall">	 -->
	<div class="table-responsive">          
     
	<table class="table table-bordered" id="userlist" style="width:100%">
		<thead>
			<tr>
				<th width="100px">Order Id</th>       
				<th width="350px">Pickup zone</th>         
				<th width="350px">Drop zone</th>         
				<th width="100px;">Action</th>                                                          
			</tr>
		</thead>
		<tbody>				     
			<?php
			$no = 1;
			if ( $users_records->num_rows > 0 ) { ?>
				<script type="text/javascript">


        

					jQuery(document).ready(function(){
				
					   var table = jQuery('#userlist').dataTable({
					    	ajax : "inc/orders/data_orders.php",
					    	"columns": [
					            { "data": "order_id" },
					            { "data": "pickup_zone" },					  
					            { "data": "drop_zone" },
					            { "data": "action"},
					          
					        ],

					    	"aaSorting": [[ 0, "desc" ]], 

					    });
					   	setInterval( function () {
 				  			table.api().ajax.reload(null,false);
						}, 5000 );  
						 $(document).ajaxComplete(function() {
                 $(".order_stat").change(function() {
			$( "#set-status" ).submit();
		});
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
				 while($users_record = $users_records->fetch_assoc()) {

				 	// print_r($users_record);die;
				 	// print_r(unserialize($users_recrd->driver_zone_id));die;

					$id        		  = $users_record['order_id'];
					$driver_zones    	= $users_record['driver_zone_id'];
					$order_number	= $users_record['order_number'];
					$order_price 	  = $users_record['order_price'];
					$customer_email 	  = $users_record['customer_email'];
					$order_shipping_address 	  = $users_record['order_shipping_address'];

					$selected_value = $users_record['order_status'];
					$confirm_value = $users_record['confirmation'];
					$source = json_decode($users_record['all_record'])->line_items['0']->origin_location; 
					$destination = json_decode($users_record['all_record'])->line_items['0']->destination_location; 
// echo $driver_zones;die;
					// print_r($users_record);die;
					?>
				
					<tr>
					 <!-- <td>
					
					</td> -->
						<td><?php echo "#".$id; ?></td>
						<td nowrap>
						<?php 
						if(empty($source->zip))
						{
							echo "No Postal Code";
						}
						else{
							//echo $source->name.', '.$source->address1.', '.$source->address2.',</br> '.$source->city.', '.$source->zip;
							?>
						<button type="button" class="btn btn-link  btn-lg pickup-zone-model"   value ="<?php echo $source->zip; ?>" ><?php echo $source->zip; ?> </button>

						
						
						<?php }
						?>	
						</td>
						<td nowrap>
						<?php 
							if(empty($destination->zip))
						{
							echo "No Postal Code";
						}
						else{ 
							//echo $destination->name.', '.$destination->address1.', '.$destination->address2.',</br> '.$destination->city.', '.$destination->zip;
							//echo $destination->zip;?>
							<button type="button" class="btn btn-link btn-lg dropoff-zone-model"   value ="<?php echo $destination->zip; ?>"> <?php echo $destination->zip; ?> </button>


					<?php }
						?>	
						</td>
						<!-- <td nowrap><?php echo $order_shipping_address; ?></td> -->
						
						<td nowrap> 
							<?php if($confirm_value!=2){ ?>
								<form action="inc/orders/confirm_stat.php" method="post" accept-charset="utf-8">
									<input type="submit" name="confirm_stat_submit" class="btn btn-success" value="Accept"/> 
									<input type="submit" name="confirm_stat_submit" class="btn btn-danger" value="Reject"/> 
									<input type="hidden" name="order_id" value="<?php echo $id; ?>" style="display:none;">
								</form>
							<?php 
							}else{ 
								if($selected_value=='4'){
									echo "FULFILLED";
								}else{
							 		?>

										<form id="set-status" action="inc/orders/set_status.php" method="post" accept-charset="utf-8">
											<input type="hidden" name="order_number" value="<?php echo $order_number; ?>"/>
											<input type="hidden" name="customer_email" value="<?php echo $customer_email; ?>"/>
											<input type="hidden" name="order_id" value="<?php echo $id; ?>" style="display:none;">
											<select name="order_status" class="order_stat">
												<option value="select" required>Please select status</option>
												<option value="1" <?php if($selected_value=='1'){echo "selected";} ?> >Picked UP</option>
												<option value="2" <?php if($selected_value=='2'){echo "selected";} ?> >On the way</option>
												<option value="3" <?php if($selected_value=='3'){echo "selected";}  ?> >Deliverd</option>
												<option value="4" <?php if($selected_value=='4'){echo "selected";}  ?> >FULFILLED</option>
											</select>
											<!--input type="submit" name="submit" class="btn btn-success" value="Update"/--> 
										</form>

							<?php }	} ?> 
						</td>
					</tr>
				<?php $no += 1;							
				}
				$conn->close();
				
			} else {
				echo '<h3>No Orders Details Found !!</h3>';
			} ?>					
		</tbody>
	</table>
	  </div>
	<!-- <p>
		<input type="submit" name="delete" class="add-new-h2 button-secondary" onclick="javascript:return confirm('Are you sure, want to delete all checked record?')" value="Delete">
	</p> -->
	
</div>







<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      Modal content
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
		  <iframe id="model-iframe" width="550" height="500" frameBorder="0" src="">
		  </iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



<script>

	jQuery(document).ready(function() {
		$(document).on("click",".pickup-zone-model",function() {
        	$(".modal-title").html('Pick up location');
			$('#model-iframe').attr('src', 'https://www.google.com/maps/embed/v1/place?q='+$(this).val()+'&zoom=17&key=AIzaSyATRApu-iBQ61A76EeNR4NokqFMKM71IXw');
			$('#myModal').modal('show');
    	});
	
		

		$(document).on("click",".dropoff-zone-model",function() {
			$(".modal-title").html('Drop off location');
			$('#model-iframe').attr('src', 'https://www.google.com/maps/embed/v1/place?q='+$(this).val()+'&zoom=17&key=AIzaSyATRApu-iBQ61A76EeNR4NokqFMKM71IXw')
			$('#myModal').modal('show');
		});

		 $("#adddriverform").validate();
		 var j =$(".order_stat").val();
		 //console.log(j);
		if(j>=1){
			for(i=j;i>=1;i--){
				$(".order_stat option[value="+i+"]").prop('disabled', true);
				$(".order_stat option[value="+i+"]").css('color','#999');		
			}
		}
		
		$(".order_stat").change(function() {
			$( "#set-status" ).submit();
		});
		
	});
	
</script>
	
</body>
</html>
