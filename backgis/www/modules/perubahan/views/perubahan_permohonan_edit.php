<script>
    var indexTambahan = $('#tbl_syarat_tambahan > tbody tr').length;
    $(document).ready(function() {
		<?php
		if($disable==1){
		?>
		$('input[type="text"], select, textarea').attr('disabled',true);
		$('input[type="checkbox"]').live('click',function(e){
			e.preventDefault();
		});
		<?php
		}    
		?>

        $('#btnAddSyarat').click(function(e){
            e.preventDefault();
            addSyaratTambahan();
        });

        initDatepickerSyaratTambahan();
    });

    function validasi()
    {
        var tgl1 = document.getElementById('inputTanggal1').value;
        var tgl2 = document.getElementById('inputTanggal2').value;

    //       if(tgl2 < tgl1 && tgl2!=='')
    //        {
    //            //alert('Tanggal Peninjauan tidak boleh lebih kecil dari\ntanggal terima berkas');
    //            $('#coba').html("<p id='eror'>Tanggal Peninjauan tidakan boleh lebih kecil dari tanggal terima berkas</p>");
    //           return false;
    //        }
    //        else
    //        {
                return true;
    //        }

}

     function ceksumber(sumber)
    {
        /*if(sumber=='PASSPORT')
        {*/
                $("input[name=no_refer]").attr("class", 'input-wrc required');
        /*}
        else
        {
            $("input[name=no_refer]").attr("class", 'input-wrc required digits');
        }*/
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
        if(validasi()==false)
        {
                document.forms[0].btn_submit.disabled=true;
        }
        else if(total == jml){
            document.forms[0].btn_submit.disabled=false;
            $('#coba').html('');
            //$('#test').html('');
        }else{
            document.forms[0].btn_submit.disabled=true;
            $('#coba').html("<p id='eror'>* Lengkapi Persyaratan Untuk Mengaktifkan Tombol Simpan</p>");
            
        }        
    }

    /**
     * @author Indra Halim
     * Fungsi untuk menambahkan baris pada Syarat Tambahan
     */
    function addSyaratTambahan(){
        var htmlRow = '';
        var no = $('#tbl_syarat_tambahan > tbody tr').length;
        htmlRow +=
            '<tr style="text-align:center;">'+
                '<td>'+(++no)+'</td>'+
                '<td><input type="text" required name="syarat_tambahan['+indexTambahan+'][v_syarat]" style="width:100%;"/></td>'+
                '<td><input type="text" required name="syarat_tambahan['+indexTambahan+'][no_dokumen]"/></td>'+
                '<td><input type="text" class="tgl-awal-berlaku" required name="syarat_tambahan['+indexTambahan+'][tgl_awal_berlaku]" style="width:80px;"/> - <input type="text" class="tgl-akhir-berlaku" required name="syarat_tambahan['+indexTambahan+'][tgl_akhir_berlaku]"  style="width:80px;"/></td>'+
                '<td><button type="button" class="button-wrc btn-del-tambahan">Hapus</button></td>'
            '</tr>';
        $('#tbl_syarat_tambahan > tbody').append(htmlRow);

        initDatepickerSyaratTambahan();

        indexTambahan++;
    }

    /**
     * @author Indra Halim
     * Fungsi untuk inisialisasi datepicker pada Syarat Tambahan
     */
    function initDatepickerSyaratTambahan(){
        /* BEGIN - Initialize Datepicker untuk Syarat Tambahan */
        var datepickersOpt = {
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            closeText: 'X'
        }

        $('#tbl_syarat_tambahan .tgl-awal-berlaku').datepicker($.extend({
            onSelect: function() {
                var minDate = $(this).datepicker('getDate');
                minDate.setDate(minDate.getDate()+1); //add one day
                $(this).parent().find(".tgl-akhir-berlaku").datepicker( "option", "minDate", minDate);
            }
        },datepickersOpt));

        $('#tbl_syarat_tambahan .tgl-akhir-berlaku').datepicker($.extend({
            onSelect: function() {
                var maxDate = $(this).datepicker('getDate');
                maxDate.setDate(maxDate.getDate()-1);
                $(this).parent().find(".tgl-awal-berlaku").datepicker( "option", "maxDate", maxDate);
            }
        },datepickersOpt));
        /* END - Initialize Datepicker untuk Syarat Tambahan */

        $('.btn-del-tambahan').click(function(e){
            delSyaratTambahan($(this));
        });
    }

    /**
     * @author Indra Halim
     * Fungsi untuk menghapus baris pada Syarat Tambahan
     */
    function delSyaratTambahan(elem){
        $(elem).parent().parent().remove();
    }

    window.onload = cheker;
    $(function() {
        var validator = $('#form').validate();
        var tabs = $( "#tabs" ).tabs({
            select: function(event, ui){
                var valid = true;
                var current = $(this).tabs("option","selected");
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
    #eror1
    {
        color:#FF0000;
        font-weight:bold;
    }

    .field_error
    {
        color:#FF0000;
        position:relative;

        font-size: 9px;
        margin: -4% 0 0 74%;
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
                <?php
                if ($paralel == "no") {
                    if ($jenis_izin->id) {
                        ?>
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Nama Izin', 'nama_izin');
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
                                echo form_label('Kelompok Izin', 'kelompok_izin');
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
                                echo form_label('Jenis Permohonan', 'jenis_permohonan');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo $jenis_permohonan->n_permohonan;
                                ?>
                            </div>
                        </div>
                        <?php
                        if ($save_method == "update") {
                            ?>
                            <div id="statusRail" style="font-weight: bold">
                                <div id="leftRail">
                                    <?php
                                    echo form_label('No Pendaftaran', 'no_daftar');
                                    ?>
                                </div>
                                <div id="rightRail">
                                    <?php
                                    echo $list_daftar->pendaftaran_id;
                                    ?>
                                </div>
                            </div>
                            <?php
                            if ($list_daftar->c_paralel !== '0') {
                                ?>
                                <div id="statusRail">
                                    <div id="leftRail" class="bg-grid">
                                        <?php
                                        echo form_label('Jenis Paralel', 'name_paralel');
                                        ?>
                                    </div>
                                    <div id="rightRail" class="bg-grid">
                                        <?php
                                        echo $jenis_paralel->n_paralel;
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <div class="ContentForm" style="font-weight: bold;text-align: center">
                            Jenis Izin belum dipilih
                            <div style="float: right">
                                <?php
                                $kembali = array(
                                    'name' => 'button',
                                    'class' => 'button-wrc',
                                    'content' => '&laquo; back',
                                    'onclick' => 'parent.location=\'' . site_url('pelayanan/pendaftaran') . '\''
                                );
                                echo form_button($kembali);
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    if ($list_izin_paralel) {
                        ?>
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Jenis Paralel', 'name_paralel');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo $jenis_paralel->n_paralel;
                                ?>
                            </div>
                        </div>
                        <br style="clear: both">
                        <div id="statusRail">
                            <div id="leftRail">
                                <?php
                                echo form_label('Jenis Izin yang dipilih', 'name_izin');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                foreach ($list_izin_paralel as $row) {
                                    $row_izin = new trperizinan();
                                    $row_izin->get_by_id($row);
                                    echo $row_izin->n_perizinan . "<br />";
                                }
                                ?>
                            </div>
                        </div>
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Jenis Permohonan', 'jenis_permohonan');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo $jenis_permohonan->n_permohonan;
                                ?>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="ContentForm" style="font-weight: bold;text-align: center">
                            Izin Paralel belum dipilih
                            <div style="float: right">
                                <?php
                                $kembali = array(
                                    'name' => 'button',
                                    'class' => 'button-wrc',
                                    'content' => '&laquo; back',
                                    'onclick' => 'parent.location=\'' . site_url('pelayanan/pendaftaran') . '\''
                                );
                                echo form_button($kembali);
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </fieldset>
        </div>
        <?php
        if ($eror) {
            echo (" <p id='eror'>$eror</p>");
        }

//     $message = $this->session->flashdata('message');
//     echo $message == '' ? '' : '<div class="message">' . $message . '</div>';
        ?>
        <p id='eror'><span id="test"></span></p>

        <?php
        $attr = array('name' => 'form', 'id' => 'form', 'onsubmit' => 'return validasi()');
        echo form_open('perubahan/perubahan_permohonan/' . $save_method, $attr);
        if ($paralel == "yes") {
            if ($list_izin_paralel) {
                foreach ($list_izin_paralel as $row) {
                    $row_izin = new trperizinan();
                    $row_izin->get_by_id($row);
                    echo form_hidden('list_izin_paralel[]', $row_izin->id);
                }
            }
        }

        echo form_hidden('jenis_permohonan', $mohon);
        echo form_hidden('paralel', $paralel);
        echo form_hidden('jenis_izin', $izin);
        echo form_hidden('trunitkerja_id', $trunitkerja_id);
        echo form_hidden('jenis_izin_id', $jenis_izin->id);
        echo form_hidden('jenis_permohonan_id', $jenis_permohonan->id);
        echo form_hidden('id_daftar', $id_daftar);
        echo form_hidden('id_link', $id_link);
        echo form_hidden('waktu_awal', $waktu_awal);
        echo form_hidden('eror', 'halo');
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
<!--                    <li><a href="#tabs-4">Persyaratan Tambahan</a></li>-->
                </ul>
                <?php
                if ($paralel == "yes")
                    $real_id = $list_izin_paralel;
                else
                    $real_id = $jenis_izin->id;
                if ($real_id) {
                    ?>

                    <!-- ------------------------------TABS 1---------------------- -->
                    <div id="tabs-1">
                        <div id="contentleft">


                            <?php
                            if ($save_method !== "update") {
                                ?>
                                <div class="contentForm">
                                    <?php
                                    echo form_label('');
                                    echo anchor(site_url('pelayanan/pendaftaran/daftar_izin_list'), 'Ambil Data Pemohon Izin', 'class="link-wrc" rel="daftar_box"');
                                    ?>
                                </div>
                                <br style="clear: both" />
                                <?php
                            }
                            ?>
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
                                if ($save_method == "save") {
                                    $id = 'no_refer';
                                } else {
                                    $id = 'no_refer2';
                                }
                                $norefer_input = array(
                                    'name' => 'no_refer',
                                    'value' => $no_refer,
                                    //'class' => 'input-wrc required digits',
                                    'class' => 'input-wrc required',
                                    'onkeyup'=>'ceksumber(this.form.cmbsource.value);return false;',
                                    'id' => $id
                                );
                                echo '<b>' . form_label('ID') . '</b>';
                                echo form_input($norefer_input).'<span id="alert" style="color: red"></span>';
                                
                                if ($statusOnline2 == "1") {
                                    ?>
                                    <p id='eror1'><span id="error_id"></span></p>
                                    <br>

                                    <input type="button" onclick="show_ktp(this.form)" value="Cek Id/KTP" class="button-wrc" >
                                <?php } ?>
                            </div>
                            <?php
                            if ($save_method == "update") {
                                echo ("<br>");
                            }
                            ?>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <?php
                                $namapemohon_input = array(
                                    'name' => 'nama_pemohon',
                                    'value' => $nama_pemohon,
                                    'class' => 'input-wrc required'
                                );
                                echo '<b>' . form_label('Nama Pemohon ') . '</b>';
                                echo form_input($namapemohon_input);
                                echo form_error('nama_pemohon', '<div class="field_error">', '</div>');
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $notelp_input = array(
                                    'name' => 'no_telp',
                                    'value' => $no_telp,
                                    'class' => 'input-wrc digits required'
                                );
                                echo '<b>' . form_label('No Telp/HP ') . '</b>';
                                echo form_input($notelp_input);
                                echo form_error('no_telp', '<div class="field_error">', '</div>');
                                ?>
                            </div>

                            <div class="contentForm">
                                <?php
                                $email_input = array(
                                    'name' => 'email_pemohon',
                                    'value' => $email_pemohon,
                                    'class' => 'input-wrc email required'
                                );
                                echo '<b>' . form_label('Alamat Email') . '</b>';
                                echo form_input($email_input);
                                echo form_error('email_pemohon', '<div class="field_error">', '</div>');
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
                                    'class' => 'input-wrc',
                                    'readOnly' => TRUE,
                                    'id' => 'inputTanggal2'
                                    
                                );
                                echo '<b>' . form_label('Tgl Peninjauan') . '</b>';
                                echo form_input($tglsurvey_input);
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
                                $lokasi_input = array(
                                    'name' => 'lokasi_izin',
                                    'value' => $lokasi_izin,
                                    'class' => 'input-area-wrc required'
                                );
                                echo '<b>' . form_label('Lokasi Izin') . '</b>';
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
                                echo '<b>' . form_label('Keterangan') . '</b>';
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

                                if ($propinsi_pemohon == " ") {
                                    echo form_dropdown('propinsi_pemohon', $opsi_propinsi, '0', 'class = "input-select-wrc notSelect" id="propinsi_pemohon_id"');
                                } else {
                                    echo form_dropdown('propinsi_pemohon', $opsi_propinsi, $propinsi_pemohon, 'class = "input-select-wrc notSelect" id="propinsi_pemohon_id"');
                                }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                            echo form_label('Kabupaten ');
                            /*$opsi_kabupaten = array('0' => '-------Pilih data-------');
                            foreach ($list_kabupaten as $row) {
                                $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                            }*/
                            if ($kabupaten_pemohon == NULL) {
                                echo "<div id='show_kabupaten_pemohon'>Data Tidak Tersedia</div>";
                            } else {
                                echo "<div id='show_kabupaten_pemohon'><input type='hidden' value='" . $kabupaten_pemohon . "' name='kabupaten_pemohon' />" . $nama_kabupaten_pemohon . "</div>";
                            }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                                echo form_label('Kecamatan ');
                                /*$opsi_kecamatan = array('0' => '-------Pilih data-------');
                                foreach ($list_kecamatan as $row) {
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }*/
                                if ($kecamatan_pemohon == NULL) {
                                    echo "<div id='show_kecamatan_pemohon'>Data Tidak Tersedia</div>";
                                } else {
                                    echo "<div id='show_kecamatan_pemohon'><input type='hidden' value='" . $kecamatan_pemohon . "' name='kecamatan_pemohon' />" . $nama_kecamatan_pemohon . "</div>";
                                }
                                ?>
                            </div>
                            <div style="clear: both" ></div>
                            <div class="contentForm">
                                <b><?php
                                echo form_label('Kelurahan ');
                                /*$opsi_kelurahan = array('0' => '-------Pilih data-------');
                                foreach ($list_kelurahan as $row) {
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }*/
                                if ($kelurahan_pemohon == NULL) {
                                    echo "<div id='show_kelurahan_pemohon'>Data Tidak Tersedia</div>";
                                } else {
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
                                    'class' => 'input-area-wrc required'
                                );
                                echo form_label('Alamat Pemohon ');
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
                                echo form_label('Alamat Pemohon<br />di Luar Negeri<br />(isikan jika ada)');
                                echo form_textarea($alamatdataluar_input);
                                ?>
                            </div>
                        </div>

                        <br style="clear: both;" />
                    </div>

                    <!-- ------------------------------TABS 2---------------------- -->

                    <div id="tabs-2">
                        <?php
                        if ($save_method !== "update") {
                            ?>
                            <div class="contentForm">
                                <?php
                                echo form_label('');
                                echo anchor(base_url() . 'pelayanan/pendaftaran/daftar_perusahaan_list', 'Ambil Data Perusahaan', 'class="link-wrc" rel="perusahaan_box"');
                                ?>
                                
                            </div>
                            <br style="clear: both" />
                            <?php
                        }
                        ?>
                        <div id="contentleft">
                            <div class="contentForm">
                                <?php
                                if ($save_method == "save") {
                                    $id = 'npwp';
                                } else {
                                    $id = 'npwp2';
                                }

                                $npwp_input = array(
                                    'name' => 'npwp',
                                    'id' => 'npwp',
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
                            <div class="contentForm">
                                <?php
                                $telp_input = array(
                                    'name' => 'telp_perusahaan',
                                    'value' => $telp_perusahaan,
                                    'class' => 'input-wrc',
                                );

                                echo form_label('Telp Perusahaan ');
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

                                echo form_label('Fax');
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

                                echo form_label('Email');
                                echo form_input($email_input);
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
								
								$propinsi_usaha=2; // SANI: kode provinsi Sumatera Utara
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
                            echo form_label('Kabupaten ');
                            $opsi_kabupaten = array('0' => '-------Pilih data-------');
                            foreach ($list_kabupaten as $row) {
                                $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                            }
							
							
                            if ($kabupaten_usaha == NULL) {
                                echo "<div id='show_kabupaten_usaha'>Data Tidak Tersedia</div>";
                            } else {
                                echo "<div id='show_kabupaten_usaha'><input type='hidden' value='" . $kabupaten_usaha . "' name='kabupaten_usaha' />" . $nama_kabupaten_perusahaan . "</div>";
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
                                    echo "<div id='show_kecamatan_usaha'><input type='hidden' value='" . $kecamatan_usaha . "' name='kecamatan_usaha' />" . $nama_kecamatan_perusahaan . "</div>";
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
                                    'class' => 'input-area-wrc',
                                );

                                echo form_label('Alamat Perusahaan ');
                                echo form_textarea($alamatusaha_input);
                                echo form_error('alamat_usaha', '<div class="field_error">', '</div>');
                                ?>
                            </div>
                            <div class="contentForm">
                                <?php
//                                $rt_input = array(
//                                    'name' => 'rt',
//                                    'value' => $rt,
//                                    'class' => 'input-wrc',
//                                );
//                                if($statusOnline == "1"){
//                                   $rt_input['readonly'] = "readonly";
//                                }
//                                echo form_label('RT');
//                                echo form_input($rt_input);
                                foreach ($list_kegiatan as $row) {
//                                    $opsi_kegiatan[' '] = "------Pilih salah satu------";
                                    $opsi_kegiatan[$row->id] = $row->n_kegiatan.'-'.$row->keterangan;
                                }

//                                echo form_label('Jenis Kegiatan ');
                                echo form_label('Kode Bidang Usaha ');
                                if ($jenis_kegiatan == "ok") {
                                    echo form_dropdown('jenis_kegiatan[]', $opsi_kegiatan, ' ', 'class = "input-select-wrc" id="jenis_kegiatan" style="width:300px" multiple="multiple"');
                                } else {
                                    echo form_dropdown('jenis_kegiatan[]', $opsi_kegiatan, $jenis_kegiatan, 'class = "input-select-wrc" id="jenis_kegiatan" style="width:300px" multiple="multiple"');
                                }
                                echo form_error('jenis_kegiatan', '<div class="field_error">', '</div>');
                                ?>
                                <p id="erorJ_kegiatan" align="right" style="visibility: hidden;"></p>
                            </div>
                            <div class="contentForm">
                                <?php
//                                $rw_input = array(
//                                    'name' => 'rw',
//                                    'value' => $rw,
//                                    'class' => 'input-wrc',
//                                );
//                                if($statusOnline == "1"){
//                                   $rw_input['readonly'] = "readonly";
//                                }
//                                echo form_label('RW');
//                                echo form_input($rw_input);
                                $opsi_investasi = array();
                                foreach ($list_investasi as $row) {
//                                    $opsi_investasi[' '] = "------Pilih salah satu------";
                                    $opsi_investasi[$row->id] = $row->n_investasi.'-'.$row->keterangan;
                                }

//                                echo form_label('Jenis Investasi ');
                                echo form_label('Jenis Produksi/Jasa ');
                                if ($jenis_investasi == "ok") {
                                    echo form_dropdown('jenis_investasi[]', $opsi_investasi, ' ', 'class = "input-select-wrc" id="jenis_investasi" style="width:300px" multiple="multiple"');
                                } else {
                                    echo form_dropdown('jenis_investasi[]', $opsi_investasi, $jenis_investasi, 'class = "input-select-wrc" id="jenis_investasi" style="width:300px" multiple="multiple"');
                                }
                                echo form_error('jenis_investasi', '<div class="field_error">', '</div>');
                                ?>
                                <p id="erorJ_investasi" align="right" style="visibility: hidden;"></p>
                            </div>
                        </div>
                        <br style="clear: both;" />
                    </div>
                    <div id="tabs-3">
                        <table cellpadding="0" cellspacing="0" border="1" class="display">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="50%">Syarat</th>
                                    <th width="5%" style="text-align: center;padding-left:0px;">Terpenuhi</th>
                                    <th width="5%">No Dokumen</th>
                                    <th width="20%">Masa Berlaku</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if ($paralel == "no") {
                                    foreach ($syarat_izin as $keySyarat=>$data) {
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
                                                                    $checked = TRUE;
                                                                    break;
                                                                } else {
                                                                    $checked = FALSE;
                                                                }
                                                            }
                                                        } else {
                                                            $checked = FALSE;
                                                            /*if ($list_daftar) {
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
                                                            }*/
                                                            if(isset($existingSyaratPerizinan[$data->id])){//Jika ada data Syarat dengan Key trsyarat_perizinan_id
                                                                $checked = true;
                                                            }
                                                        }

                                                        $set = array(
                                                            'name' => 'pemohon_syarat[]',
                                                            'id' => 'chek',
                                                            'value' => $data->id,
                                                            'checked' => $checked,
                                                            'onClick' => 'cheker()',
                                                            'class'=>'check-terpenuhi',
                                                        );
                                                        echo form_checkbox($set);
                                                    } else {
                                                        if (isset($check) && !empty($check)) {
                                                            foreach ($check as $dt) {
                                                                if ($dt == $data->id) {
                                                                    $checked = TRUE;
                                                                    break;
                                                                } else {
                                                                    $checked = FALSE;
                                                                }
                                                            }
                                                        } else {
                                                            $checked = FALSE;
                                                        }
                                                        $set = array(
                                                            'name' => 'pemohon_syarat[]',
                                                            'value' => $data->id,
                                                            'checked' => $checked,
                                                            'onClick' => 'cheker()',
                                                            'class'=>'check-terpenuhi',
                                                            'id' => 'chek'
                                                        );
                                                        echo form_checkbox($set);
                                                    }
                                                    ?></td>
                                                <td align="center">
                                                    <?php
                                                    $txtNoDokumen = array(
                                                        'name' => 'no_dokumen['.$keySyarat.']',
                                                        'type'=>'text',
                                                        'id' => 'no_dokumen_'.$keySyarat,
                                                        'class'=>'no-dokumen'
                                                    );

                                                    if ($save_method === 'update') {
                                                        if(isset($existingSyaratPerizinan[$data->id])){//Jika ada data Syarat dengan Key trsyarat_perizinan_id
                                                            $txtNoDokumen['value'] = $existingSyaratPerizinan[$data->id]['no_dokumen'];
                                                        }else{
                                                            $txtNoDokumen['disabled'] = 'disabled';
                                                        }
                                                    }else{
                                                        $txtNoDokumen['disabled'] = 'disabled';
                                                    }

                                                    echo form_input($txtNoDokumen);
                                                    ?>
                                                </td>
                                                <td align="center">
                                                    <?php
                                                    $txtAwalBerlaku = array(
                                                        'name' => 'tgl_awal_berlaku['.$keySyarat.']',
                                                        'type'=>'text',
                                                        'id' => 'tgl_awal_berlaku'.$keySyarat,
                                                        'style'=>'width:80px;',
                                                        'class'=>'tgl-awal-berlaku',
                                                    );
                                                    $txtAkhirBerlaku = array(
                                                        'name' => 'tgl_akhir_berlaku['.$keySyarat.']',
                                                        'type'=>'text',
                                                        'id' => 'tgl_akhir_berlaku'.$keySyarat,
                                                        'style'=>'width:80px;',
                                                        'class'=>'tgl-akhir-berlaku',
                                                    );

                                                    if ($save_method === 'update') {
                                                        if(isset($existingSyaratPerizinan[$data->id])){//Jika ada data Syarat dengan Key trsyarat_perizinan_id
                                                            $txtAwalBerlaku['value'] = $existingSyaratPerizinan[$data->id]['tgl_awal_berlaku'];
                                                            $txtAkhirBerlaku['value'] = $existingSyaratPerizinan[$data->id]['tgl_awal_berlaku'];
                                                        }else{
                                                            $txtAwalBerlaku['disabled'] = 'disabled';
                                                            $txtAkhirBerlaku['disabled'] = 'disabled';
                                                        }
                                                    }else{
                                                        $txtAwalBerlaku['disabled'] = 'disabled';
                                                        $txtAkhirBerlaku['disabled'] = 'disabled';
                                                    }

                                                    echo form_input($txtAwalBerlaku);
                                                    echo ' - ';
                                                    echo form_input($txtAkhirBerlaku);
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
                                }else {
                                    $x = 1;
                                    $data_izin = 0;
                                    if ($list_izin_paralel) {
                                        foreach ($list_izin_paralel as $row) {
                                            $row_izin = new trperizinan();
                                            $row_izin->get_by_id($row);
                                            if ($x == 1)
                                                $data_izin = $row_izin->id;
                                            else
                                                $data_izin = $data_izin . ", " . $row_izin->id;
                                            $x++;
                                        }
//                            foreach ($jenis_izin as $row){
//                                if($x == 1) $data_izin = $row->id;
//                                else $data_izin = $data_izin.", ".$row->id;
//                                $x++;
//                            }
//                            $query = "select distinct(trsyarat_perizinan_id)
//                                from trperizinan_trsyarat_perizinan
//                                where trperizinan_id IN(".$data_izin.") ";
                                        $query = "select distinct(A.trsyarat_perizinan_id), B.v_syarat, B.status
                                                    from trperizinan_trsyarat_perizinan as A,
                                                    trsyarat_perizinan as B
                                                    where A.trperizinan_id IN(" . $data_izin . ")
                                                    and A.trsyarat_perizinan_id = B.id
                                                    order by B.status, B.v_syarat ";
                                        $results = mysql_query($query);
                                        $keySyarat = 0;
                                        while ($rows = mysql_fetch_assoc(@$results)) {
                                            $syarat_daftar = new trsyarat_perizinan();
                                            $syarat_daftar->get_by_id($rows['trsyarat_perizinan_id']);
//                                $syarat_daftar->trperizinan->include_join_fields()->get();

                                            /*
                                             * Baca beberapa status yang di-parse menjadi biner dan dikonvert jadi
                                             * desimal
                                             */
//                                $var = $syarat_daftar->trperizinan->join_c_show_type;
                                            $show_syarat = new trperizinan_syarat();
                                            $show_syarat
                                                    ->where('trsyarat_perizinan_id', $rows['trsyarat_perizinan_id'])->get();
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
//                                $syarat_perizinan = new trsyarat_perizinan();
//                                $data = $syarat_perizinan->get_by_id($rows['trsyarat_perizinan_id']);
                                                ?>
                                                <tr>
                                                    <td align="center"><?php echo $i; ?></td>
                                                    <td><?php echo $syarat_daftar->v_syarat; ?></td>
                                                    <td align="center">
                                                        <?php
                                                        $set = array(
                                                            'name' => 'pemohon_syarat[]',
                                                            'value' => $syarat_daftar->id,
                                                            'onClick' => 'cheker()',
                                                            'class'=>'check-terpenuhi',
                                                        );
                                                        echo form_checkbox($set);
                                                        ?></td>
                                                    <td align="center">
                                                        <?php
                                                        $txtNoDokumen = array(
                                                            'name' => 'no_dokumen['.$keySyarat.']',
                                                            'type'=>'text',
                                                            'id' => 'no_dokumen_'.$keySyarat,
                                                            'class'=>'no-dokumen'
                                                        );

                                                        if ($save_method === 'update') {
                                                            if(isset($existingSyaratPerizinan[$data->id])){//Jika ada data Syarat dengan Key trsyarat_perizinan_id
                                                                $txtNoDokumen['value'] = $existingSyaratPerizinan[$data->id]['no_dokumen'];
                                                            }else{
                                                                $txtNoDokumen['disabled'] = 'disabled';
                                                            }
                                                        }else{
                                                            $txtNoDokumen['disabled'] = 'disabled';
                                                        }

                                                        echo form_input($txtNoDokumen);
                                                        ?>
                                                    </td>
                                                    <td align="center">
                                                        <?php
                                                        $txtAwalBerlaku = array(
                                                            'name' => 'tgl_awal_berlaku['.$keySyarat.']',
                                                            'type'=>'text',
                                                            'id' => 'tgl_awal_berlaku'.$keySyarat,
                                                            'style'=>'width:80px;',
                                                            'class'=>'tgl-awal-berlaku',
                                                        );
                                                        $txtAkhirBerlaku = array(
                                                            'name' => 'tgl_akhir_berlaku['.$keySyarat.']',
                                                            'type'=>'text',
                                                            'id' => 'tgl_akhir_berlaku'.$keySyarat,
                                                            'style'=>'width:80px;',
                                                            'class'=>'tgl-akhir-berlaku',
                                                        );

                                                        if ($save_method === 'update') {
                                                            if(isset($existingSyaratPerizinan[$data->id])){//Jika ada data Syarat dengan Key trsyarat_perizinan_id
                                                                $txtAwalBerlaku['value'] = $existingSyaratPerizinan[$data->id]['tgl_awal_berlaku'];
                                                                $txtAkhirBerlaku['value'] = $existingSyaratPerizinan[$data->id]['tgl_awal_berlaku'];
                                                            }else{
                                                                $txtAwalBerlaku['disabled'] = 'disabled';
                                                                $txtAkhirBerlaku['disabled'] = 'disabled';
                                                            }
                                                        }else{
                                                            $txtAwalBerlaku['disabled'] = 'disabled';
                                                            $txtAkhirBerlaku['disabled'] = 'disabled';
                                                        }

                                                        echo form_input($txtAwalBerlaku);
                                                        echo ' - ';
                                                        echo form_input($txtAkhirBerlaku);
                                                        ?>
                                                    </td>
                                                    <td align="center">
                                                        <?php
                                                        if ($syarat_daftar->status == "1")
                                                            $status_data = "Wajib";
                                                        else
                                                            $status_data = "Tidak Wajib";
                                                        echo form_label($status_data);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            $keySyarat++;
                                        }
                                    }
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    <!--- BEGIN - Tab 4 --->
                    <!--<div id="tabs-4">
                        <button type="button" name="btnAddSyarat" id="btnAddSyarat" class="button-wrc">Tambah</button>
                        <table cellpadding="0" cellspacing="0" border="1" class="display" id="tbl_syarat_tambahan">
                            <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="55%">Syarat</th>
                                <th width="15%">No Dokumen</th>
                                <th width="20%">Masa Berlaku</th>
                                <th width="5%">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
/*                            if(isset($existingSyaratTambahan) && is_array($existingSyaratTambahan)){
                                foreach($existingSyaratTambahan as $indexTambahan=>$valTambahan){
                                    echo '<tr style="text-align:center;">';
                                    echo '<td>'.($indexTambahan+1).'</td>';
                                    echo '<td><input type="text" style="width:100%;" name="syarat_tambahan['.$indexTambahan.'][v_syarat]" required value="'.$valTambahan['v_syarat'].'"></td>';
                                    echo '<td><input type="text" name="syarat_tambahan['.$indexTambahan.'][no_dokumen]" required value="'.$valTambahan['no_dokumen'].'"></td>';
                                    echo '<td>';
                                    echo '<input type="text" style="width:80px;" name="syarat_tambahan['.$indexTambahan.'][tgl_awal_berlaku]" required value="'.$valTambahan['tgl_awal_berlaku'].'">';
                                    echo ' - ';
                                    echo '<input type="text" style="width:80px;" name="syarat_tambahan['.$indexTambahan.'][tgl_akhir_berlaku]" required value="'.$valTambahan['tgl_akhir_berlaku'].'">';
                                    echo '</td>';
                                    echo '<td><button class="button-wrc btn-del-tambahan" type="button">Hapus</button></td>';
                                    echo '</tr>';
                                }
                            }
                            */?>
                            </tbody>
                        </table>
                    </div>-->
                    <!--- END - Tab 4 --->

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

                    <?php
                }else {
                    ?>
                    <div id="tabs-1" ></div>
                    <div id="tabs-2" ></div>
                    <div id="tabs-3" ></div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="entry" style="text-align: center;">

            <?php
			
            
			$add_daftar = array(
                'name' => 'btn_submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
	        );
			
            if ($id_link == "1")
                $link = site_url('pendataan');
            else
                $link = site_url('pelayanan/pendaftaran');
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . $link . '\''
            );
            if ($save_method !== "update") {
                if ($paralel == "no") {
                    if ($jenis_izin->id){
						if($disable!=1){
	                        echo form_submit($add_daftar);
	                    }
					}
					echo "<span></span>";
                    echo form_button($cancel_daftar);
                }else {
                    if ($list_izin_paralel) {
            			if($disable!=1){
			            	echo form_submit($add_daftar);
                        }
						echo "<span></span>";
                        echo form_button($cancel_daftar);
                    }
                }
            } else {
                if ($jenis_izin->id){
					if($disable!=1){
						echo form_submit($add_daftar);
               		}
				}
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
    },'Pilih Opsi Yang Tersedia');
    
    
var site = '<?php echo base_url(); ?>';
<?php
if ($save_method == "save") {
?>
   $("#form").validate({
        onkeyup: false,        
         rules:{
            no_refer:{
                remote:{
                    url: site + "pemohon/register_id_exist",
                    type:"post",
                    data:{
                        no_refer: function(){
                            return $("#no_refer").val();
                        }
                    }
                }
            },
            npwp:{
                remote:{
                    url: site + "perusahaan/register_npwp_exist",
                    type:"post",
                    data:{
                        npwp: function(){
                            return $("#npwp").val();
                        }
                    }
                }
            }
        }
        ,
        messages:{
            no_refer:{
                remote:'No referensi sudah digunakan!'
            }
            ,
            npwp:{
                remote:'No NPWP sudah digunakan!'
            }
        }
    });
   <?php } ?>
</script>