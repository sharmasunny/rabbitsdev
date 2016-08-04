<!DOCTYPE html>
<?php include('../../header.php');  ?>

	<link rel="stylesheet" href="css/style.css">
  
    <div class="login-page">
	  <div class="form">
		  <?php
		  // echo $_SESSION['User'];
		  
			include('../../constants.php');
			include('../../db.php');
			$drivers_table = "drivers";	
			
			if(isset($_POST['submit'])){
			
				$email = $_POST['driver_email'];
				$pwd = $_POST['driver_password'];
			
				if(!isset($email) && $email ==''){
					$error_msg = "Kindly enter email address";
				}else if(!isset($pwd) && $pwd ==''){
					$error_msg = "Kindly enter email address";
				}

				if((isset($email) && $email!='') && (isset($pwd) && $pwd!='')){
					$selected_driver_sql = "select * from ".$drivers_table." where driver_email = '".$email."' AND password = '".md5($pwd)."'";
					
					$selected_driver_records = $conn->query($selected_driver_sql);
					
					if ( $selected_driver_records->num_rows > 0 ) {
						while($selected_driver_record = $selected_driver_records->fetch_assoc()) {
							if($selected_driver_record['driver_status'] == '0'){
							echo "Your account is deactivate by admin</br>Please contact with admininstrator";	
							return false;
							}if(!empty($selected_driver_record['token_confirm'])){
							echo "Please confirm email first to login.";	
							return false;
							}

							$_SESSION['User'] = $selected_driver_record['driver_name'];
							$_SESSION['driver_id'] = $selected_driver_record['driver_id'];

							//$_SESSION['User']['email'] = $selected_driver_record['driver_email'];
							
						}
						echo "<script>window.location = '".HOME_URL."'</script>";
					}else{
						$error_msg = "Authentication failed, kindly use valid details";
					}
				}else{
					$error_msg = "Kindly enter required fields for login";
				}
			
			
			}
			
			
			
		  ?>
		  <?php if($error_msg !=''){ ?>
			<p style="color: rgb(255, 5, 29); padding: 5px 0px; font-size: 12px;" class="message"><?php echo $error_msg; ?> </p>

		  <?php }?>
		  
		 <!--<form role="form" action="inc/driver-management/driver-list-add.php" method="post" id="adddriverform">
		
		  <input type="email" class="form-control required" id="adddriveremail" name="adddriveremail" value="">
		
		
		  <input type="password" class="form-control required" id="adddriverpwd" name="adddriverpwd" value="">
		
		
		  <input type="text" placeholder="email address"/>
		
		  <button>create</button>
		
		  <p class="message">Already registered? <a href="#">Sign In</a></p>
		
		</form>-->
		
		<form id="login_form" method="post" class="login-form">
		<h2 id ="form_head">Login Form</h2>
        	        
			<input type="email" class="required" id="adddriveremail" placeholder="Enter your email" name="driver_email" value="">
		
		
			<input type="password" class="required" id="adddriverpwd" placeholder="Enter your password" name="driver_password" value="">
			
			<button name="submit" type="submit">login</button>
		  <p class="message">Not registered? <a href=<?php echo HOME_URL.'/inc/sign-up'; ?>>Sign up</a> | <a href=<?php echo HOME_URL.'/inc/sign-up/forget.php'; ?>>Forget Password</a></p>
		</form>
	  </div>
	</div>
	
	<script>
	jQuery(document).ready(function() {
		 $("#login_form").validate();
    });		 
</script>	
<style>
label.error {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
    border-radius: 0;
    color: #ff0000 !important;
    font-size: 11px;
    left: 0;
    margin: 0 !important;
    padding: 0;
    position: relative;
    float: left;
    top: -10px;
}
</style>
<?php include('../../footer.php'); ?>
