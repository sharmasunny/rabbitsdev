<?php
/**
* header.php
*/
include('constants.php');
session_start();
?>
<!DOCTYPE>
<html>
<head>
<meta http-equiv="Access-Control-Allow-Origin" content="*">
<meta charset="UTF-8">
<title>Rabbits Portal</title>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/style.css"/>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/bootstrap.css"/>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/jquery.dataTables.css"/>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/jquery-ui.css"/>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/fancybox/jquery.fancybox.css"/>
<link rel="stylesheet" href="<?php echo APP_URL; ?>/css/jquery-ui-timepicker-addon.css"/>
<script src="<?php echo APP_URL; ?>/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo APP_URL; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo APP_URL; ?>/js/jquery.dataTables.js"></script> 
<script src="<?php echo APP_URL; ?>/js/jquery-ui.js"></script>
<script src="<?php echo APP_URL; ?>/js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<script src="<?php echo APP_URL; ?>/js/fancybox/jquery.fancybox.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="<?php echo APP_URL; ?>/js/jquery-ui-timepicker-addon.js"></script>
</head>
<style>
h2#form_head {
  font-size: 20px;
}
label.error {
  font-weight:normal !important;
  top:-13px !important;

}
</style>
<body>
