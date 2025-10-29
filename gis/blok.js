function blokArea(){
    var lhokseumawe = new google.maps.LatLng(5.180613931613132, 97.14170151436122);
    var petaoption = {
        zoom: 15,
        center: lhokseumawe,
        mapTypeId: google.maps.MapTypeId.SATELLITE,
		  //navigationControl: true,
          //navigationControlOptions: {
              //style: google.maps.NavigationControlStyle.ZOOM_PAN,
              //position: google.maps.ControlPosition.TOP_RIGHT
          //},
		  //scaleControl: true,
          //scaleControlOptions: {
              //position: google.maps.ControlPosition.BOTTOM_RIGHT
          //}
		  
		  //poligon
		  
        };
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
	peta.setTilt(45);
  	peta.setHeading(90);

    google.maps.event.addListener(peta,'click',function(event){
        kasihtanda(event.latLng);
    });
    ambildatabase('awal');
	
	//buat poligon
	var paths = [new google.maps.LatLng(5.1777930829840395, 97.13156008207591),
          new google.maps.LatLng(5.178006784078418,97.13145547592433),
          new google.maps.LatLng(5.180528451536615, 97.13417523586543),
		  new google.maps.LatLng(5.181629006637983,97.13533395015986),
		  new google.maps.LatLng(5.182398325570126,97.13812881195338),
		  new google.maps.LatLng(5.182579970181518,97.13935189926417),
		  new google.maps.LatLng(5.1816610616121395,97.14161568367274),
		  new google.maps.LatLng(5.176468134587257,97.14034968101771),
		  
		  
		  new google.maps.LatLng(5.176852797311171,97.13958256923945),
		  new google.maps.LatLng(5.173454935157809,97.13860088074),
		  new google.maps.LatLng(5.1777930829840395,97.13156008207591)];
      
          
        var shape = new google.maps.Polygon({
          paths: paths,
          strokeColor: 'yellow',
          strokeOpacity: 0.8,
          strokeWeight: 1,
          fillColor: 'skyblue',
          fillOpacity: 0.35
        });
      
        shape.setMap(peta);    
}
function buatGaris() {
        var mapDiv = document.getElementById('petaku');
        var map = new google.maps.Map(mapDiv, {
          center: new google.maps.LatLng(5.1777930829840395, 97.13156008207591),
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.SATELLITE
        });
        var path = [new google.maps.LatLng(5.177384379440245, 97.13066422426493),
          new google.maps.LatLng(5.1810894143275394,97.13469021999629),
		  new google.maps.LatLng(5.182243393360657,97.13661068165095),
          new google.maps.LatLng(5.1825105178362065, 97.13811808311732),
		  new google.maps.LatLng(5.176708548817095,97.15390020096095),
		  new google.maps.LatLng(5.173706035658274,97.15371781074793),
		  new google.maps.LatLng(5.161353910043768,97.14531713211329)];
      
        var line = new google.maps.Polyline({
          path: path,
          strokeColor: '#ff0000',
          strokeOpacity: 0.5,
          strokeWeight: 7,
		  
        });
      
        line.setMap(map);
      }
      
function garislurus() {
        var mapDiv = document.getElementById('petaku');
        var map = new google.maps.Map(mapDiv, {
          center: new google.maps.LatLng(5.1777930829840395, 97.13156008207591),
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.SATELLITE
        });
        var path = [new google.maps.LatLng(5.177384379440245, 97.13066422426493),
          new google.maps.LatLng(5.22837945284288,96.99909382545741 ),
		  new google.maps.LatLng(5.22837945284288,96.99909382545741 ),
		  new google.maps.LatLng(5.22837945284288,97.13013046467097 ),
		  // new google.maps.LatLng(5.168924423908441,97.13013046467097 ),
		  new google.maps.LatLng(5.182243393360657,97.13661068165095)];
          //new google.maps.LatLng(5.1825105178362065, 97.13811808311732),
		  //new google.maps.LatLng(5.176708548817095,97.15390020096095),
		  new google.maps.LatLng(5.173706035658274,97.15371781074793)
		  //new google.maps.LatLng(5.161353910043768,97.14531713211329)];
      
        var line = new google.maps.Polyline({
          path: path,
          strokeColor: '#F90',
          strokeOpacity: 0.5,
          strokeWeight: 1
        });
      
        line.setMap(map);
      }
      //google.maps.event.addDomListener(window, 'load', initialize);