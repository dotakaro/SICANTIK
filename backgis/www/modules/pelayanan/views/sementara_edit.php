<script type="text/javascript">
    function ceksumber(sumber)
    {
        if(sumber=='PASSPORT')
        {
                $("input[name=no_refer]").attr("class", 'input-wrc required');
        }
        else
        {
            $("input[name=no_refer]").attr("class", 'input-wrc required digits');
        }
    }
        

	function getKTP(){
	var nik = document.getElementById("idktp").value;
	//alert(nik);
    $.jsonp({
					url: 'http://sicantik.alp.layanan.go.id/backoffice/pelayanan/sementara/getCitizen/'+nik,
					callbackParameter: 'callback',
					success: function(data, status) {
					
						$.each(data, function(i,item){
							var ktp="";
							var nama = item.nilainamaLengkap;
							var alamat = item.nilaiAlamat;
							var prop  = item.nilaiPropNo;
							var kab  = item.nilaiKabNo;
							var kec  = item.nilaiKecNo;
							var kel = item.nilaiKelNo;
							
							//ktp += '<p>'+ nama + '</p>';
							ktp +='<div class="contentForm">';
							ktp +='<b><label>Nama</label></b>';
							ktp +='<input type = "text" value="'+ nama + '" disabled="disabled" class="input-wrc" id="namaw" name="namaw"></div>';
							ktp +='<div class="contentForm">';
							ktp +='<b><label>Propinsi</label></b>';
							ktp +='<input name="prop" type = "text" value="'+ prop + '" disabled="disabled" class="input-wrc" id="propw">';
							ktp +='<label for="prop" generated="true" class="error" style="">Field ini tidak dicopy</label></div>'
							ktp +='<div class="contentForm">';
							ktp +='<b><label>Kabupaten</label></b>';
							ktp +='<input name="kab" type = "text" value="'+ kab + '" disabled="disabled" class="input-wrc" id="kabw"><label for="kab" generated="true" class="error" style="">Field ini tidak dicopy</label></div>';
							ktp +='<div class="contentForm">';
							ktp +='<b><label>Kecamatan</label></b>';
							ktp +='<input name="kec" type = "text" value="'+ kec + '" disabled="disabled" class="input-wrc" id="kecw"><label for="kec" generated="true" class="error" style="">Field ini tidak dicopy</label></div>';
							ktp +='<div class="contentForm">';
							ktp +='<b><label>Kelurahan</label></b>';
							ktp +='<input name="kel" type = "text" value="'+ kel + '" disabled="disabled" class="input-wrc" id="kelw"><label for="kel" generated="true" class="error" style="">Field ini tidak dicopy</label></div>';
							ktp +='<div class="contentForm">';
							ktp +='<b><label>Alamat</label></b>';
							ktp +='<textarea id="alamatw" disabled="disabled">'+ alamat + '</textarea><br>';
							ktp +='<input type="button" onclick="javascript :copyKTP();" value="Pakai data ini" class="button-wrc" >';
							ktp +='<input type="button" onclick="javascript :cetakPermohonan();" value="Cetak Surat" class="button-wrc" >';
							ktp +='</div>';
							//ktp +='</form>';
							$('#tampil').append(ktp);
						});
					},
					error: function(){
						$('#tampil').append('<li>There was an error loading the feed</li>');
					}
			});
	}
	
	function copyKTP() {
		var nama = document.getElementById('namaw').value;
	  var alamat = document.getElementById('alamatw').value;
	  //alert(nama);
	  $(document).ready(function() {
						$('#alm').text(alamat);						
						$('#np').val(nama);					
						
				});
	  
	  
	}
	
	function cetakPermohonan() {
		//var kota = "KOTA	";
		var nama = document.getElementById('namaw').value;
		var alamat = document.getElementById('alamatw').value;
		var kel = document.getElementById('kelw').value;
		var kec = document.getElementById('kecw').value;
		var prop = document.getElementById('propw').value;
		var kota = document.getElementById('kabw').value;
		var par = nama+"/"+alamat+"/"+kel+"/"+kec+"/"+kota+"/"+prop;
		//alert(nama+alamat+kel+kec+prop);
		
		
		window.open("http://sicantik.alp.layanan.go.id/backoffice/pelayanan/sementara/cetakPermohonan/"+par)
		}
		
		
    
    
    function cheker()
    { 
        $('#form').validate();        
        var a = document.getElementsByName("pemohon_syarat[]");
        var jml ='<?php echo $jml_syarat; ?>';
        var total=0;
        for(var i=0; i < jml; i++){
            if(a[i].checked) {
                total++;
            }
        }
        
        if(total == jml){
            document.forms[0].submit.disabled=false;
            $('#coba').html('');
        }else{
            document.forms[0].submit.disabled=true;
            $('#coba').html("<p id='eror'>* Lengkapi Persyaratan Untuk Mengaktifkan Tombol Simpan</p>");
        }        
    }

    window.onload = cheker;
    $(function() {
        var validator = $('#form').validate();
        var tabs = $( "#tabs" ).tabs({
            select: function(event, ui){
                var valid = true;
                var current = $(this).tabs("option","selected");
                //var panelId = $('#tabs ul a').eq(current).attr("href");
                $('#form').find(':input.required, select.notSelect').each(function(){
                    console.log(valid);
                    if (!validator.element(this) && valid){
                        valid = false;     
                    }
                });
                if (valid == false){
                    $('#test').html('Data Belum Lengkap, Silahkah Diisi');
                }else{
                    $('#test').html('');
                }
                //return valid;
                
            }
        });
    });
