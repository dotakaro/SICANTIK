<style>
    .read_only{
        border: 1px solid black;
}
#no_refer1{
    background: #DDDFFD;
}
#namapemohon_input{
    background: #DDDFFD;
}
#cmbsource{
    background: #DDDFFD;
}
</style>
<script language="javascript" type="text/javascript">
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
        $.post('<?php echo base_url(); ?>pelayanan/pendaftaran/pick_penduduk_data', {
            data_no_refer: $('#no_refer1').val()
        }, function(response){
            setTimeout("finishAjax('tabs-1', '"+escape(response)+"')", 400);
        });
        return false;
    }

    function clear_data() {
        $.post('<?php echo base_url(); ?>pelayanan/pendaftaran/pick_penduduk_data', {
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

    function Check(){
        if(document.form.Check_ctr.checked == true){
            document.form.propinsi_pemohon.disabled = false ;
            document.form.kabupaten_pemohon.disabled = false ;
            document.form.kecamatan_pemohon.disabled = false ;
            document.form.kelurahan_pemohon.disabled = false ;
        }else{
            document.form.propinsi_pemohon.disabled = true ;
            document.form.kabupaten_pemohon.disabled = true ;
            document.form.kecamatan_pemohon.disabled = true ;
            document.form.kelurahan_pemohon.disabled = true ;
        }
    }


</script>
<div id="tabs-1">

    <div id="contentleft">
        <?php
        if ($status == "pemohon")
            echo form_hidden('id_pemohon', $id_pemohon);
        ?>
        <div class="contentForm">
            <?php
            echo form_label('');
            //echo anchor(site_url('pelayanan/pendaftaran/pick_pemohon_list'), 'Cari Data Pemohon', 'class="link-wrc" rel="pemohon_box"');
            ?>
            <?php
            echo anchor(site_url('pelayanan/pendaftaran/daftar_izin_list'), 'Ambil Data Pemohon Izin', 'class="link-wrc" rel="daftar_box"');
            ?>
        </div><br  style="clear: both" /><br  style="clear: both" />
         <div style="clear: both" ></div>
        <div class="contentForm">
                            <?php
                            $data = array('KTP' => 'KTP','SIM' => "SIM",'PASSPORT' => 'PASSPORT');
                            echo '<b>' .form_label('Sumber Identitas') . '</b>';
                            if($cmbsource!=NULL)
                            {
                                    echo form_dropdown('cmbsource',$data,$cmbsource,'class = "input-select-wrc" id="cmbsource" readonly => "readonly"  onChange=" ceksumber(this.value);return false;" ');
                            }
                            else
                            {
                                    echo form_dropdown('cmbsource',$data,'0','class = "input-select-wrc" id="cmbsource" readonly => "readonly" onChange=" ceksumber(this.value);return false;" ');
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
                'id' => 'no_refer1',
                'readonly' => "readonly"
            );
            echo '<b>' . form_label('ID') . '</b>';
            echo form_input($norefer_input);
            //echo form_hidden('no_refer', $no_refer);
            echo form_error('no_refer', '<div class="field_error">', '</div>');
            if ($statusOnline2 == "1") {
                ?>
                <br>

    <!--               <input type="hidden" id="clear_id" name="clear_id" value="wrc">-->
                <input type="button" onclick="show_ktp()" value="Cek Id/KTP" class="button-wrc" >
    <!--               <input type="button" onclick="clear_data()" value="Clear Data" class="button-wrc">-->
<?php } ?>

        </div>
        <div style="clear: both" ></div>
        <div class="contentForm">
            <?php
            $namapemohon_input = array(
                'name' => 'nama_pemohon',
                'value' => $nama_pemohon,
                'class' => 'input-wrc required',
                'disabled' => true,
                'id' =>"namapemohon_input"
            );
            echo '<b>' .form_label('Nama Pemohon ').'<b>';
            echo form_input($namapemohon_input);
            echo form_hidden('nama_pemohon', $nama_pemohon);
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
            echo '<b>' .form_label('No Telp/HP ').'<b>';
            echo form_input($notelp_input);
            echo form_error('no_telp', '<div class="field_error">', '</div>');
            ?>
        </div>
        <div class="contentForm">
            <?php
            $email_input = array(
                'name' => 'email_pemohon',
                'value' => $email_pemohon,
                'class' => 'input-wrc required email'
            );
            echo '<b>' .form_label('Alamat Email').'<b>';
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
                'id' => 'inputTanggal1'
            );
            echo '<b>' .form_label('Tgl Terima Berkas ').'<b>';
            echo form_input($tgldaftar_input);
            echo form_error('tgl_daftar', '<div class="field_error">', '</div>');
            ?>
        </div>
        <div class="contentForm">
            <?php
            $tglsurvey_input = array(
                'name' => 'tgl_survey',
                'value' => '',
                'class' => 'input-wrc',
                'id' => 'inputTanggal2'
            );
            echo '<b>' .form_label('Tgl Peninjauan').'<b>';
            echo form_input($tglsurvey_input);
            ?>
        </div>
        <div class="contentForm">
            <?php
            $lokasi_input = array(
                'name' => 'lokasi_izin',
                'value' => '',
                'class' => 'input-area-wrc'
            );
            echo '<b>' .form_label('Lokasi Izin').'<b>';
            echo form_textarea($lokasi_input);
            ?>
        </div>
        <div class="contentForm">
            <?php
            $ket_input = array(
                'name' => 'keterangan',
                //'value' => $keterangan,
                'class' => 'input-area-wrc'
            );
            echo '<b>' .form_label('Keterangan').'<b>';
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
                /*$opsi_kecamatan = array('0' => '-------Pilih data-------');
                foreach ($list_kecamatan as $row) {
                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                }*/
                if ($kecamatan_pemohon == NULL) {
                    echo "<div id='show_kecamatan_pemohon'>Data Tidak Tersedia</div>";
                } else {
                    echo "<div id='show_kecamatan_pemohon'><input type='hidden' value='" . $kecamatan_pemohon . "' name='kecamatan_pemohon' />" . $nama_kecamatan . "</div>";
                }
            ?>
        </div>
        <div style="clear: both" ></div>
        <div class="conpetentForm">
            <b><?php
                echo form_label('Kelurahan ');
                /*$opsi_kelurahan = array('0' => '-------Pilih data-------');
                foreach ($list_kelurahan as $row) {
                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                }*/
                if ($kelurahan_pemohon == NULL) {
                    echo "<div id='show_kelurahan_pemohon'>Data Tidak Tersedia</div>";
                } else {
                    echo "<div id='show_kelurahan_pemohon'><input type='hidden' value='" . $kelurahan_pemohon . "' name='kelurahan_pemohon' />" . $nama_kelurahan . "</div>";
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