<?php 
    // call config file
    require_once('app/config/config.php'); 

    // call your class files
    require_once('app/models/User.php'); 

    // callyour auth files
    require_once('app/auth/userAuth.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payperlez</title>
  <meta name="description" content="">
  <meta name="author" content="Payperlez" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="icon" href="public/img/payperlez.jpg" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="public/css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="public/css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link rel="stylesheet" media="screen" href="public/css/style.css">
</head>
 