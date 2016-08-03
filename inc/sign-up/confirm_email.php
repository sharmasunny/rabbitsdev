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
			 $confirm_token=$_REQUEST['confirm_token'];
			 // echo $email;die;
			if (!empty($email) && !empty($confirm_token))
{
	
    // $email = $_REQUEST['driver_email'];
    $query = "SELECT * FROM $drivers_table WHERE driver_email = '$email' ";
    // echo $query;die;
    $result = $conn->query($query);
    // echo $result->num_rows;

// echo 'g'.$token_c;
$login_url= HOME_URL.'/inc/authentication-check/';
	
if($result->num_rows == 1)
    { 
	while($res = $result->fetch_assoc()) {

		// print_r($res);
$token_c=$res['token_confirm'];
}
if(empty($token_c))
{
echo '<p>Your email is already confirmed</p>
<p>Please visit the following link to login: <a href="'.$login_url.'">Login</a></p>
<p>If you have any questions please contact us at <a href="mailto:info@shopifystorefinder.com">info@shopifystorefinder.com</a></p>';
return false;
}

$sql = "UPDATE ".$drivers_table." SET token_confirm='' WHERE driver_email='" .$email."' AND  token_confirm = '$confirm_token'" ;

// echo $sql;die;
if ($conn->query($sql) === TRUE) {
	
//	echo "New record created successfully";die;
// echo HOME_URL.'/inc/authentication-check';die;




	echo '<p>Your email is confirmed</p>
<p>Please visit the following link to login: <a href="'.$login_url.'">Login</a></p>
<p>If you have any questions please contact us at <a href="mailto:info@shopifystorefinder.com">info@shopifystorefinder.com</a></p>';

// echo "<script>window.location = '".HOME_URL."'</script>";

} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

    	?>

        
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


?>
	
</html>