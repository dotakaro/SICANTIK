function cari_lokasi(x,y){
	var lhokseumawe = new google.maps.LatLng(x,y);
    //var lhokseumawe = new google.maps.LatLng(5.180357491348583, 97.14710884773524);
    var petaoption = {
        zoom: 15,
        center: lhokseumawe,
        mapTypeId: google.maps.MapTypeId.SATELLITE
        };
    peta = new google.maps.Map(document.getElementById("petaku"),petaoption);
	peta.setTilt(45);
  	peta.setHeading(90);

    google.maps.event.addListener(peta,'click',function(event){
        kasihtanda(event.latLng);
    });
    //ambildatabase('awal');
}

//ambildatapelanggan
function ambildatapelanggan(akhir){
            url = "datapelanggan.php";

    $.ajax({
		   //url: "simpanlokasi.php",
            //data: "x="+x+"&y="+y+"&judul="+judul+"&des="+des+"&jenis="+jenis+"&katagori="+katagori,
        url: url,
		data:"jenis="+akhir,
        dataType: 'json',
        cache: false,
        success: function(msg){
            for(i=0;i<msg.wilayah.petak.length;i++){
                judulx[i] = msg.wilayah.petak[i].judul;
                desx[i] = msg.wilayah.petak[i].deskripsi;

                set_icon(msg.wilayah.petak[i].jenis);
                var point = new google.maps.LatLng(
                    parseFloat(msg.wilayah.petak[i].x),
                    parseFloat(msg.wilayah.petak[i].y));
                tanda = new google.maps.Marker({
                    position: point,
                    map: peta,
                    icon: gambar_tanda
                });
                setinfo(tanda,i);

            }
        }
    });
}
//optBandasakti click
$("#optBandasakti").click(function(){
	//href="javascript:cari_lokasi(5.180357491348583,97.14710884773524);"
		cari_lokasi(5.180357491348583,97.14710884773524);
		ambildatapelanggan('rumaha');
	});