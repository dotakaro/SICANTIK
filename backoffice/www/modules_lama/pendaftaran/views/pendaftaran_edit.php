<script>
    $(document).ready(function() {
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
		$('#inputTanggal2, #inputTanggal1').change(function(){
			validasi();
			cheker();
		});
    });
    function validasi()
    {
        var tgl1 = document.getElementById('tglambil_izin').value;
        var tgl2 = document.getElementById('inputTanggal1').value;
        var tgl3 = document.getElementById('inputTanggal2').value;
        var id_j = document.getElementById('jenisp').value;

        var m_names = new Array("Januari", "Februari", "Maret", 
        "April", "Mei", "Juni", "Juli", "Agustus", "September", 
        "Oktober", "November", "Desember");
        var dd = tgl1.substring(8,10)
        var mm = tgl1.substring(6,7)
        var yy = tgl1.substring(0,4)
        var ddd = tgl2.substring(8,10)
        var mmm = tgl2.substring(6,7)
        var yyy = tgl2.substring(0,4)
    
        if(id_j=='2')
            var jn = "Perubahan";
        else if(id_j=='3')
            var jn = "Perpanjangan";
        else
            var jn = "Daftar Ulang";
        
		tgl3_1 = tgl3.replace(/\-/g,'');
		tgl2_1 = tgl2.replace(/\-/g,'');
		tgl1_1 = tgl1.replace(/\-/g,'');
		
		
		if(tgl2_1 < tgl1_1 || tgl2_1=='')
        {
            $('#coba').html("<p id='eror'>Tanggal "+jn+" izin tidak boleh lebih kecil dari tanggal Penyerahan berkas ("+(dd + " " + m_names[mm-1] 
                + " " + yy)+")</p>");
            //alert('Tanggal perubahan izin tidak boleh lebih kecil dari\ntanggal persetujuan berkas');
            return false;
        }
        else if(tgl3_1 < tgl2_1)
        {
			//alert('Tanggal survei tidak boleh lebih kecil dari\ntanggal perubahan izin');
            $('#coba').html("<p id='eror'>Tanggal survei "+jn+" izin tidak boleh lebih kecil dari tanggal "+jn+" izin ("+(ddd + " " + m_names[mmm-1] 
                + " " + yyy)+")</p>");
            return false;
        }
        else
        {
            return true;
        }
		
    }
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
    function cheker()
    { 
        var a = document.getElementsByName("pemohon_syarat[]");
        var jml ='<?php echo $jml_syarat; ?>';
        var total=0;
        for(var i=0; i < jml; i++){
            if(a[i].checked) {
                total++;
            }
        }
        if(validasi()==false)
        {
            document.forms[0].submit.disabled=true;
        }
        else if(total == jml){
            document.forms[0].submit.disabled=false;
            $('#coba').html('');
        }else{
            document.forms[0].submit.disabled=true;
            $('#coba').html("<p id='eror'>* Lengkapi Persyaratan Untuk Mengaktifkan Tombol Simpan</p>");
            
        }        
    }

    window.onload = cheker;

</script>

<script type="text/javascript">
    
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
        margin: -4% 0 0 74%;
        padding: 0 0 2% 0 ;

    }

    .error_npwp
    {
        margin: -4% 0 0 71%;
        padding: 0 0 1% 0 ;
        color:#FF0000;
        font-size: 9px;

    }

