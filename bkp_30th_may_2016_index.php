<?php include('header.php'); ?>
  
<div class="col-sm-12 col-md-12 main-div">
	 <ul class="nav nav-tabs">
	    <li class="active"><a data-toggle="tab" href="#zones-management">Zones Management</a></li>
	    <li><a data-toggle="tab" href="#stores-management">Stores Management</a></li>
	    <li><a data-toggle="tab" href="#collections-management">Collections Management</a></li>
	    <li><a data-toggle="tab" href="#products-management">Products Management</a></li>
	    <li><a data-toggle="tab" href="#drivers-management">Drivers Management</a></li>
	    <li><a data-toggle="tab" href="#orders-management">Orders Management</a></li>
		<li><a data-toggle="tab" href="#zone-option-management">Zone Option Management</a></li>
	  </ul>
	  <div class="tab-content app-settings-tabs">
		<div id="drivers-management" class="tab-pane fade in">
			<?php include('inc/driver-management/driver-list.php'); ?>
		</div>
		<div id="zones-management" class="tab-pane fade in active">
			<?php include('inc/zone-management/zone-list.php'); ?>
		</div>
		<div id="stores-management" class="tab-pane fade in">
			<?php include('inc/store-management/store-list.php'); ?>
		</div>
		<div id="collections-management" class="tab-pane fade in">
			<?php include('inc/collections-management/collection-list.php'); ?>
		</div>
		<div id="products-management" class="tab-pane fade in">
			<?php include('inc/product-management/product-list.php'); ?>
		</div>
		<div id="orders-management" class="tab-pane fade in">
			<?php include('inc/order-management/order-list.php'); ?>
		</div>
		<div id="zone-option-management" class="tab-pane fade in">
			<?php require('inc/zone-management/zone-option-list.php'); ?>
		</div>
	  </div>		
</div>
<?php include('footer.php'); ?>