</script>



<style>
    #eror
    {
        color:#FF0000;
        font-weight:bold;
        text-align:center;
    }

    .field_error
    {
        color:#FF0000;
        position:relative;
        font-size: 9px;
        margin: -4% 0 0 58%;
        padding: 0 0 2% 0 ;
    }

</style>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <legend>Data Perizinan</legend>
                <div id="statusRail">
                    <div id="leftRail" class="bg-grid">
                        <?php
                        echo '<b>' . form_label('Nama Izin', 'nama_izin') . '</b>';
                        ?>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <?php
                        echo $jenis_izin->n_perizinan;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo '<b>' . form_label('Kelompok Izin', 'kelompok_izin') . '</b>';
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        $jenis_izin->trkelompok_perizinan->get();
                        echo $jenis_izin->trkelompok_perizinan->n_kelompok;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail" class="bg-grid">
                        <?php
                        echo '<b>' . form_label('Jenis Permohonan', 'jenis_permohonan') . '</b>';
                        ?>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <?php
                        echo $jenis_permohonan->n_permohonan;
                        ?>
                    </div>
                </div>
                <div id="statusRail" style="font-weight: bold">
                    <div id="leftRail">
                        <?php
                        echo '<b>' . form_label('No Pendaftaran', 'no_daftar') . '</b>';
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        echo $list_daftar->pendaftaran_id;
                        ?>
                    </div>

                </div>
                <?php
                $img_download = array(
                    'src' => base_url() . 'assets/images/icon/file_pdf.png',
                    'alt' => 'Download Syarat',
                    'title' => 'Download Syarat',
                    'width' => '30',
                    'height' => 'inherit',
                    'border' => '0'
                );
                ?>
                <div id="statusRail" style="font-weight: bold">
                    <div id="leftRail">
                        <?php
                        echo '<b>' . form_label('View Persyaratan', 'syarat') . '</b>';
                        $a = explode('.', $list_daftar->file_ttd);
                        ?>
                    </div>
                    <div id="rightRail" >
                        <?php
//                           echo anchor('pelayanan/sementara/download/'.$list_daftar->pendaftaran_id,' '.img($img_download));
//                        
                        ?>

                        <!--  <a target=”_blank” href="http://localhost/alp-portal/daerah/upload/<?php echo $list_daftar->pendaftaran_id . "." . $a[1]; ?>">Lihat</a>
                        -->
                        <?php
                        $data = base_url();
                        $data = str_replace("bo-", "daerah-", $data) . "upload/";
                        ?>
                                                 <!-- <a target=”_blank” href="<?php echo $data . $list_daftar->pendaftaran_id . "." . $a[0]; ?>">Lihat</a> -->
                        <a target=”_blank” href="<?php echo $list_daftar->file_ttd; ?>">Lihat</a>              
                    </div>

                </div>
            </fieldset>
        </div>
        <?php
        if ($eror) {
            echo (" <p align='center' style='color:red;'><b>$eror</b></p>");
        }