</style>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('name' => 'form', 'id' => 'form', 'onsubmit' => 'return validasi()');
        echo form_open('pendaftaran/' . $save_method, $attr);
        echo form_hidden('id_jenis', $id_jenis);
        echo form_hidden('jenis_izin_id', $jenis_izin->id);
        echo form_hidden('jenis_permohonan_id', $jenis_permohonan->id);
        echo form_hidden('id_daftar', $id_daftar);
        echo form_hidden('id_link', $id_link);
        ?>
        <div class="entry">
            <?php
            if ($id_daftar) {
                ?>
                <fieldset>
                    <legend>Data Perizinan</legend>

                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                            <?php
                            echo form_label('Nama Izin');
                            ?>
                        </div>
                        <div id="rightRail" class="bg-grid">
                            <?php
                            echo $jenis_izin->n_perizinan;
                            ?>
                        </div>
                    </div>
                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                            <?php
                            echo form_label('Jenis Permohonan');
                            ?>
                        </div>
                        <div id="rightRail" class="bg-grid">
                            <?php
                            echo $jenis_permohonan->n_permohonan;
                            ?>
                        </div>
                    </div>
                    <div id="statusRail">
                        <div id="leftRail">
                            <?php
                            echo form_label('Kelompok Izin');
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
                        <div id="leftRail">
                            <?php
                            echo form_label('Tanggal ' . $jenis_permohonan->n_permohonan . " *");
                            ?>
                        </div>
                        <div id="rightRail">
                            <?php
                            $tgldaftar_input = array(
                                'name' => 'tgl_daftar_baru',
                                'value' => $tgl_daftar_baru,
                                'readOnly' => TRUE,
                                'class' => 'input-all required',
                                'id' => 'inputTanggal1',
                            );
                            echo form_input($tgldaftar_input);
                            ?>
                            <input type="hidden" value="<?php echo $tgl_survey; ?>" name="txttglselesai" id="txttglselesai" />
                            <input type="hidden" value="<?php echo $tglambil_izin; ?>" name="tglambil_izin" id="tglambil_izin" />
                            <input type="hidden" value="<?php echo $id_jenis; ?>" name="jenisp" id="jenisp" />
                        </div>
                    </div>
                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                            <?php
                            echo form_label('No Pendaftaran Lama');
                            ?>
                        </div>
                        <div id="rightRail" class="bg-grid">
                            <?php
                            echo $no_pendaftaran;
                            ?>
                        </div>
                    </div>
                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                            <?php
                            echo form_label('No Surat Izin Lama');
                            ?>
                        </div>
                        <div id="rightRail" class="bg-grid">
                            <?php
                            echo $no_sk;
                            ?>
                        </div>
                    </div>
                    <?php
                    if ($save_method == "update") {
                        ?>
                        <div id="statusRail" style="font-weight: bold">
                            <div id="leftRail">
                                <?php
                                echo form_label('No Pendaftaran');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo $no_pendaftaran_baru;
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </fieldset>
                <?php
            } else {
                ?>
                <fieldset id="half">
                    <legend>Keterangan</legend>
                    <div class="ContentForm" style="font-weight: bold;text-align: center">
                        No Surat Izin / No Pendaftaran belum dipilih
                        <div style="float: right">
                            <?php
                            $kembali = array(
                                'name' => 'button',
                                'class' => 'button-wrc',
                                'content' => '&laquo; back',
                                'onclick' => 'parent.location=\'' . site_url('pendaftaran/index') . '/' . $jenis_permohonan->id . '\''
                            );
                            echo form_button($kembali);
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </fieldset>
        </div>
        <p id='eror'><span id="test"></span></p>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Pemohon</a></li>
                    <li><a href="#tabs-2">Data Perusahaan</a></li>
                    <li><a href="#tabs-3">Persyaratan</a></li>
                </ul>
                <?php
                if ($id_daftar) {
                    ?>
                    <div id="tabs-1">
                        <div id="contentleft">
                            <div class="contentForm">
                                <?php
                                $data = array('KTP' => 'KTP', 'SIM' => "SIM", 'PASSPORT' => 'PASSPORT');
                                echo form_label('Sumber Identitas');
                                if ($cmbsource != NULL) {
                                    echo form_dropdown('cmbsource', $data, $cmbsource, 'class = "input-select-wrc" id="cmbsource" onChange=" ceksumber(this.value);return false;"');
                                } else {
                                    echo form_dropdown('cmbsource', $data, '0', 'class = "input-select-wrc" id="cmbsource" onChange=" ceksumber(this.value);return false;"');
                                }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <?php
                                $norefer_input = array(
                                    'name' => 'no_refer',
                                    'value' => $no_refer,
                                    'onkeyup' => 'ceksumber(this.form.cmbsource.value);return false;',
                                    'class' => 'input-wrc required digits'
                                );
                                echo form_label('ID');
                                echo form_input($norefer_input);
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <?php
                                $namapemohon_input = array(
                                    'name' => 'nama_pemohon',
                                    'value' => $nama_pemohon,
                                    'class' => 'input-wrc required'
                                );

                                echo form_label('Nama Pemohon ');
                                echo form_input($namapemohon_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $notelp_input = array(
                                    'name' => 'no_telp',
                                    'value' => $no_telp,
                                    'class' => 'input-wrc digits'
                                );
                                echo form_label('No Telp/HP ');
                                echo form_input($notelp_input);
                                ?>
                            </div>
                            <br style="clear: both" />
                            <!--                        <div class="contentForm">
                            <?php
                            $tgldaftar_input = array(
                                'name' => 'tgl_daftar',
                                'value' => $tgl_daftar,
                                'class' => 'input-all required',
                                'id' => 'inputTanggal1'
                            );
                            echo form_label('Tgl Terima Berkas ');
                            echo form_input($tgldaftar_input);
                            ?>
                                                        </div>
                                                        <br style="clear: both;" />-->
                            <div class="contentForm">
                                <?php
                                $tglsurvey_input = array(
                                    'name' => 'tgl_survey',
                                    'value' => $tgl_survey,
                                    'class' => 'input-wrc',
                                    'readOnly' => TRUE,
                                    'id' => 'inputTanggal2',
                                );
                                echo form_label('Tgl Survey');
                                echo form_input($tglsurvey_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $lokasi_input = array(
                                    'name' => 'lokasi_izin',
                                    'value' => $lokasi_izin,
                                    'class' => 'input-area-wrc'
                                );
                                echo form_label('Lokasi Izin');
                                echo form_textarea($lokasi_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $ket_input = array(
                                    'name' => 'keterangan',
                                    'value' => $keterangan,
                                    'class' => 'input-area-wrc'
                                );
                                echo form_label('Keterangan');
                                echo form_textarea($ket_input);
                                ?>
                            </div>
                        </div>
                        <div id="contentright">
                            <div class="contentForm">
                                <b><?php echo form_label('Propinsi '); ?> </b>
                                <?php
                                $opsi_propinsi = array('0' => '-------Pilih data-------');
                                foreach ($list_propinsi as $row) {
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                }
                                // edited by mucktar
                                if ($propinsi_usaha == " ") {
                                    echo form_dropdown('propinsi_pemohon', $opsi_propinsi, '0', 'class = "input-select-wrc" id="propinsi_pemohon_id"');
                                } else {
                                    echo form_dropdown('propinsi_pemohon', $opsi_propinsi, $propinsi_pemohon, 'class = "input-select-wrc" id="propinsi_pemohon_id"');
                                }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                            echo form_label('Kabupaten ');
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
                                echo form_label('Kecamatan ');
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
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                                echo form_label('Kelurahan ');
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
                                    'class' => 'input-area-wrc'
                                );
                                echo form_label('Alamat Pemohon ');
                                echo form_textarea($alamatdata_input);
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
                        <br style="clear: both;" />
                    </div>
                    <div id="tabs-2">
                        <div id="contentleft">
                            <div class="contentForm">
                                <?php
                                $namaperusahaan_input = array(
                                    'name' => 'nama_perusahaan',
                                    'value' => $nama_perusahaan,
                                    'class' => 'input-wrc'
                                );
                                echo form_label('Nama Perusahaan ');
                                echo form_input($namaperusahaan_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $npwp_input = array(
                                    'name' => 'npwp',
                                    'value' => $npwp,
                                    /*'class' => 'input-wrc required'*/ //Edited by Indra
									'class' => 'input-wrc'
                                );
                                echo form_label('NPWP ');
                                echo form_input($npwp_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $telp_input = array(
                                    'name' => 'telp_perusahaan',
                                    'value' => $telp_perusahaan,
                                    'class' => 'input-wrc'
                                );
                                echo form_label('Telp Perusahaan ');
                                echo form_input($telp_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $alamatusaha_input = array(
                                    'name' => 'alamat_usaha',
                                    'value' => $alamat_usaha,
                                    'class' => 'input-area-wrc'
                                );
                                echo form_label('Alamat Perusahaan *');
                                echo form_textarea($alamatusaha_input);
                                ?>
                            </div>
                        </div>
                        <div id="contentright">
                            <div class="contentForm">
                                <b><?php echo form_label('Propinsi '); ?> </b>
                                <?php
                                $opsi_propinsi = array('0' => '-------Pilih data-------');
                                foreach ($list_propinsi as $row) {
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                }

                                if ($propinsi_usaha == " ") {
                                    /*echo form_dropdown('propinsi_usaha', $opsi_propinsi, '0', 'class = "input-select-wrc notSelect" id="propinsi_usaha_id"');*///Edited by Indra
									echo form_dropdown('propinsi_usaha', $opsi_propinsi, '0', 'class = "input-select-wrc" id="propinsi_usaha_id"');
                                } else {
                                    /*echo form_dropdown('propinsi_usaha', $opsi_propinsi, $propinsi_usaha, 'class = "input-select-wrc notSelect" id="propinsi_usaha_id"');*///Edited by Indra
									echo form_dropdown('propinsi_usaha', $opsi_propinsi, $propinsi_usaha, 'class = "input-select-wrc" id="propinsi_usaha_id"');
                                }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                            echo form_label('Kabupaten ');
                            $opsi_kabupaten = array('0' => '-------Pilih data-------');
                            foreach ($list_kabupaten as $row) {
                                $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                            }
                            if ($kabupaten_usaha == NULL) {
                                echo "<div id='show_kabupaten_usaha'>Data Tidak Tersedia</div>";
                            } else {
                                echo "<div id='show_kabupaten_usaha'><input type='hidden' value='" . $kabupaten_usaha . "' name='kabupaten_usaha' />" . $opsi_kabupaten[$kabupaten_usaha] . "</div>";
                            }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                                echo form_label('Kecamatan ');
                                $opsi_kecamatan = array('0' => '-------Pilih data-------');
                                foreach ($list_kecamatan as $row) {
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }
                                if ($kecamatan_usaha == NULL) {
                                    echo "<div id='show_kecamatan_usaha'>Data Tidak Tersedia</div>";
                                } else {
                                    echo "<div id='show_kecamatan_usaha'><input type='hidden' value='" . $kecamatan_usaha . "' name='kecamatan_usaha' />" . $opsi_kecamatan[$kecamatan_usaha] . "</div>";
                                }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                                echo form_label('Kelurahan ');
                                $opsi_kelurahan = array('0' => '-------Pilih data-------');
                                foreach ($list_kelurahan as $row) {
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }
                                if ($kelurahan_usaha == NULL) {
                                    echo "<div id='show_kelurahan_usaha'>Data Tidak Tersedia</div>";
                                } else {
                                    echo "<div id='show_kelurahan_usaha'><input type='hidden' value='" . $kelurahan_usaha . "' name='kelurahan_usaha' />" . $opsi_kelurahan[$kelurahan_usaha] . "</div>";
                                }
                                ?>
                            </div>

                            <div style="clear: both" ></div>

                            <div class="contentForm">
                                <?php
								
                                foreach ($list_kegiatan as $row) {
                                    $opsi_kegiatan[$row->id] = $row->n_kegiatan;
                                }
								array_unshift($opsi_kegiatan,'-Pilih salah satu-');

                                echo form_label('Jenis Kegiatan ');
                                //echo form_dropdown('jenis_kegiatan', $opsi_kegiatan, $jenis_kegiatan, 'class = "input-select-wrc notSelect"');
                                echo form_dropdown('jenis_kegiatan', $opsi_kegiatan, $jenis_kegiatan, 'class = "input-select-wrc"'); //edited by Indra
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                foreach ($list_investasi as $row) {
                                    $opsi_investasi[$row->id] = $row->n_investasi;
                                }
								array_unshift($opsi_investasi,'-Pilih salah satu-');
                                echo form_label('Jenis Investasi ');
                                //echo form_dropdown('jenis_investasi', $opsi_investasi, $jenis_investasi, 'class = "input-select-wrc notSelect"');
                                echo form_dropdown('jenis_investasi', $opsi_investasi, $jenis_investasi, 'class = "input-select-wrc"'); //edited by Indra
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
                                    $show_syarat
                                            ->where('trsyarat_perizinan_id', $data->id)
                                            ->where('trperizinan_id', $jenis_izin->id)->get();
                                    $var = $show_syarat->c_show_type;

//                                $syarat_daftar = new trsyarat_perizinan();
//                                $syarat_daftar->get_by_id($data->id);
//                                $syarat_daftar->trperizinan->include_join_fields()->get();

                                    /*
                                     * Baca beberapa status yang di-parse menjadi biner dan dikonvert jadi
                                     * desimal
                                     */
//                                $var = $syarat_daftar->trperizinan->join_c_show_type;

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

                                    if ($jenis_permohonan->id == 2)
                                        $syarat_status = $c_ubah;
                                    else if ($jenis_permohonan->id == 3)
                                        $syarat_status = $c_perpanjangan;
                                    else if ($jenis_permohonan->id == 4)
                                        $syarat_status = $c_baru;
                                    else
                                        $syarat_status = 0;
                                    if ($syarat_status == '1') {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo $i; ?></td>
                                            <td><?php echo $data->v_syarat; ?></td>
                                            <td align="center">
                                                <?php
                                                if ($save_method === 'update') {
                                                    $checked = FALSE;
                                                    if ($list_daftar) {
                                                        foreach ($list_daftar as $data_daftar) {
                                                            $data_syarat = new tmpermohonan_trsyarat_perizinan();
                                                            $data_syarat->where('tmpermohonan_id', $data_daftar->id)
                                                                    ->where('trsyarat_perizinan_id', $data->id)->get();
                                                            if ($data_syarat->trsyarat_perizinan_id) {
                                                                $checked = TRUE;
                                                                break;
                                                            }
                                                        }
                                                    } else {
                                                        $checked = FALSE;
                                                        break;
                                                    }

                                                    $set = array(
                                                        'name' => 'pemohon_syarat[]',
                                                        'value' => $data->id,
                                                        'checked' => $checked,
                                                        'onClick' => 'cheker()'
                                                    );
                                                    echo form_checkbox($set);
                                                } else {
                                                    $set = array(
                                                        'name' => 'pemohon_syarat[]',
                                                        'value' => $data->id,
                                                        'onClick' => 'cheker()'
                                                    );
                                                    echo form_checkbox($set);
                                                }
                                                ?></td>
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
                    <?php
                }else {
                    ?>
                    <div id="tabs-1"></div>
                    <div id="tabs-2"></div>
                    <div id="tabs-3"></div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="entry" style="text-align: center;">
            <?php
            $add_daftar = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            if ($id_link == "1")
                $link = site_url('pendataan');
            else
                $link = site_url('pendaftaran/index/' . $jenis_permohonan->id);
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . $link . '\''
            );
            if ($id_daftar) {
                echo form_submit($add_daftar);
                echo "<span></span>";
                echo form_button($cancel_daftar);
            }
            echo form_close();
            ?>
            </br>
            <span id="coba"></span>
        </div>
    </div>
    <br style="clear: both;" />
</div>
<!--validasi-->
<script type="text/javascript">
    $.validator.addMethod('notSelect', function(value, element){
        return (value !=0);
    },'Pilih Opsi Yang Tersedia')
</script>