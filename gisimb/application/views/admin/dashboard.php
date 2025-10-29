<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<?php $this->load->view("admin/_partials/head.php") ?>
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
<body id="page-top">



<?php $this->load->view("admin/_partials/navbar.php") ?>

<div id="wrapper">

	<?php $this->load->view("admin/_partials/sidebar.php") ?>

	<div id="content-wrapper">

		<div class="container-fluid">

        <!-- 
        karena ini halaman overview (home), kita matikan partial breadcrumb.
        Jika anda ingin mengampilkan breadcrumb di halaman overview,
        silahkan hilangkan komentar (//) di tag PHP di bawah.
        -->
		<?php //$this->load->view("admin/_partials/breadcrumb.php") ?>

		<!-- Icon Cards-->
		<div class="row">
			<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-primary o-hidden h-100">
				<div class="card-body">
				<div class="card-body-icon">
					<i class="fas fa-fw fa-home"></i>
				</div>
				<div class="mr-5">26 IZIN BANGUNAN</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
				<span class="float-left">View Details</span>
				<span class="float-right">
					<i class="fas fa-angle-right"></i>
				</span>
				</a>
			</div>
			</div>
			<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-warning o-hidden h-100">
				<div class="card-body">
				<div class="card-body-icon">
					<i class="fas fa-fw fa-list"></i>
				</div>
				<div class="mr-5">11 IZIN USAHA</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
				<span class="float-left">View Details</span>
				<span class="float-right">
					<i class="fas fa-angle-right"></i>
				</span>
				</a>
			</div>
			</div>
			<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-success o-hidden h-100">
				<div class="card-body">
				<div class="card-body-icon">
					<i class="fas fa-fw fa-shopping-cart"></i>
				</div>
				<div class="mr-5">IZIN REKLAME</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
				<span class="float-left">View Details</span>
				<span class="float-right">
					<i class="fas fa-angle-right"></i>
				</span>
				</a>
			</div>
			</div>
			<div class="col-xl-3 col-sm-6 mb-3">
			<div class="card text-white bg-danger o-hidden h-100">
				<div class="card-body">
				<div class="card-body-icon">
					<i class="fas fa-fw fa-life-ring"></i>
				</div>
				<div class="mr-5">13 IZIN TAMBANG</div>
				</div>
				<a class="card-footer text-white clearfix small z-1" href="#">
				<span class="float-left">View Details</span>
				<span class="float-right">
					<i class="fas fa-angle-right"></i>
				</span>
				</a>
			</div>
			</div>
		</div>

		<!-- Area Chart Example-->
		<!-- <div class="card mb-3">
			<div class="card-header">
				<i class="fas fa-chart-area"></i>
				Visitor Stats
			</div>
			<div class="card-body">
				<canvas id="myAreaChart" width="100%" height="30"></canvas>
			</div>
			<div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
		</div> -->

		<!-- Area Chart Example-->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fas fa-map"></i>
				MAP DETAIL
			</div>
			<div class="card-body">
				<!-- <canvas id="map" width="100%" height="30"> -->

				<div id="mapid"></div>


				

					
				<!-- </canvas> -->
			</div>
			<div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
		</div>

		</div>
		<!-- /.container-fluid -->

		<!-- Sticky Footer -->
		<?php $this->load->view("admin/_partials/footer.php") ?>

	</div>
	<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

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
				

<?php $this->load->view("admin/_partials/scrolltop.php") ?>
<?php $this->load->view("admin/_partials/modal.php") ?>
<?php $this->load->view("admin/_partials/js.php") ?>

</body>
</html>