//     $url = "http://localhost/testing.php";
//  $handle = @fopen($url, 'rb');
// $contents = stream_get_contents($handle);
// fclose($handle);
// echo $contents;
        ?>
        <p id='eror'><span id="test"></span></p>


        <?php
        $attr = array('name' => 'form', 'id' => 'form', 'onsubmit' => 'return validasi()');
        echo form_open('pelayanan/sementara/' . $save_method, $attr);
        echo form_hidden('jenis_izin_id', $jenis_izin->id);
        echo form_hidden('jenis_permohonan_id', $jenis_permohonan->id);
        echo form_hidden('id_daftar', $id_daftar);
        echo form_hidden('id_link', $id_link);
        echo form_hidden('waktu_awal', $waktu_awal);
        if ($paralel == "yes")
            echo form_hidden('jenis_paralel', $jenis_paralel->id);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Pemohon</a></li>
                    <li><a href="#tabs-2">Data Perusahaan</a></li>
                    <li><a href="#tabs-5">Data Proyek</a></li>
                    <li><a href="#tabs-3">Persyaratan</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <br style="clear: both" />
                        <div class="contentForm">
                            <?php
                            $data = array('KTP' => 'KTP','SIM' => "SIM",'PASSPORT' => 'PASSPORT');
                            echo '<b>' .form_label('Sumber Identitas') . '</b>';
                            if($cmbsource!=NULL)
                            {
                                    echo form_dropdown('cmbsource',$data,$cmbsource,'class = "input-select-wrc" id="cmbsource" onChange=" ceksumber(this.value);return false;" ');
                            }
                            else
                            {
                                    echo form_dropdown('cmbsource',$data,'0','class = "input-select-wrc" id="cmbsource" onChange=" ceksumber(this.value);return false;" ');
                            }
                            
                            ?>
                        </div>
                            <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
                            $norefer_input = array(
                                'name' => 'no_refer',
                                'value' => $no_refer,
                                'onkeyup'=>'ceksumber(this.form.cmbsource.value);return false;',
                                'class' => 'input-wrc required digits'
                            );
                            echo '<b>' . form_label('ID ') . '</b>';
                            echo form_input($norefer_input);
                            echo form_error('no_refer', '<div class="field_error">', '</div>');
// echo var_dump($cekPenduduk);
                            //if ($cekPenduduk !== "nothing") {
