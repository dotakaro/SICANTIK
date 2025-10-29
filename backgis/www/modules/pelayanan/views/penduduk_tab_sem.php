<script language="javascript" type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    $(document).ready(function() {
        $('a[rel*=pemohon_box]').facebox();
        $('a[rel*=daftar_box]').facebox();
    } );

    $(function() {
        $("#inputTanggal1").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            closeText: 'X'
        });
        $("#inputTanggal2").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            closeText: 'X'
        });
    });

    function show_ktp() {
        $.post('<?php echo base_url(); ?>pelayanan/sementara/pick_penduduk_data', {
            data_no_refer: $('#no_refer1').val()
        }, function(response){
            setTimeout("finishAjax('tabs-1', '"+escape(response)+"')", 400);
        });
        return false;
    }


    function clear_data() {
        $.post('<?php echo base_url(); ?>pelayanan/sementara/pick_penduduk_data', {
            data_no_refer: $('#clear_id').val()
        }, function(response){
            setTimeout("finishAjax('tabs-1', '"+escape(response)+"')", 400);
        });
        return false;
    }
    
    $(document).ready(function() {
        $('#propinsi_pemohon_id').change(function(){
            $.post('<?php echo base_url(); ?>pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
            function(data) {
                $('#show_kabupaten_pemohon').html(data);
                $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
            });
        }); 
    });


    function finishAjax(id, response){
        $('#'+id).html(unescape(response));
        $('#'+id).fadeIn();
    }
</script>
<style>
.eror
{
    color: #FF0000;
    font-weight: bold;
    position: inherit;
    padding-bottom: 2%;
   
    text-align: left;
   
    
}

</style>
<?php
           // $settings = new settings();
//            $app_web_service = $settings->where('name', 'web_service_penduduk')->get();
//            $url = $app_web_service->value.$_REQUEST['data_no_refer'];
           // if($url !== "http://localhost/webservicePenduduk/api/penduduk/getdatawpbyktp")
//            {
//                echo "<script>alert ('URL Webservice Penduduk masih salah. Cek Menu Setting Webservice...');
//                    </script>";
//                 $id_perusahaan = '';
//                    $refer = "";
//                    $nama = "";
//                    $tlp = "";
//                    $alamat = "";
//                    $luar = "";
//            }
//            else {
 //           $handle = @fopen($url, 'r');
//
//            if ($handle !== false) {
                //web service WSDL
//                $client = new SoapClient($url);
//                $data_xml = $client->getDataWpByNpwp(array('npwp' => $_REQUEST['data_npwp_id']));
//                foreach ($data_xml as $xml){
//                    $id_perusahaan = '';
//                    $npwp = $xml->npwp;
//                    $nodaftar = $xml->no;
//                    $nama_perusahaan = $xml->nama;
//                    $alamat_usaha = $xml->alamat;
//                    $rt = $xml->rt;
//                    $rw = $xml->rw;
//                    $telp_perusahaan = $xml->telepon;
//                    $fax = $xml->fax;
//                    $email = $xml->email;
//    //              $getdata = $xml->getdata;
//                }
                //web service API

        //       $data_url = $url . '/' . $_REQUEST['data_no_refer'];
//               $data_xml = $this->curl->simple_get($data_url, array(CURLOPT_PORT => 8080));
//               $data_xml = simplexml_load_string($data_xml);
//               $nama = null;
//                foreach ($data_xml as $xml) {
//                    $id_perusahaan = '';
//                    $refer = $xml->no_referensi;
//                    $nama = $xml->nama_pemohon;
//                    $tlp = $xml->telp_pemohon;
//                    $alamat = $xml->alamat_pemohon;
//                    $luar = $xml->luar_negeri;
//                    //    $getdata = $xml->getdata;
//                }
//                 if($_REQUEST['data_no_refer'] == " ")
//                      {
//                          echo " ";
//                      }
//                      else
//                      {
//                          if ($refer=="")
//                          {
//                          echo "<div class='eror'>ID Penduduk tidak ditemukan... </div>";
//                          }
//                      }
//            } else {
//                echo "<div class='eror'>Webservice Penduduk Belum Online... </div>";
//                  $id_perusahaan = '';
//                    $refer = "";
//                    $nama = "";
//                    $tlp = "";
//                    $alamat = "";
//                    $luar = "";
//            }
          
         //   }
         
 //        $ch = curl_init();                              // PHP_CURL in php.ini must be enabled 
