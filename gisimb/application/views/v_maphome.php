<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Web Gis Kabupaten Karo</title>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <!-- Bootstrap -->
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">

  <!-- <link href="<?=base_url()?>assets/css/BootSideMenu.css" rel="stylesheet"> -->
  <link href="<?=base_url()?>assets/leaflet/leaflet.css" rel="stylesheet">
  <script src="<?=base_url()?>assets/leaflet/leaflet.js"></script>
  
<style type="text/css">
  	.user{
    		padding:5px;
    		margin-bottom: 5px;
		}
	#mapid { height: 480px; }
</style>
</head>
<body>



  <div class="container">

		<div class="row">
			<div class="col-md-12">
				<h1>Web Gis Kabupaten Karo</h1>
			<div>
		</div>

		<!-- <div class="row">
			<div class="col-md-12">
				<div id="mapid"></div>
			<div>
		</div> -->

		
  </div> 
  

  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
 

  <?php echo $map['html']; ?>
  <?php echo $map['js']; ?>

</body>
</html>