//                                if (empty($cekPenduduk)) {
//                                    echo "<br /><b><font color='red' size='1'>ID (SIM/KTP/Passport) Ini Tidak Terdaftar </font></b>";
//                                }
//                            } else {
//                                echo "<br /><b><font color='red' size='1'>Tidak bisa koneksi webservice penduduk</font></b>";
//                            }
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <br>
                        <div class="contentForm">
                            <b><label>Nama Pemohon *</label></b>
                            <?php
                            $namapemohon_input = array(
                                'name' => 'nama_pemohon',
                                'value' => $nama_pemohon,
                                'class' => 'input-wrc required',
                                'id' => 'np'
                            );

                            echo form_input($namapemohon_input);
                            echo form_error('nama_pemohon', '<div class="field_error">', '</div>');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $notelp_input = array(
                                'name' => 'no_telp',
                                'value' => $no_telp,
                                'class' => 'input-wrc required digits'
                            );
                            echo '<b>' . form_label('No Telp/HP ') . '</b>';
                            echo form_input($notelp_input);
                            echo form_error('no_telp', '<div class="field_error">', '</div>');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $tgldaftar_input = array(
                                'name' => 'tgl_daftar',
                                'value' => $tgl_daftar,
                                'class' => 'input-wrc required',
                                'readOnly' => TRUE,
                                'id' => 'inputTanggal1'
                            );
                            echo '<b>' . form_label('Tgl Terima Berkas ') . '</b>';
                            echo form_input($tgldaftar_input);
                            echo form_error('tgl_daftar', '<div class="field_error">', '</div>');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $tglsurvey_input = array(
                                'name' => 'tgl_survey',
                                'value' => $tgl_survey,
                                'class' => 'input-wrc required',
                                'readOnly' => TRUE,
                                'id' => 'inputTanggal2'
                            );
                            echo '<b>' . form_label('Tgl Peninjauan') . '</b>';
                            echo form_input($tglsurvey_input);
                            echo form_error('tgl_survey', '<div class="field_error">', '</div>');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $lokasi_input = array(
                                'name' => 'lokasi_izin',
                                'value' => $lokasi_izin,
                                'class' => 'input-area-wrc'
                            );
                            echo '<b>' . form_label('Lokasi Izin') . '</b>';
                            echo form_textarea($lokasi_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <b><?php echo '<b>' . form_label('Propinsi ') . '</b>'; ?> </b>
                            <?php
                            $opsi_propinsi = array('0' => '-------Pilih data-------');
                            foreach ($list_propinsi as $row) {
                                $opsi_propinsi[$row->id] = $row->n_propinsi;
                            }

                            if ($propinsi_usaha == " ") {
                                echo form_dropdown('propinsi_pemohon', $opsi_propinsi, '0', 'class = "input-select-wrc notSelect" id="propinsi_pemohon_id"');
                            } else {
                                echo form_dropdown('propinsi_pemohon', $opsi_propinsi, $propinsi_pemohon, 'class = "input-select-wrc notSelect" id="propinsi_pemohon_id"');
                            }
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <b><?php
                            echo '<b>' . form_label('Kabupaten ') . '</b>';
                            /*$opsi_kabupaten = array('0' => '-------Pilih data-------');
                            foreach ($list_kabupaten as $row) {
                                $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                            }*/
                            if ($kabupaten_pemohon == NULL) {
                                echo "<div id='show_kabupaten_pemohon'>Data Tidak Tersedia</div>";
                            } else {
//                                echo "<div id='show_kabupaten_pemohon'><input type='hidden' value='" . $kabupaten_pemohon . "' name='kabupaten_pemohon' />" . $opsi_kabupaten[$kabupaten_pemohon] . "</div>";
                                echo "<div id='show_kabupaten_pemohon'><input type='hidden' value='" . $kabupaten_pemohon . "' name='kabupaten_pemohon' />" . $nama_kabupaten_pemohon . "</div>";
                            }
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <b><?php
                                echo '<b>' . form_label('Kecamatan ') . '</b>';
                                /*$opsi_kecamatan = array('0' => '-------Pilih data-------');
                                foreach ($list_kecamatan as $row) {
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }*/
                                if ($kecamatan_pemohon == NULL) {
                                    echo "<div id='show_kecamatan_pemohon'>Data Tidak Tersedia</div>";
                                } else {
//                                    echo "<div id='show_kecamatan_pemohon'><input type='hidden' value='" . $kecamatan_pemohon . "' name='kecamatan_pemohon' />" . $opsi_kecamatan[$kecamatan_pemohon] . "</div>";
                                    echo "<div id='show_kecamatan_pemohon'><input type='hidden' value='" . $kecamatan_pemohon . "' name='kecamatan_pemohon' />" . $nama_kecamatan_pemohon . "</div>";
                                }
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <b><?php
                                echo '<b>' . form_label('Kelurahan ') . '</b>';
                                /*$opsi_kelurahan = array('0' => '-------Pilih data-------');
                                foreach ($list_kelurahan as $row) {
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }*/
                                if ($kelurahan_pemohon == NULL) {
                                    echo "<div id='show_kelurahan_pemohon'>Data Tidak Tersedia</div>";
                                } else {
//                                    echo "<div id='show_kelurahan_pemohon'><input type='hidden' value='" . $kelurahan_pemohon . "' name='kelurahan_pemohon' />" . $opsi_kelurahan[$kelurahan_pemohon] . "</div>";
                                    echo "<div id='show_kelurahan_pemohon'><input type='hidden' value='" . $kelurahan_pemohon . "' name='kelurahan_pemohon' />" . $nama_kelurahan_pemohon . "</div>";
                                }
                            ?>
                        </div>

                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
                            $alamatdata_input = array(
                                'name' => 'alamat_pemohon',
                                'value' => $alamat_pemohon,
                                'class' => 'input-area-wrc required',
                                'id'	=> 'alm'
                            );
                            echo '<b>' . form_label('Alamat Pemohon ') . '</b>';
                            echo form_textarea($alamatdata_input);
                            echo form_error('alamat_pemohon', '<div class="field_error">', '</div>');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $alamatdataluar_input = array(
                                'name' => 'alamat_pemohon_luar',
                                'value' => $alamat_pemohon_luar,
                                'class' => 'input-area-wrc'
                            );
                            echo '<b>' . form_label('Alamat Pemohon<br />di Luar Negeri<br />(isikan jika ada)') . '</b>';
                            echo form_textarea($alamatdataluar_input);
                            ?>
                        </div>
                    </div>
                    <div id="contentright">                    
                    
                        <div class="contentForm">
                            <?php
                            $data = array('KTP' => 'KTP','SIM' => "SIM",'PASSPORT' => 'PASSPORT');
                            echo '<b>' .form_label('Sumber Identitas') . '</b>';
                            if($cmbsource!=NULL)
                            {
                                    echo form_dropdown('cmbsource',$data,$cmbsource,'class = "input-select-wrc" id="cmbsource"  onChange=" ceksumber(this.value);return false;" ');
                            }
                            else
                            {
                                    echo form_dropdown('cmbsource',$data,'0','class = "input-select-wrc" id="cmbsource" onChange=" ceksumber(this.value);return false;" ');
                            }
                            
                            ?>
                        </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <?php
                                $norefer_input = array(
                                    'name' => 'no_refer',
                                    'value' => $no_refer,
                                    'class' => 'input-wrc required digits',
                                    'onkeyup'=>'ceksumber(this.form.cmbsource.value);return false;',
                                    'id' => "idktp"
                                );
                                echo '<b>' . form_label('ID') . '</b>';
                                echo form_input($norefer_input).'<span id="alert" style="color: red"></span>';
                                
                                if ($statusOnline2 == "1") {
                                    ?>
                                    <p id='eror1'><span id="error_id"></span></p>
                                    <br>

                                    <input type="button" onclick="javascript :getKTP();" value="Cek Id/KTP" class="button-wrc" >
                                <?php } ?>
                            </div>
                            <?php
                            if ($save_method == "update") {
                                echo ("<br>");
                            }
                            ?>
                            <div style="clear: both" ></div>
                            <div id="tampil"></div>
                    </div>
                    <br style="clear: both;" />
                </div>
                <div id="tabs-2">
                    <?php
                    if (!$nama_perusahaan) {
                        ?>
                        <div class="contentForm">
                            <?php
                            echo '<b>' . form_label('') . '</b>';
//                            echo anchor(site_url('pelayanan/sementara/pick_perusahaan_list'), 'Pilih Data Perusahaan', 'class="link-wrc" rel="perusahaan_box"');
                            ?>
                        </div>
                        <br style="clear: both" />
                        <?php
                    }
                    ?>
                    <div id="contentleft">

                        <div class="contentForm">
                            <?php
                            $npwp_input = array(
                                'name' => 'npwp',
                                'value' => $npwp,
                                'class' => 'input-wrc'
                            );
                            echo '<b>' . form_label('NPWP ') . '</b>';
                            echo form_input($npwp_input);
                            echo form_error('npwp', '<div class="field_error">', '</div>');
                            // echo var_dump($cekPajak);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $reg_input = array(
                                'name' => 'no_registrasi',
                                'value' => $noRegistrasi,
                                'class' => 'input-wrc'
                            );
                            echo '<b>' . form_label('No Register ') . '</b>';
                            echo form_input($reg_input);
                            echo form_error('no_registrasi', '<div class="field_error">', '</div>');
                           // if ($cekPajak !== "nothing") {
