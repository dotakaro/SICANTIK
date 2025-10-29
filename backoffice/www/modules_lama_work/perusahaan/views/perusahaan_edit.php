<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('perusahaan/' . $save_method, $attr);
        echo form_hidden('id_perusahaan', $id_perusahaan);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Perusahaan</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                    <div class="contentForm">
                            <?php
                            $regperusahaan_input = array(
                                'name' => 'reg_perusahaan',
                                'value' => $reg_perusahaan,
                                'class' => 'input-wrc required'
                            );
                            echo '<b>' . form_label('No Registrasi') . '</b>';
                            echo form_input($regperusahaan_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $namaperusahaan_input = array(
                                'name' => 'nama_perusahaan',
                                'value' => $nama_perusahaan,
                                'class' => 'input-wrc required'
                            );
                            echo '<b>' . form_label('Nama Perusahaan ') . '</b>';
                            echo form_input($namaperusahaan_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            if ($save_method == "save") {
                                $idprop = 'npwp';
                            } else {
                                $idprop = 'npwp2';
                            }

                            $npwp_input = array(
                                'name' => 'npwp',
                                'value' => $npwp,
                                'class' => 'input-wrc required ',
                                'id' => $idprop
                            );
                            echo '<b>' . form_label('NPWP ') . '</b>';
                            echo form_input($npwp_input).'<span id="alert" style="color: red;"></span>';
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $telp_input = array(
                                'name' => 'telp_perusahaan',
                                'value' => $telp_perusahaan,
                                'class' => 'input-wrc required digits'
                            );
                            echo '<b>' . form_label('Telp Perusahaan ') . '</b>';
                            echo form_input($telp_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $alamatusaha_input = array(
                                'name' => 'alamat_usaha',
                                'value' => $alamat_usaha,
                                'class' => 'input-area-wrc required'
                            );
                            echo '<b>' . form_label('Alamat Perusahaan ') . '</b>';
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

                            if ($propinsi_usaha == NULL) {
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
                                /*$opsi_kecamatan = array('0' => '-------Pilih data-------');
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
                                    echo "<div id='show_kelurahan_usaha'><input type='hidden' value='" . $kelurahan_usaha . "' name='kelurahan_usaha' />" . $nama_kelurahan . "</div>";
                                }
                            ?>
                        </div>

                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
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
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
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
                            ?>
                        </div>
                    </div>
                    <br style="clear: both;" />
                </div>
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
            echo form_submit($add_daftar);
            echo "<span></span>";
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . site_url('perusahaan') . '\''
            );
            echo form_button($cancel_daftar);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>

<!-- Menambahkan rule untuk opsi jika value-nya = 0 -->
<script type="text/javascript">
    $.validator.addMethod('notSelect', function(value, element) {
        return (value != 0);
    }, 'Pilih opsi yang tersedia.')
    
    $.validator.addMethod('alphaOnly', function(value, element) {
        return this.optional(element) || /^[a-z. ]+$/i.test(value);
    }, 'Hanya diisi oleh huruf.');
    
    var site = '<?php echo base_url(); ?>';
    
    $("#form").validate({
        onkeyup: false,
        rules:{
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
        },
        messages:{
            npwp:{
                remote:'No NPWP sudah digunakan!'
            }
        }
    })
    
    //$(document).ready(function(){
//      $('#form').submit(function(){
//        $.ajax({
//            type: 'POST',
//            url: site + 'perusahaan/register_npwp_exist',
//            data: $(this).serialize(),
//            success: function(data) {
//                if(data == "1"){
//                     $('#alert').html('No NPWP sudah digunakan!');
//                } else {
//                     $.ajax({
//                        type: 'POST',
//                        url: site + 'perusahaan/save',
//                        data: $('#form').serialize(),
//                        success: function() {
//                            
//                        }                        
//                     })   
//                }
//            }
//        })
//        return false;
//    });  
//         
//        $("#npwp").keypress(function(){
//            $('#alert').html('');
//        });
//    }) 
</script>
