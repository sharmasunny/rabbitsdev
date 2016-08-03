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


$sql = "UPDATE ".$drivers_table." SET token_reset='".base64_encode(mt_rand()*100)."' WHERE driver_email='" .$email."'" ;
 if ($conn->query($sql) === TRUE) {

$sql1= "SELECT token_reset FROM ".$drivers_table." WHERE driver_email='" .$email."'" ;
// echo $sql1;die;
$stmt = $conn->query($sql1);

if ( $stmt->num_rows > 0) {
while($stm = $stmt->fetch_assoc()) {
$token=$stm['token_reset'];
}
}
					
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// $headers = "From: abc@mail.com";
// $message= "Hey request for password reset here  <b> hey </b>";
$reset_url= HOME_URL.'/inc/sign-up/reset.php?driver-email=' .base64_encode($email).'&token='.$token;
						
//$message = '<html><body>';
//$message .= '<h1 style="color:#f40;">Hi User!</h1>';
//$message .= '<p style="color:#080;font-size:18px;">Click here to password Reset</p>';
//$message .= '<a href="'.$reset_url.'">Reset Password</a>';
//$message .= '</body></html>';

$message ='<p><span style="font-family: Arial,Helvetica,sans-serif;"> </span></p>
<table style="border-collapse: collapse; border-spacing: 0; border: 1px solid #a6ce45; background-color: #fff; padding: 0;" border="0" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr style="height: 36px;" height="36">
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="middle"><span style="background-color: #a6ce45;"> </span></td>
<td style="width: 500px; background-color: #a6ce45;" width="500" valign="middle"><span style="background-color: #a6ce45;"><span style="color: white; font-size: small;"><strong> Shopify Store Finder - Forgot Password</strong></span></span><span style="color: #ffffff; font-size: small;"><strong> </strong></span><br /></td>
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="middle"><span style="background-color: #a6ce45;"> </span></td>
</tr>
<tr>
<td style="height: 20px;" colspan="3" valign="top">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 30px;" width="30" valign="top"><br /></td>
<td style="width: 500px;" width="500" valign="top"><span style="font-family: Arial,Helvetica,sans-serif;"> 
<table style="border-collapse: collapse; border-spacing: 0; padding: 0;" border="0" cellspacing="0" cellpadding="0" width="499" height="108" align="left">
<tbody>
<tr>
<td valign="top"></td>
</tr>
<tr>
<td valign="top"><br /></td>
</tr>
<tr>
<td valign="top">Hi,</td>
</tr>
<tr>
<td style="height: 25px;" valign="top"><br /></td>
</tr>
<tr>
<td valign="top">
<p>You have requested to reset your password for: '.$email .' on Shopify Store Finder.</p>
<p>Please visit the following link to reset your password: <a href="'.$reset_url.'">Reset Password</a></p>
<p>If you have any questions please contact us at <a href="mailto:info@shopifystorefinder.com">info@shopifystorefinder.com</a></p>
</td>
</tr>
</tbody>
</table>
</span></td>
<td style="width: 30px;" width="30" valign="top"><br /></td>
</tr>
<tr>
<td style="height: 20px;" colspan="3" valign="top"><br /></td>
</tr>
<tr>
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="top"><br /></td>
<td style="background-color: #a6ce45; padding: 15px 0;" valign="top"><span style="background-color: #a6ce45;"><span style="font-family: Arial,Helvetica,sans-serif;"> 
<table style="border-collapse: collapse; border-spacing: 0; text-align: justify; padding: 0;" border="0" cellspacing="0" cellpadding="0" width="500" height="128" align="left">
<tbody>
<tr>
<td style="width: 500px; height: 20px;" width="500" valign="top">
<p style="font-weight: bold;">Regards,<br /><strong> </strong></p>
<p style="font-weight: bold;">Team Shopify Store Finder</p>
</td>
</tr>
<tr>
<td>
<p><span style="font-family: Arial; font-size: xx-small;">Notice:  The information in this email and in any  attachments is confidential  and intended solely for the attention and use of the  named addressee.  This information may be subject to legal professional or other   privilege or may otherwise be protected by work product immunity or  other legal  rules. It must not be disclosed to any person without  authorization. If you are  not the intended recipient, or a person  responsible for delivering it to the  intended recipient, you are not  authorized to and must not disclose, copy,  distribute, or retain this  message or any part of  it.</span></p>
</td>
</tr>
</tbody>
</table>
</span> </span></td>
<td style="width: 30px; background-color: #a6ce45;" width="30" valign="top"><span style="background-color: #a6ce45;"> </span></td>
</tr>
</tbody>
</table>
<p><span style="font-family: Arial,Helvetica,sans-serif;"> </span></p>
';
// echo $message;die;
						/*<a href= " .HOME_URL.'/inc/sign-up/reset.php?driver-email=' .base64_encode($email).'&token=123'"> </br>Click Here to Reset Password</a>

					";*/
					// echo $email.$message.$headers;die;
					if(mail($email,'Password Reset',$message,$headers))
					{
					echo "Request for reset password is mailed you.check your inbox and follow instructions";
					echo "<a href= " .HOME_URL.'/inc/authentication-check/' ."> </br>Click Here to Login</a>";
					die;
					}
					else
					{
						echo 'error while sending email';
						// echo "<script>window.location = '".HOME_URL."'</script>";
					}
 	
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
		
		<form id="pwd_reset_form" method="post" class="login-form">
		<h2 id ="form_head">Password Reset</h2>
        	        
			<input type="email" class="required" id="adddriveremail" placeholder="Enter your email" name="driver_email" value="">
		
		
			
			<button name="submit" type="submit">Submit</button>
			 <p class="message"><a href=<?php echo HOME_URL.'/inc/sign-up'; ?>>Create an account</a> | <a href=<?php echo HOME_URL.'/inc/authentication-check/'; ?>>Log in</a></p>
		</form>
	  </div>
	</div>
	
	<script>
	jQuery(document).ready(function() {
		 $("#pwd_reset_form").validate({
		 	 rules: {
		 	 	
    driver_email: {
           remote: "check_email_for_reset.php"    
		 	 }, 	 	

		 },
		 messages:{
		 	driver_email: {
           remote: "Email Doesn't Exists"
    	 	 }
		 	
		 }
		 }
		 	);
	
    });	
/*	jQuery(document).ready(function() {
		 $("#pwd_reset_form").validate();
    });	*/	 
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