//                                if (empty($cekPajak)) {
//                                    echo "<br><b><font style='margin-left:22%;' color='red' size='1'>Npwp atau No Registrasi Ini Tidak Terdaftar </font></b>";
//                                }
//                            } else {
//                                echo "<br /><b><font style='margin-left:22%;' color='red' size='1'>Tidak bisa koneksi webservice Pajak</font></b>";
//                            }
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $namaperusahaan_input = array(
                                'name' => 'nama_perusahaan',
                                'value' => $nama_perusahaan,
                                'class' => 'input-wrc'
                            );
                            echo '<b>' . form_label('Nama Perusahaan ') . '</b>';
                            echo form_input($namaperusahaan_input);
                            echo form_error('nama_perusahaan', '<div class="field_error">', '</div>');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $telp_input = array(
                                'name' => 'telp_perusahaan',
                                'value' => $telp_perusahaan,
                                'class' => 'input-wrc digits'
                            );
                            echo '<b>' . form_label('Telp Perusahaan ') . '</b>';
                            echo form_input($telp_input);
                            echo form_error('telp_perusahaan', '<div class="field_error">', '</div>');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $fax_input = array(
                                'name' => 'fax',
                                'value' => $fax,
                                'class' => 'input-wrc digits',
                            );

                            echo '<b>' . form_label('Fax') . '</b>';
                            echo form_input($fax_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $email_input = array(
                                'name' => 'email',
                                'value' => $email,
                                'class' => 'input-wrc email',
                            );

                            echo '<b>' . form_label('Email') . '</b>';
                            echo form_input($email_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <b><?php echo '<b>' . form_label('Propinsi ') . '</b>'; ?> </b>
                            <?php
                            $opsi_propinsi = array('0' => '-------Pilih data-------');
                            foreach ($list_propinsi as $row) {
                                $opsi_propinsi[$row->id] = $row->n_propinsi;
                            }

                            if ($propinsi_usaha == " ") {
                                echo form_dropdown('propinsi_usaha', $opsi_propinsi, '0', 'class = "input-select-wrc" id="propinsi_usaha_id"');
                            } else {
                                echo form_dropdown('propinsi_usaha', $opsi_propinsi, $propinsi_usaha, 'class = "input-select-wrc" id="propinsi_usaha_id"');
                            }
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <b><?php
                            echo '<b>' . form_label('Kabupaten ') . '</b>';
                            /*$opsi_kabupaten = array('0' => '-------Pilih data-------');
                            foreach ($list_kabupaten as $row) {
                                $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                            }*/
                            if ($kabupaten_usaha == NULL) {
                                echo "<div id='show_kabupaten_usaha'>Data Tidak Tersedia</div>";
                            } else {
//                                echo "<div id='show_kabupaten_usaha'><input type='hidden' value='" . $kabupaten_usaha . "' name='kabupaten_usaha' />" . $opsi_kabupaten[$kabupaten_usaha] . "</div>";
                                echo "<div id='show_kabupaten_usaha'><input type='hidden' value='" . $kabupaten_usaha . "' name='kabupaten_usaha' />" . $nama_kabupaten_perusahaan . "</div>";
                            }
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <b><?php
                                echo '<b>' . form_label('Kecamatan ') . '</b>';
                                /*$opsi_kecamatan = array('0' => '-------Pilih data-------');
                                foreach ($list_kecamatan as $row) {
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }*/
                                if ($kecamatan_usaha == NULL) {
                                    echo "<div id='show_kecamatan_usaha'>Data Tidak Tersedia</div>";
                                } else {
//                                    echo "<div id='show_kecamatan_usaha'><input type='hidden' value='" . $kecamatan_usaha . "' name='kecamatan_usaha' />" . $opsi_kecamatan[$kecamatan_usaha] . "</div>";
                                    echo "<div id='show_kecamatan_usaha'><input type='hidden' value='" . $kecamatan_usaha . "' name='kecamatan_usaha' />" . $nama_kecamatan_perusahaan . "</div>";
                                }
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <b><?php
                                echo '<b>' . form_label('Kelurahan ') . '</b>';
                                /*$opsi_kelurahan = array('0' => '-------Pilih data-------');
                                foreach ($list_kelurahan as $row) {
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }*/
                                if ($kelurahan_usaha == NULL) {
                                    echo "<div id='show_kelurahan_usaha'>Data Tidak Tersedia</div>";
                                } else {
//                                    echo "<div id='show_kelurahan_usaha'><input type='hidden' value='" . $kelurahan_usaha . "' name='kelurahan_usaha' />" . $opsi_kelurahan[$kelurahan_usaha] . "</div>";
                                    echo "<div id='show_kelurahan_usaha'><input type='hidden' value='" . $kelurahan_usaha . "' name='kelurahan_usaha' />" . $nama_kelurahan_perusahaan . "</div>";
                                }
                            ?>
                        </div>

                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
                            $alamatusaha_input = array(
                                'name' => 'alamat_usaha',
                                'value' => $alamat_usaha,
                                'class' => 'input-area-wrc'
                            );
                            echo '<b>' . form_label('Alamat Perusahaan ') . '</b>';
                            echo form_textarea($alamatusaha_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            foreach ($list_kegiatan as $row) {
                                $opsi_kegiatan[' '] = "------Pilih salah satu------";
                                $opsi_kegiatan[$row->id] = $row->n_kegiatan;
                            }

                            echo '<b>' . form_label('Jenis Kegiatan ') . '</b>';
                            echo form_dropdown('jenis_kegiatan', $opsi_kegiatan, $jenis_kegiatan, 'class = "input-select-wrc" id="jenis_kegiatan"');
                            echo form_error('jenis_kegiatan', '<div class="field_error">', '</div>');
                            ?>
                            <p id="erorJ_kegiatan" align="right" style="visibility: hidden;"></p>
                        </div>
                        <div class="contentForm">
                            <?php
                            foreach ($list_investasi as $row) {
                                $opsi_investasi[' '] = "------Pilih salah satu------";
                                $opsi_investasi[$row->id] = $row->n_investasi;
                            }

                            echo '<b>' . form_label('Jenis Investasi ') . '</b>';
                            echo form_dropdown('jenis_investasi', $opsi_investasi, $jenis_investasi, 'class = "input-select-wrc"');
                            echo form_error('jenis_investasi', '<div class="field_error">', '</div>');
                            ?>
                            <p id="erorJ_investasi" align="right" style="visibility: hidden;"></p>
                        </div>
                    </div>
                    <div id="contentright">
                        <div class="contentForm">
                                <?php
                                if ($save_method == "save") {
                                    $id = 'npwp';
                                } else {
                                    $id = 'npwp2';
                                }

                                $npwp_input = array(
                                    'name' => 'npwp',
                                    'value' => $npwp,
                                    'class' => 'input-wrc'
                                );
                                echo form_label('NPWP ');
                                echo form_input($npwp_input);
                                ?>

                                &nbsp;

                            </div>


                            <div class="contentForm">
                                <?php
                                $nodaftar_input = array(
                                    'name' => 'nodaftar',
                                    'id' => 'nodaftar_id',
                                    'value' => $nodaftar,
                                    'class' => 'input-wrc'
                                );

                                echo form_label('No Register ');
                                echo form_input($nodaftar_input);
                                echo form_error('nodaftar', '<div class="field_error">', '</div>');
                                ?>
                                <?php if ($statusOnline == "1") { ?>

                                    <br>
                                    <input style="margin-left: 22%;" type="button" onclick="show_npwp(this.form)" value="Cek NPWP dan No Daftar" class="button-wrc" >
                                <?php } ?>
                            </div>

                            <div class="contentForm">
                                <?php
                                $namaperusahaan_input = array(
                                    'name' => 'nama_perusahaan',
                                    'value' => $nama_perusahaan,
                                    'class' => 'input-wrc',
                                );

                                echo form_label('Nama Perusahaan ');
                                echo form_input($namaperusahaan_input);
                                echo form_error('nama_perusahaan', '<div class="field_error">', '</div>');
                                ?>
                            </div>
                    </div>
                    <br style="clear: both;" />
                </div>
                <div id="tabs-3">
                    <table cellpadding="0" cellspacing="0" border="1" class="display">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="70%">Syarat</th>
                                <th width="10%">Terpenuhi</th>
                                <th width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($syarat_izin as $data) {
                                $show_syarat = new trperizinan_syarat();
                                $show_syarat->where('trsyarat_perizinan_id', $data->id)->where('trperizinan_id', $jenis_izin->id)->get();
                                $var = $show_syarat->c_show_type;
                                $rule = strval(decbin($var));
                                if (strlen($rule) < 4) {
                                    $len = 4 - strlen($rule);
                                    $rule = str_repeat("0", $len) . $rule;
                                }
                                $arr_rule = str_split($rule);

                                $c_daftar_ulang = $arr_rule[0];
                                $c_baru = $arr_rule[1];
                                $c_perpanjangan = $arr_rule[2];
                                $c_ubah = $arr_rule[3];

                                $syarat_status = $c_baru;
                                if ($syarat_status == '1') {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td align="center"><?php echo $i; ?></td>
                                        <td><?php echo $data->v_syarat; ?></td>
                                        <td align="center">
                                            <?php
                                            if ($save_method === 'update') {
                                                if (isset($check)) {
                                                    foreach ($check as $dt) {
                                                        if ($dt == $data->id) {
                                                            $checked = true;
                                                            break;
                                                        } else {
                                                            $checked = false;
                                                        }
                                                    }
                                                } else {
                                                    $checked = false;
                                                    if ($list_daftar) {
                                                        foreach ($list_daftar as $data_daftar) {
                                                            $data_syarat = new tmpermohonan_trsyarat_perizinan();
                                                            $data_syarat->where('tmpermohonan_id', $data_daftar->id)->where('trsyarat_perizinan_id', $data->id)->get();
                                                            if ($data_syarat->trsyarat_perizinan_id) {
                                                                $checked = true;
                                                                break;
                                                            }
                                                        }
                                                    } else {
                                                        $checked = false;
                                                        break;
                                                    }
                                                }

                                                $set = array('name' => 'pemohon_syarat[]', 'id' => 'chek', 'value' => $data->id,
                                                    'checked' => $checked, 'onClick' => 'cheker()');
                                                echo form_checkbox($set);
                                            } else {
                                                if (isset($check) && !empty($check)) {
                                                    foreach ($check as $dt) {
                                                        if ($dt == $data->id) {
                                                            $checked = true;
                                                            break;
                                                        } else {
                                                            $checked = false;
                                                        }
                                                    }
                                                } else {
                                                    $checked = false;
                                                }
                                                $set = array('name' => 'pemohon_syarat[]', 'value' => $data->id, 'checked' => $checked,
                                                    'onClick' => 'cheker()', 'id' => 'chek');
                                                echo form_checkbox($set);
                                            }
                                            ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if ($data->status == "1")
                                                $status_data = "Wajib";
                                            else
                                                $status_data = "Tidak Wajib";

                                            echo form_label($status_data);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!--- BEGIN - Tab 5 --->
                <div id="tabs-5">
                    <div class="contentForm">
                        <b><?php echo form_label('Jenis Usaha '); ?> </b>
                        <?php
                        if ($jenis_usaha == " ") {
                            echo form_dropdown('jenis_usaha_id', $listJenisUsaha, '0', 'class = "input-select-wrc" id="jenis_usaha_id"');
                        } else {
                            echo form_dropdown('jenis_usaha_id', $listJenisUsaha, $jenis_usaha, 'class = "input-select-wrc" id="jenis_usaha_id"');
                        }
                        ?>
                    </div>
                    <div style="clear: both" ></div>

                    <div class="contentForm">
                        <?php
                        $txtTargetPad = array(
                            'name' => 'target_pad',
                            'value' => $target_pad,
                            'class' => 'input-wrc',
                            'id' => 'target_pad',
                            'type'=>'number'
                        );
                        echo '<b>' . form_label('Target PAD') . '</b>';
                        echo form_input($txtTargetPad);
                        ?>
                    </div>
                    <div style="clear: both" ></div>

                    <div class="contentForm">
                        <?php
                        $txtNilaiInvestasi = array(
                            'name' => 'nilai_investasi',
                            'value' => $nilai_investasi,
                            'class' => 'input-wrc',
                            'id' => 'nilai_investasi',
                            'type'=>'number'
                        );
                        echo '<b>' . form_label('Nilai Investasi') . '</b>';
                        echo form_input($txtNilaiInvestasi);
                        ?>
                    </div>
                    <div style="clear: both" ></div>

                    <div class="contentForm">
                        <?php
                        $txtJumlahTenagaKerja = array(
                            'name' => 'jumlah_tenaga_kerja',
                            'value' => $jumlah_tenaga_kerja,
                            'class' => 'input-wrc',
                            'id' => 'jumlah_tenaga_kerja',
                            'type'=>'number'
                        );
                        echo '<b>' . form_label('Jumlah Tenaga Kerja') . '</b>';
                        echo form_input($txtJumlahTenagaKerja);
                        ?>
                    </div>
                    <div style="clear: both" ></div>
                </div>
                <!--- END - Tab 5 --->

            </div>
        </div>
        <div class="entry" style="text-align: center;">
            <?php
            $confirm_text = 'Apakah Anda yakin permohonan akan diproses?';
            $add_daftar = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan',
                'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
            );
            echo form_submit($add_daftar);
            echo "<span></span>";
            $link = site_url('pelayanan/sementara');
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . $link . '\''
            );
            echo form_button($cancel_daftar);
            echo form_close();
            ?>
            </br>
            <span id="coba"></span>
        </div>

    </div>
    <br style="clear: both;" />
</div>

<script type="text/javascript">
    $.validator.addMethod('notSelect', function(value, element){
        return (value !=0);
    },'Pilih Opsi Yang Tersedia')
</script>
