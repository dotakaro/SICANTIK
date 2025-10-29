function gpUteunkot() {
        var mapDiv = document.getElementById('petaku');
        var map = new google.maps.Map(mapDiv, {
          center: new google.maps.LatLng(5.1777930829840395, 97.13156008207591),
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.SATELLITE
        });
        var path = [new google.maps.LatLng(5.17358582798455, 97.12987029039652),
          new google.maps.LatLng(5.1716598308052495,97.12827705824168),
		  new google.maps.LatLng(5.171798737871416,97.12816976988108),
		  
          new google.maps.LatLng(5.171889561705902,97.12778889620097),
		  new google.maps.LatLng(5.170791660951164,97.12610983335765),
		  new google.maps.LatLng(5.169608276740023,97.12507986509593),
		  new google.maps.LatLng(5.169202239210112,97.12481164419444),
		  new google.maps.LatLng(5.16637065446268,97.12212943517954),
		  new google.maps.LatLng(5.162283533408247,97.12740265810282),
		  new google.maps.LatLng(5.16808830596345,97.13591867172511),
		  new google.maps.LatLng(5.172298268796906,97.13518911087306),
		  new google.maps.LatLng(5.174937495178614,97.13289313995631),
		  new google.maps.LatLng(5.172907322015782,97.13086538994105),
		  new google.maps.LatLng(5.17358582798455, 97.12987029039652)];
      
        var line = new google.maps.Polyline({
          path: path,
          //strokeColor: '#ff0000',
		  strokeColor: 'yellow',
          strokeOpacity: 1,
          strokeWeight: 1
        });
      
        line.setMap(map);
      }
function gpCunda() {
        var mapDiv = document.getElementById('petaku');
        var map = new google.maps.Map(mapDiv, {
          center: new google.maps.LatLng(5.1777930829840395, 97.13156008207591),
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.SATELLITE
        });
        var path = [new google.maps.LatLng(5.175129827040051, 97.1327295252064),
          new google.maps.LatLng(5.177269514998313,97.1306588598469),
		  new google.maps.LatLng(5.171798737871416,97.12816976988108),
		  
          new google.maps.LatLng(5.171889561705902,97.12778889620097),
		  new google.maps.LatLng(5.170791660951164,97.12610983335765),
		  new google.maps.LatLng(5.169608276740023,97.12507986509593),
		  new google.maps.LatLng(5.169202239210112,97.12481164419444),
		  new google.maps.LatLng(5.16637065446268,97.12212943517954),
		  new google.maps.LatLng(5.162283533408247,97.12740265810282),
		  new google.maps.LatLng(5.16808830596345,97.13591867172511),
		  new google.maps.LatLng(5.172298268796906,97.13518911087306),
		  new google.maps.LatLng(5.174937495178614,97.13289313995631),
		  new google.maps.LatLng(5.172907322015782,97.13086538994105),
		  new google.maps.LatLng(5.17358582798455, 97.12987029039652)];
      
        var line = new google.maps.Polyline({
          path: path,
          //strokeColor: '#ff0000',
		  strokeColor: 'yellow',
          strokeOpacity: 1,
          strokeWeight: 1
        });
      
        line.setMap(map);
      }