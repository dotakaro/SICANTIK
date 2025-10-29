$(document).click(function() { //Click anywhere and...
		$("#frmKatagori").hide(); //hide subpanel
		//$("#frmKatagori").removeClass('formKatagori'); //remove active class on subpanel trigger
	});

$("#btnSosialA").click(function(){
		//alert("sosial A");
		//peta_pilihan();
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('sosiala');
	});		
$("#btnSosialB").click(function(){
		//alert("sosial B");
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('sosialb');
	});		
$("#btnRtA").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('rumaha');
		//alert("Rumah Tangga A");
	});
$("#btnRtB").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('rumahb');
		//alert("Rumah tangga b");
	});
$("#btnRtC").click(function(){
		//alert("Rumah tangga c");
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('rumahc');
	});
$("#btnRtD").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('rumahd');
		//alert("Rumah tangga d");
	});
$("#btnDuta").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('konsultan');
		//alert("Kedutaan");
	});
$("#btnInstansi").click(function(){
		//alert("INstansi Pemerintah");
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('instansi');
	});
$("#btnBalai").click(function(){
		//alert("Balai Latihan/swasta");
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('balai');
	});
$("#btnUsahaA").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('usahaa');
		//alert("Usaha A");
	});
$("#btnUsahaB").click(function(){
		//alert("Usaha B");
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('usahab');
	});
$("#btnUsahaC").click(function(){
		//alert("Usaha C");
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('usahac');
	});
$("#btnIndustriA").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('industria');
		//alert("Industri A");
	});
$("#btnIndustriB").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('industrib');
		//alert("Industri B");
	});
$("#btnKhususA").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('khususa');
		//alert("Khusus A");
	});
$("#btnKhususB").click(function(){
		cari_lokasi(5.18527257829091,97.1256511756161);
		ambildatapelanggan('khususb');
		//alert("Khusus B");
	});
$("#btnCloseInput").click(function(){
		//alert("lkdsfj");
		$("#frmInput").slideUp();
	});
function msgbox(){
		alert("STMIK BINA BANGSA LHOKSEUMAWE");
	}
// tbarInput click
$("#tbarInput").click(function(){
		$("#frmInput").slideUp();
	});
//jendelainfo click
$("#jendelainfo").click(function(){
		$("#jendelainfo").fadeOut(500);	 
	});
//formLookLoc click
$("#btnLoc").click(function(){
		$("#formLookLoc").show();			
	});
//btnPetaawal click
$("#btnPetaawal").click(function(){
		peta_awal();				 
	});
//optBandasakti click
//btnBlok
$("#btnBlok").click(function(){
		blokArea();		 
	});
//btnGaris click
$("#btnGaris").click(function(){
		buatGaris();
	});
$("#btnUteunkot").click(function(){
		gpUteunkot();			 
	});
$("#btnCunda").click(function(){
		gpCunda();			 
	});
$(document).ready(function(){
						
						   })