//	                                                // (extension=[php_curl.dll|php_curl.so]) 
//	curl_setopt($ch, CURLOPT_URL, $url);            // Set URL 
//	curl_setopt($ch, CURLOPT_HEADER, FALSE);
//	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
//	curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);  // Return result
//
//	$result=curl_exec($ch);                         // Connect to URL and get result
//	
//
//	
//	if($result):
//		if(substr($result,0,5)=="REST:"):                  //Replace REST:
//			$result=substr_replace($result,"",0,5);
//		endif;
//		if($result):
//			if(strtolower(substr($result,0,5))=="<?xml"): //Detect XML format
//
//				$xmle = new SimpleXMLElement($result);     //Parsing XML into Array
//				$rootName=strtolower($xmle->getName());
//				if($rootName=="invalid_response"):
//					$result=(string) $xmle;
//				elseif ($rootName=="valid_response"):
//					$xmli = new SimpleXMLIterator( $result );
//					$result=array();
//					$i=0;
//					for( $xmli->rewind(); $xmli->valid(); $xmli->next() ):
//						if($xmli->hasChildren()):
//							$items=(array) $xmli->current();
//							foreach($items as $key=>$item):
//								$result[$i][$key]=(string) $item;
//							endforeach;
//							$i++;
//						endif;
//					endfor;	
//				else:
//					$result=false;
//					$messageAPI="No result from API Webservice";
//				endif;
//			endif;
//		endif;
//	endif;
//    if ($_REQUEST['data_no_refer']=="")
//    {
//          echo "<div class='eror'>ID Penduduk tidak ditemukan... </div>";
//         $id_perusahaan = '';
//         $refer = "";
//         $nama = "";
//         $tlp = "";
//         $alamat = "";
//         $luar = "";
//          
//    }else
//    {
//     $id_perusahaan = '';
//     $refer = "";
//     $nama = $result['0']['NAMA'];
//     $tlp = "";
//     $alamat = $result['0']['ALAMAT'];;
//     $luar = "";
//    }

    
if ($_REQUEST['data_no_refer']=="")
    {
     $_REQUEST['data_no_refer']="";           
     $id_perusahaan = '';
     $refer = "";
     $nama = "";
     $tlp = "";
     $alamat = "";
     $luar = "";

    }else
    {
        if ($_REQUEST['data_no_refer']!==$mantra['nilaiBiodataWNI']['nilaiNIK'])
    {
         $id_perusahaan = '';
     $refer = "";
     $nama = $mantra['nilaiBiodataWNI']['nilainamaLengkap'];
     $tlp = "";
     $alamat = $mantra['nilaiBiodataWNI']['nilaiProp']."-".$mantra['nilaiBiodataWNI']['nilaiKab']."-".$mantra['nilaiBiodataWNI']['nilaiKec']."-".$mantra['nilaiBiodataWNI']['nilaiKel'];
     $luar = "";
     echo "<b><p style='color:red;'>Nik Tidak ditemukan</p></b>";
    } else {
     $id_perusahaan = '';
     $refer = "";
     $nama = $mantra['nilaiBiodataWNI']['nilainamaLengkap'];
     $tlp = "";
     $alamat = $mantra['nilaiBiodataWNI']['nilaiProp']."-".$mantra['nilaiBiodataWNI']['nilaiKab']."-".$mantra['nilaiBiodataWNI']['nilaiKec']."-".$mantra['nilaiBiodataWNI']['nilaiKel'];
     $luar = "";
   
    }
        
    }    
?>

