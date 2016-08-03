<!DOCTYPE html>
<?php 

include('../../header.php');  


  if(isset($_SESSION['User']) && $_SESSION['User'] !=''){
    echo "<script>window.location = '".HOME_URL."'</script>";
  }
?>

  <link rel="stylesheet" href="css/style.css">
  
    
      <?php
      
      include('../../constants.php');
      include('../../db.php');
      $drivers_table = "drivers"; 
   

      if(isset($_POST['submit']))
{
  // echo "h";die;
$name=$_POST['driver_fname'];
$mname=$_POST['driver_mname'];
$lname=$_POST['driver_lname'];
$email=$_POST['driver_email'];

$street=$_POST['driver_street'];
$street_2=$_POST['driver_street_2'];
$city=$_POST['driver_city'];
$state=$_POST['driver_state'];
$zip=$_POST['driver_zip'];

// $std=$_POST['driver_std'];
$phone=$_POST['driver_std'].$_POST['driver_number'];
$password=$_POST['driver_password'];


$confirm_token=base64_encode(mt_rand()*100);
$sql = "INSERT INTO ".$drivers_table."
 (driver_name,driver_mname,driver_lname,driver_email,
 driver_street,driver_street_2,driver_city,driver_state,driver_zip,
 driver_phone_no, token_confirm, password)
VALUES ('$name', '$mname','$lname','$email',
'$street', '$street_2','$city', '$state','$zip',
'$phone','$confirm_token',
'".md5($password)."')";
// echo $sql;die;
if ($conn->query($sql) === TRUE) {
   // echo "New record created successfully";die;
    // echo HOME_URL.'/inc/authentication-check';die;
  $headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// $headers = "From: abc@mail.com";
// $message= "Hey request for password reset here  <b> hey </b>";
// $login_url= HOME_URL.'/inc/authentication-check/';
  
    $confirmation_url= HOME_URL.'/inc/sign-up/confirm_email.php?driver-email=' .base64_encode($email).'&confirm_token='.$confirm_token;     
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
<td style="width: 500px; background-color: #a6ce45;" width="500" valign="middle"><span style="background-color: #a6ce45;"><span style="color: white; font-size: small;"><strong> Shopify Store Finder - Thank you For Joining</strong></span></span><span style="color: #ffffff; font-size: small;"><strong> </strong></span><br /></td>
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
<td valign="top">Hi, '.$name .' '.$mname .' '.$lname .'</td>
</tr>
<tr>
<td style="height: 25px;" valign="top"><br /></td>
</tr>
<tr>
<td valign="top">
<p>Thank you for joining with us <br />

 </p>
<p>Please visit the following link to confirm: <a href="'.$confirmation_url.'">Confirm</a></p>
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

    if(mail($email,'Signup Confirmation',$message,$headers))
          {
           // echo $message;die; 
            // echo "Please confirm email";return false;
          echo "<script>window.location = '".HOME_URL."'</script>";
         
          }
          else
          {
            echo 'error while sending email';
            // echo "<script>window.location = '".HOME_URL."'</script>";
          }
    
      

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}
      
      
      
      ?>
      <?php if($error_msg !=''){ ?>
      <p style="color: rgb(255, 5, 29); padding: 5px 0px; font-size: 12px;" class="message"><?php echo $error_msg; ?> </p>
      <?php }?>
      
     <div class="login-page">
 
    <div class="form">
     <h2 >Signup Form</h2>
      <hr>
    <form role="form" class="form-inline" id="signup_form" method="post">
     
     
   
  
<div class="row">
   <div class="col-xs-3">
    <label for="driver_full_name">Full Name</label>
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_fname" placeholder="Enter your first name" id="adddriver_fname" class="required" aria-required="true">
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_mname" placeholder="Enter your middle name" id="adddriver_mname">
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_lname" placeholder="Enter your last name" id="adddriver_lname" class="required" aria-required="true">
  </div>
</div>
<div class="row">
   <div class="col-xs-3">
    <label for="driver_current_address">Current Address</label>
  </div>
  <div class="col-xs-6">
    <input type="text" aria-required="true" class="required" id="adddriver_address" placeholder="Street address" name="driver_street" value="">
  </div>
  <div class="col-xs-3">
    
  </div>
  <div class="col-xs-3">
    
  </div>
</div>
<div class="row">
   <div class="col-xs-3">
   
  </div>
  <div class="col-xs-6">
    <input type="text" id="adddriver_address_2" placeholder="Street address line 2" name="driver_street_2" value="">
  </div>
  <div class="col-xs-3">
    
  </div>
  <div class="col-xs-3">
    
  </div>
</div>
<div class="row">
   <div class="col-xs-3">
  </div>
  <div class="col-xs-3">
    <input type="text" aria-required="true" class="required" id="adddriver_city" placeholder="City" name="driver_city" value="">
  </div>
  <div class="col-xs-3">
    <input type="text" aria-required="true" class="required" id="adddriver_state" placeholder="State" name="driver_state" value="">
  </div>
  <div class="col-xs-3">
    
  </div>
</div>
<div class="row">
   <div class="col-xs-3">
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_zip" placeholder="Postal/zip code" id="adddriver_zip" class="required" aria-required="true">
  </div>
  <div class="col-xs-3">
        <!-- <div class="dropdown">
    <button class="dropdown-toggle" type="button" data-toggle="dropdown" name="driver_country"> Country
    <span class="caret" style="background:none"></span></button>
    <ul class="dropdown-menu">
      <li><a href="#">HTML</a></li>
      <li><a href="#">CSS</a></li>
      <li><a href="#">JavaScript</a></li>
    </ul>
  </div> -->
  </div>
  <div class="col-xs-3">

  </div>
</div>

<div class="row">
   <div class="col-xs-3">
     <label for="exampleInputEmail1">Phone number</label>
  </div>
  <div class="col-xs-2">
    <input type="text" value="" name="driver_std" placeholder="STD" id="adddriver_std" class="required" aria-required="true">
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_number" placeholder="Enter your number" id="adddriver_number" class="required" aria-required="true">
  </div>
  <div class="col-xs-3">
    
  </div>
</div>
<div class="row">
   <div class="col-xs-3">
     <label for="driver_email">Email </label>
  </div>
  <div class="col-xs-5">
    <input type="email" value="" name="driver_email" placeholder="Enter your email" id="adddriveremail" class="required" aria-required="true">
    
  </div>
  
</div>
        
       <div class="row">
   <div class="col-xs-3">
     <label for="driver_password">Password</label>
  </div>
  <div class="col-xs-4">
    <input type="password" class="required" id="adddriverpwd" placeholder="Enter your password" name="driver_password" value="">
      
      
      
  </div> 
   <div class="col-xs-4">
    <input type="password" class="required" id="adddrivercpwd" placeholder="Enter your password again" name="driver_cpassword" value="">
      
     
  </div>
  
</div> 

<div class="row">
   <div class="col-xs-3">
     
  </div>
  <div class="col-xs-4">
  <button name="submit" type="submit">Submit</button>
   <!--  <button class="btn btn-primary" name="submit" type="submit">Submit</button> -->
    <p class="message">Already registered? <a href=<?php echo HOME_URL.'/inc/authentication-check'; ?>>Log in</a></p>
  </div>
  
</div>
    </form>
    </div>
  </div>

  
  
  <script>
  jQuery(document).ready(function() {
     $("#signup_form").validate({
       rules: {
        
    driver_email: {
           remote: "check_email.php"    
       },     
    driver_cpassword: {
        equalTo: "#adddriverpwd"
       },
       driver_std: {
       number:true
       },
       driver_number: {
       number:true
       }
     },
     messages:{
      driver_email: {
           remote: "Email already exists"
         },
       driver_cpassword: {
          
          equalTo: "Please enter the same password as above"
        },
         driver_std: {
          
          number: "invalid value"
        },   driver_zip: {
          
          number: "invalid value"
        }, 
        driver_number: {
          
          number: "invalid value"
        }
     }
     }
      );
  
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
.form
{
  max-width: 1060px;
}
.login-page
{
  width: 1060px;
}
</style>
<?php include('../../footer.php'); ?>