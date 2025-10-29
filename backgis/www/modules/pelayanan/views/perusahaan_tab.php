<style>
    #npwp_id{
        background: #DDDFFD;
}
#nodaftar{
        background: #DDDFFD;
}
#namaperusahaan_input{
        background: #DDDFFD;
}

</style>
<script language="javascript" type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";
    $(document).ready(function() {
        $('a[rel*=perusahaan_box]').facebox();
    } );

    function show_npwp(form) {
        var reg = form.nodaftar.value;
        var npwp = form.npwp_id.value;
        if (npwp.length==0)
        {
            alert('Npwp harus diisi');
            return false;
        }
        else if (reg.length==0)
        {
            alert('No daftar Harus diisi');
            return false;
        }
        else
        {        
            $.post(base_url + 'pelayanan/pendaftaran/pick_perusahaan_data/'+reg, {
                data_npwp_id: $('#npwp_id').val()
            }, function(response){
                setTimeout("finishAjax('tabs-2', '"+escape(response)+"')", 400);
            });
            return false;
        }
    }

    function clear_data() {
        var reg = "0909";
        $.post(base_url + 'pelayanan/pendaftaran/pick_perusahaan_data/'+reg, {
            data_npwp_id: $('#clear_id').val()
        }, function(response){
            setTimeout("finishAjax('tabs-2', '"+escape(response)+"')", 400);
        });
        return false;
    }

    $(document).ready(function() {
        $('#propinsi_usaha_id').change(function(){
            $.post('<?php echo base_url(); ?>pelayanan/pendaftaran/kabupaten_usaha', { propinsi_id: $('#propinsi_usaha_id').val() },
            function(data) {
                $('#show_kabupaten_usaha').html(data);
                $('#show_kecamatan_usaha').html('Data Tidak tersedia');
                $('#show_kelurahan_usaha').html('Data Tidak tersedia');
            });
        });

        $('#jenis_kegiatan').multiselect({
            show:'blind',
            hide:'blind',
            multiple: true,
            header: 'Pilih Kode Bidang Usaha',
            noneSelectedText: 'Pilih Kode Bidang Usaha',
            selectedList: 1
        }).multiselectfilter();

        $('#jenis_investasi').multiselect({
            show:'blind',
            hide:'blind',
            multiple: true,
            header: 'Pilih Jenis Produksi/Jasa',
            noneSelectedText: 'Pilih Jenis Produksi/Jasa',
            selectedList: 1
        }).multiselectfilter();

        $('#jenis_kegiatan').change(function(){
            var selectedKegiatan = $(this).val();
            //ambil unit melalui ajax
            $.ajax({
                url:'<?php echo site_url('pelayanan/pendaftaran/ajax_get_jenis_investasi');?>',
                type:'POST',
                dataType:'json',
                data:{trkegiatan_id : selectedKegiatan},
                success:function(r){
                    var selectOption = '';
                    $.each(r,function(key,val){
                        selectOption += '<option value=\"'+val.id+'\">'+val.n_investasi+'-'+val.keterangan+'</option>';
                    });
                    $('#jenis_investasi').html(selectOption);
                    $('#jenis_investasi').multiselect('refresh');
                }
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
        padding-bottom: 1%;

        text-align: left;


    }

</style>

<div id="tabs-2">
    <div class="contentForm">
        <?php
        if (!$id_perusahaan) {
            $settings = new settings();
            $app_web_service = $settings->where('name', 'app_web_service')->get();
            $url = $app_web_service->value;
            $handle = @fopen($url, 'r');

            if ($handle !== false) {
                $npwp = $_REQUEST['data_npwp_id'];
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

                $data_url = $url . '/' . $_REQUEST['data_npwp_id'] . '/' . $registrasi;
                $data_xml = $this->curl->simple_get($data_url, array(CURLOPT_PORT => 8080));
                $data_xml = simplexml_load_string($data_xml);

                foreach ($data_xml as $xml) {
                    $id_perusahaan = '';
                    $npwp = $xml->npwp;
                    $nodaftar = $xml->nodaftar;
                    $nama_perusahaan = $xml->nama_perusahaan;
                    $alamat_usaha = $xml->alamat_usaha;
                    $rt = $xml->rt;
                    $rw = $xml->rw;
                    $telp_perusahaan = $xml->telp_perusahaan;
                    $fax = $xml->fax;
                    $email = $xml->email;
                    //                $getdata = $xml->getdata;
                }
                if ($_REQUEST['data_npwp_id'] == " ") {
                    echo " ";
                } else {
                    if ($npwp == "") {
                        echo "<div class='eror'>Npwp / No Registrasi tidak ditemukan... </div>";
                    }
                }
            } else {
                echo "<b style='color: #FF0000;'>Aplikasi Pajak Belum Online!</b>";
                $id_perusahaan = '';
                $npwp = "";
                $nodaftar = "";
                $nama_perusahaan = "";
                $alamat_usaha = "";
                $rt = "";
                $rw = "";
                $telp_perusahaan = "";
                $fax = "";
                $email = "";
            }

            if (!$npwp) {
                $npwp_input = array(
                    'id' => 'npwp_id',
                    'name' => 'npwp',
                    'value' => $npwp,
                    'class' => 'input-wrc required'
                );
            } else {
                echo '<input type="hidden" id="clear_id" name="clear_id" value=" ">';
               // echo form_hidden('id_perusahaan', $id_perusahaan);
                $npwp_input = array(
                    'id' => 'npwp_id',
                    'name' => 'npwp',
                    'value' => $npwp,
                    'class' => 'input-wrc required',
                    'readonly' => "readonly"
                );
            }
        }
        ?>

        <div class="contentForm">
        <?php
        echo form_label('');
        echo anchor(base_url() . 'pelayanan/pendaftaran/daftar_perusahaan_list', 'Ambil Data Perusahaan', 'class="link-wrc" rel="perusahaan_box"');
        ?>
        </div>

    </div>
    <br style="clear: both" />
    <div id="contentleft">
        <?php
        if ($status == "perusahaan")
            echo form_hidden('id_perusahaan', $id_perusahaan);
        ?>
        <div class="contentForm">
            <?php
            $npwp_input = array(
                'id' => 'npwp_id',
                'name' => 'npwp',
                'value' => $npwp,
                'class' => 'input-wrc required',
                'readonly' => "readonly"
            );

            echo form_label('NPWP ');
            echo form_input($npwp_input);
            ?>
        </div>

        <div class="contentForm">
            <?php
            $nodaftar_input = array(
                'name' => 'nodaftar',
                'id' => 'nodaftar',
                'value' => $nodaftar,
                'class' => 'input-wrc',
                'readonly' => "readonly"
                
            );

            echo form_label('No Register ');
            echo form_input($nodaftar_input);
            if ($statusOnline == "1") {
                ?>
                <?php echo form_label('      '); ?><br>
                <input type="button" onclick="show_npwp(this.form)" value="Cek NPWP dan No Daftar" class="button-wrc">
                <?php
                if (isset($_REQUEST['data_npwp_id'])) {
                    ?>
                    <input type="button" onclick="clear_data()" value="Clear Data" class="button-wrc">
                <?php } ?>
            <?php } ?>
        </div>

        <div class="contentForm">
            <?php
            $namaperusahaan_input = array(
                'name' => 'nama_perusahaan',
                'value' => $nama_perusahaan,
                'class' => 'input-wrc',
                'id'=>'namaperusahaan_input',
                'readonly' => "readonly"
            );

            echo form_label('Nama Perusahaan ');
            echo form_input($namaperusahaan_input);
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
            $fax_input = array(
                'name' => 'fax',
                'value' => $fax,
                'class' => 'input-wrc'
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
                'class' => 'input-wrc'
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

            if ($propinsi_usaha == " ") {
                echo form_dropdown('propinsi_usaha', $opsi_propinsi, '0', 'class = "input-select-wrc notSelect" id="propinsi_usaha_id"');
            } else {
                echo form_dropdown('propinsi_usaha', $opsi_propinsi, $propinsi_usaha, 'class = "input-select-wrc notSelect" id="propinsi_usaha_id"');
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
           /* $opsi_kecamatan = array('0' => '-------Pilih data-------');
            foreach ($list_kecamatan as $row) {
                $opsi_kecamatan[$row->id] = $row->n_kecamatan;
            }*/
            if ($kecamatan_usaha == NULL) {
                echo "<div id='show_kecamatan_usaha'>Data Tidak Tersedia</div>";
            } else {
                echo "<div id='show_kecamatan_usaha'><input type='hidden' value='" . $kecamatan_usaha . "' name='kecamatan_usaha' />" . $nama_kecamatan . "</div>";
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
                if ($kelurahan_usaha == NULL) {
                    echo "<div id='show_kelurahan_usaha'>Data Tidak Tersedia</div>";
                } else {
                    echo "<div id='show_kelurahan_usaha'><input type='hidden' value='" . $kelurahan_usaha . "' name='kelurahan_usaha' />" . $nama_kelurahan. "</div>";
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
                /*foreach ($list_kegiatan as $row) {
                    $opsi_kegiatan[' '] = "------Pilih salah satu------";
                    $opsi_kegiatan[$row->id] = $row->n_kegiatan;
                }

                echo form_label('Jenis Kegiatan ');
                if ($jenis_kegiatan == "ok") {
                    echo form_dropdown('jenis_kegiatan', $opsi_kegiatan, ' ', 'class = "input-select-wrc notSelect" id="jenis_kegiatan"');
                } else {
                    echo form_dropdown('jenis_kegiatan', $opsi_kegiatan, $jenis_kegiatan, 'class = "input-select-wrc notSelect" id="jenis_kegiatan"');
                }*/

                foreach ($list_kegiatan as $row) {
//                                $opsi_kegiatan['0'] = "------Pilih salah satu------";
                    $opsi_kegiatan[$row->id] = $row->n_kegiatan.'-'.$row->keterangan;
                }

                echo '<b>' . form_label('Jenis Kegiatan ') . '</b>';
                if ($jenis_kegiatan == "ok") {
                    echo form_dropdown('jenis_kegiatan[]', $opsi_kegiatan, '0', 'id="jenis_kegiatan" class = "input-select-wrc notSelect" style="width:300px" multiple="multiple"');
                } else {
                    echo form_dropdown('jenis_kegiatan[]', $opsi_kegiatan, $jenis_kegiatan, 'id="jenis_kegiatan" class = "input-select-wrc notSelect" style="width:300px " multiple="multiple"');
                }

                echo form_error('jenis_kegiatan', '<div class="field_error">', '</div>');
                ?>
            <p id="erorJ_kegiatan" align="right" style="visibility: hidden;"></p>
        </div>
        <div class="contentForm">
            <?php
//            $rw_input = array(
//                'name' => 'rw',
//                'value' => $rw,
//                'class' => 'input-wrc',
//            );
//            if($statusOnline == "1"){
//               $rw_input['readonly'] = "readonly";
//            }
//            echo form_label('RW');
//            echo form_input($rw_input);

            /*foreach ($list_investasi as $row) {
                $opsi_investasi[' '] = "------Pilih salah satu------";
                $opsi_investasi[$row->id] = $row->n_investasi;
            }

            echo form_label('Jenis Investasi ');
            if ($jenis_investasi == "ok") {
                echo form_dropdown('jenis_investasi', $opsi_investasi, ' ', 'class = "input-select-wrc notSelect" id="jenis_investasi"');
            } else {
                echo form_dropdown('jenis_investasi', $opsi_investasi, $jenis_investasi, 'class = "input-select-wrc notSelect" id="jenis_investasi"');
            }*/

            $opsi_investasi = array();
            foreach ($list_investasi as $row) {
//                                $opsi_investasi['0'] = "------Pilih salah satu------";
                $opsi_investasi[$row->id] = $row->n_investasi.'-'.$row->keterangan;
            }

            echo '<b>' . form_label('Jenis Investasi ') . '</b>';
            if ($jenis_investasi == "ok") {
                echo form_dropdown('jenis_investasi[]', $opsi_investasi, '0', 'id="jenis_investasi" class = "input-select-wrc notSelect" style="width:300px" multiple="multiple"');
            } else {
                echo form_dropdown('jenis_investasi[]', $opsi_investasi, $jenis_investasi, 'id="jenis_investasi" class = "input-select-wrc notSelect" style="width:300px" multiple="multiple"');
            }

            echo form_error('jenis_investasi', '<div class="field_error">', '</div>');
            ?>
            <p id="erorJ_investasi" align="right" style="visibility: hidden;"></p>
        </div>
    </div>
    <br style="clear: both;" />
</div>