<div id="tabs-1">
   <div id="contentleft">
             
                            <div style="clear: both" ></div>
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
                                    'id' =>'no_refer1',
                                    'name' => 'no_refer',
                                    'value' => $_REQUEST['data_no_refer'],
                                    'onkeyup'=>'ceksumber(this.form.cmbsource.value);return false;',
                                    'class' => 'input-wrc required'
                                     
                                );
                                echo  '<b>' .form_label('ID '). '<b>';
                                echo form_input($norefer_input);
                                ?>
                            </div>
                           
                            <div class="contentForm">
                                <?php
                                $namapemohon_input = array(
                                    'name' => 'nama_pemohon',
                                    'value' => $nama_pemohon,
                                    'class' => 'input-wrc required'
                                );
                                echo  '<b>' .form_label('Nama Pemohon '). '<b>';
                                echo form_input($namapemohon_input);
                                echo form_error('nama_pemohon', '<div class="field_error">','</div>');
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $notelp_input = array(
                                    'name' => 'no_telp',
                                    'value' => $no_telp,
                                    'class' => 'input-wrc required digits'
                                );
                                echo  '<b>' .form_label('No Telp/HP '). '<b>';
                                echo form_input($notelp_input);
                                echo form_error('no_telp', '<div class="field_error">','</div>');
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $tgldaftar_input = array(
                                    'name' => 'tgl_daftar',
                                    'value' => $tgl_daftar,
                                    'class' => 'input-wrc required',
                                    'id' => 'inputTanggal1'
                                );
                                echo  '<b>' .form_label('Tgl Terima Berkas ') .'<b>';
                                echo form_input($tgldaftar_input);
                                echo form_error('tgl_daftar', '<div class="field_error">','</div>');
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $tglsurvey_input = array(
                                    'name' => 'tgl_survey',
                                    'value' => $tgl_survey,
                                    'class' => 'input-wrc',
                                    'id' => 'inputTanggal2'
                                );
                                echo  '<b>' .form_label('Tgl Peninjauan'). '<b>';
                                echo form_input($tglsurvey_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $lokasi_input = array(
                                    'name' => 'lokasi_izin',
                                    
                                    'class' => 'input-area-wrc'
                                );
                                echo  '<b>' .form_label('Lokasi Izin'). '<b>';
                                echo form_textarea($lokasi_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $ket_input = array(
                                    'name' => 'keterangan',
                                   'value' => $lokasi_izin,
                                    'class' => 'input-area-wrc'
                                );
                                echo  '<b>' .form_label('Keterangan') .'<b>';
                                echo form_textarea($ket_input);
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
        </div>
        <div style="clear: both" ></div>
        <div class="contentForm">
                            <b><?php
                            echo '<b>' . form_label('Kabupaten ') . '</b>';
                            $opsi_kabupaten = array('0' => '-------Pilih data-------');
                            foreach ($list_kabupaten as $row) {
                                $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                            }
                            if ($kabupaten_pemohon == NULL) {
                                echo "<div id='show_kabupaten_pemohon'>Data Tidak Tersedia</div>";
                            } else {
                                echo "<div id='show_kabupaten_pemohon'><input type='hidden' value='" . $kabupaten_pemohon . "' name='kabupaten_pemohon' />" . $opsi_kabupaten[$kabupaten_pemohon] . "</div>";
                            }
                            ?>
                        </div>
        <div style="clear: both" ></div>
        <div class="contentForm">
                            <b><?php
                                echo '<b>' . form_label('Kecamatan ') . '</b>';
                                $opsi_kecamatan = array('0' => '-------Pilih data-------');
                                foreach ($list_kecamatan as $row) {
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }
                                if ($kecamatan_pemohon == NULL) {
                                    echo "<div id='show_kecamatan_pemohon'>Data Tidak Tersedia</div>";
                                } else {
                                    echo "<div id='show_kecamatan_pemohon'><input type='hidden' value='" . $kecamatan_pemohon . "' name='kecamatan_pemohon' />" . $opsi_kecamatan[$kecamatan_pemohon] . "</div>";
                                }
                            ?>
        <div style="clear: both" ></div>
       <div class="contentForm">
                            <b><?php
                                echo '<b>' . form_label('Kelurahan ') . '</b>';
                                $opsi_kelurahan = array('0' => '-------Pilih data-------');
                                foreach ($list_kelurahan as $row) {
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }
                                if ($kelurahan_pemohon == NULL) {
                                    echo "<div id='show_kelurahan_pemohon'>Data Tidak Tersedia</div>";
                                } else {
                                    echo "<div id='show_kelurahan_pemohon'><input type='hidden' value='" . $kelurahan_pemohon . "' name='kelurahan_pemohon' />" . $opsi_kelurahan[$kelurahan_pemohon] . "</div>";
                                }
                            ?>
                        </div>

        <div style="clear: both" ></div>
        								<div class="contentForm">
                                <?php
                                $alamatdata_input = array(
                                    'name' => 'alamat_pemohon',
                                    'value' => $alamat_pemohon,
                                    'class' => 'input-area-wrc required'
                                );
                                echo form_label('Alamat Pemohon ');
                                echo form_textarea($alamatdata_input);
                                echo form_error('alamat_pemohon', '<div class="field_error">','</div>');

                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $alamatdataluar_input = array(
                                    'name' => 'alamat_pemohon_luar',
                                    'value' => $alamat_pemohon_luar,
                                    'class' => 'input-area-wrc'
                                );
                                echo form_label('Alamat Pemohon<br />di Luar Negeri<br />(isikan jika ada)');
                                echo form_textarea($alamatdataluar_input);
                                ?>
                            </div>
                        </div>
                        <div id="contentright">
                            <div class="contentForm">
                                <?php
                                $norefer_input = array(
                                    'id' =>'no_refer1',
                                    'name' => 'no_refer',
                                    'value' => $_REQUEST['data_no_refer'],
                                    'onkeyup'=>'ceksumber(this.form.cmbsource.value);return false;',
                                    'class' => 'input-wrc required'
                                     
                                );
                                echo  '<b>' .form_label('ID '). '<b>';
                                echo form_input($norefer_input);
                                echo form_error('no_refer', '<div class="field_error">','</div>');
                                 if($statusOnline2 == "1"){
                                 ?>
                                <br>
                         
                            <input type="hidden" id="clear_id" name="clear_id" value="">
                            <input type="button" onclick="show_ktp()" value="Cek Id/KTP" class="button-wrc" >
                            <input type="button" onclick="clear_data()" value="Clear Data" class="button-wrc">
                            <?php } ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $alamatdata_input = array(
                                    'name' => 'alamat_pemohon',
                                    'value' => $alamat,
                                    'class' => 'input-area-wrc required'
                                );
                                echo form_label('Alamat Pemohon ');
                                echo form_textarea($alamatdata_input);
                                echo form_error('alamat_pemohon', '<div class="field_error">','</div>');

                                ?>
                            </div>
                        </div>
                        <br style="clear: both;" />
                    </div>