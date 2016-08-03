<!DOCTYPE html>
		  <?php
		  include('../../header.php');  ?>
		  <link rel="stylesheet" href="css/style.css">
  
    <div class="login-page">
	  <div class="form">
	  <?php
			include('../../constants.php');
			include('../../db.php');
			$drivers_table = "drivers";	
			$email = base64_decode($_REQUEST['driver-email']);
			 $token=$_REQUEST['token'];
			 // echo $email;die;
			if (!empty($email) && !empty($token))
{
	
    // $email = $_REQUEST['driver_email'];
    $query = "SELECT * FROM $drivers_table WHERE driver_email = '$email' and token_reset = '$token'";
    // echo $query;die;
    $result = $conn->query($query);
    // echo $result->num_rows;die;
	
	
if($result->num_rows == 1)
    { ?>
        <form id="reset_form" method="post" class="login-form">
		<h2 id ="form_head">Reset Password</h2>
        	        
		
			<input type="password" class="required" id="adddriverpwd" placeholder="Enter your password" name="driver_password" value="">
			<input type="password" class="required" id="adddrivercpwd" placeholder="Enter your password again" name="driver_cpassword" value="">
			
			
			<button name="submit" type="submit">Reset Password</button>
		  
		</form>
		  </div>
	</div>
   <?php }
    else
    {
        echo "Token has been expired"; //already registered
    }
}
else
{
	echo "Forbidden";
}

if(isset($_POST['submit'])){

$password=$_POST['driver_password'];

$sql = "UPDATE ".$drivers_table." SET token_reset='',password='".md5($password)."' WHERE driver_email='" .$email."'" ;

// echo $sql;die;
if ($conn->query($sql) === TRUE) {
//	echo "New record created successfully";die;
// echo HOME_URL.'/inc/authentication-check';die;

echo "<script>window.location = '".HOME_URL."'</script>";

} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();


}
?>
		  
		 
		<script>
	jQuery(document).ready(function() {
		 $("#reset_form").validate({
		 	 rules: {
		 	 	
  
    driver_cpassword: {
    		equalTo: "#adddriverpwd"
		 	 }
		 },
		messages: {
		 	 driver_cpassword: {
					
					equalTo: "Please enter the same password as above"
				}
		 }
		 }
		 	);
	
    });		 
</script>	
		
		
