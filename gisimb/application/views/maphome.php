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
  [<script src="<?=base_url()?>assets/leaflet/leaflet.js"></script>]
  
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

		<div class="row">
			<div class="col-md-12">
				<div id="mapid"></div>
			<div>
		</div>

		
  </div> 
  <!--Normale contenuto di pagina-->

  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  <!-- <script src="<?=base_url()?>assets/js/BootSideMenu.js"></script> -->

  <script type="text/javascript">
    // $('#test').BootSideMenu({side:"left", autoClose:false});
	
	var map = L.map('mapid').setView([3.1049681,98.4937721], 13);
	var base_url="<?=base_url()?>";

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

// BUAT defenisi feature layer
	var myFeatureGroup = L.featureGroup().addTo(map).on("click", groupClick)
	var bangunanMarker



// AJAX untuk baca data dari database
	$.getJSON("<?=base_url()?>index.php/home/bangunan_json", function(data){
      $.each(data, function(i, field){
		//alert(data[i].bangunan_nama);
		var v_lat=parseFloat(data[i].bangunan_lat);
		var v_long=parseFloat(data[i].bangunan_long);

//SETING Marker		
		var icon_bangunan = L.icon({
			iconUrl: base_url+'assets/images/home.svg',
			iconSize: [30,30]
		});

	bangunanMarker=L.marker([v_long, v_lat], {icon:icon_bangunan})
					.addTo(myFeatureGroup)
					.bindPopup(data[i].bangunan_nama);	
	bangunanMarker.id = data[i].bangunan_id;

	// bangunanMarker=L.marker([v_long,v_lat], {icon:icon_bangunan})
	// 				.addTo(map)
    // 				.bindPopup(data[i].bangunan_nama)
    // 				.openPopup();


    });
  });

  	function groupClick(event){
		  console.log("clicked on marker" + event.layer.id);
		  //alert("clicked on marker" + event.layer.id)
	  }

	var popup = L.popup();

	function onMapClick(e) {
    popup
        .setLatLng(e.latlng)
        .setContent("Klik <a href=#>Register</a> <br>" + e.latlng.toString())
        .openOn(map);
	}

	map.on('click', onMapClick);

	// L.marker([3.1049681,98.4937721]).addTo(map)
    // .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
    // .openPopup();
//MENAMBAHKAN Layer poligon geojson

	$.getJSON(base_url+"assets/geojson/lahan_pemda.geojson", function(data){
		geoLayer = L.geoJson(data, {
			style: function(feature){
				return {
					fillOpacity: 0.8,
					weight: 1,
					opacity: 1,
					color: "#f08eff"
				};
			},

			onEachFeature: function(feature, layer){
				var latt =  parseFloat(feature.properties.latitude);
			}
		}).addTo(map);
	});

	$.getJSON(base_url+"assets/geojson/karo_administratif.geojson", function(data){
		geoLayer = L.geoJson(data, {
			style: function(feature){
				return {
					fillOpacity: 0.4,
					weight: 1,
					opacity: 1,
					color: "#f08eff"
				};
			},

			onEachFeature: function(feature, layer){
				var latt =  parseFloat(feature.properties.latitude);
			}
		}).addTo(map);
	});


  </script>

</body>
</html>