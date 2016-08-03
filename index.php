<?php 
include('header.php'); 

if(isset($_SESSION['User']) && $_SESSION['User'] !=''){
	include('inc/orders/index.php');
	// include('inc/order-management/order-list.php');
	// include('inc/driver-management/driver-list.php');
}
else{
	echo "<script>window.location = '".HOME_URL."/inc/authentication-check'</script>";
}
 
include('footer.php');

 ?>
