<?php
include('../../constants.php');
include('../../db.php');
$drivers_table = "drivers";	
			

		
if (!empty($_REQUEST['driver_email']))
{
	
    $email = $_REQUEST['driver_email'];
    $query = "SELECT * FROM $drivers_table WHERE driver_email = '$email'";
    $result = $conn->query($query);
    // echo $result->num_rows;die;
	
	if($result->num_rows == 0)
    {
        echo "false";  //good to register
    }
    else
    {
        echo "true"; //already registered
    }
}
else
{
    echo "false"; //invalid post var
}
?>

			
		
		  

		
		