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


$sql = "INSERT INTO ".$drivers_table."
 (driver_name,driver_mname,driver_lname,driver_email,
 driver_street,driver_street_2,driver_city,driver_state,driver_zip,
 driver_phone_no, password)
VALUES ('$name', '$mname','$lname','$email',
'$street', '$street_2','$city', '$state','$zip',
'$phone',
'".md5($password)."')";
// echo $sql;die;
if ($conn->query($sql) === TRUE) {
   // echo "New record created successfully";die;
    // echo HOME_URL.'/inc/authentication-check';die;
    
      echo "<script>window.location = '".HOME_URL."'</script>";

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
    <form role="form" class="form-inline" id="signup_form" method="post">
      <h2 id ="form_head">Signup Form</h2>
   
  
<div class="row">
   <div class="col-xs-3">
    <label for="driver_full_name">Full Name</label>
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_fname" placeholder="Enter your first name" id="adddriver_fname" class="required" aria-required="true">
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_mname" placeholder="Enter your middle name" id="adddriver_mname" class="required" aria-required="true">
  </div>
  <div class="col-xs-3">
    <input type="text" value="" name="driver_lname" placeholder="Enter your last name" id="adddriver_lname" class="required" aria-required="true">
  </div>
</div>
<div class="row">
   <div class="col-xs-3">
    <label for="exampleInputEmail1">Current Address</label>
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
    <input type="text" aria-required="true" class="required" id="adddriver_address" placeholder="Street address line 2" name="driver_street_2" value="">
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
       }
     },
     messages:{
      driver_email: {
           remote: "Email already exists"
         },
       driver_cpassword: {
          
          equalTo: "Please enter the same password as above"
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