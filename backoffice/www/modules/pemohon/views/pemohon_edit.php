<script>
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
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('name' => 'form', 'id' => 'form');
        echo form_open('pemohon/' . $save_method, $attr);
        echo form_hidden('id_pemohon', $id_pemohon);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Pemohon</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
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
                                    echo form_dropdown('cmbsource',$data,'0','class = "input-select-wrc" id="cmbsource" onChange=" ceksumber(this.value);return false;"');
                            }
                            
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
                            if ($save_method == "save") {
                                $idprop = 'no_refer';
                            } else {
                                $idprop = 'no_refer2';
                            }
                            $norefer_input = array(
                                'name' => 'no_refer',
                                'value' => $no_refer,
                                'class' => 'input-wrc required digits',
                                'onchange'=>'ceksumber(this.form.cmbsource.value);return false;',
                                'id' => $idprop
                            );
                            echo '<b>' . form_label('ID ') . '</b>';
                            echo form_input($norefer_input).'<span id="alert" style="color: red"></span>';
                            ?>
                        </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
                            $namapemohon_input = array(
                                'name' => 'nama_pemohon',
                                'value' => $nama_pemohon,
                                'class' => 'input-wrc required alphaOnly'
                            );
                            echo '<b>' . form_label('Nama Pemohon ') . '</b>';
                            echo form_input($namapemohon_input);
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
                            ?>
                        </div>

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
                    </div>
                    <div id="contentright">
                        <div class="contentForm">
                            <?php
                            $alamatdata_input = array(
                                'name' => 'alamat_pemohon',
                                'value' => $alamat_pemohon,
                                'class' => 'input-area-wrc required'
                            );
                            echo '<b>' . form_label('Alamat Pemohon ') . '</b>';
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
                            echo '<b>' . form_label('Alamat di Luar Negeri<br />(isikan jika ada)') . '</b>';
                            echo form_textarea($alamatdataluar_input);
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
                'name' => 'submit',
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
                'onclick' => 'parent.location=\'' . site_url('pemohon') . '\''
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
    }, 'Pilih opsi yang tersedia.');
    
    $.validator.addMethod('alphaOnly', function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, 'Hanya diisi oleh huruf.');
    
    var site = '<?php echo base_url(); ?>';
    
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
            }
        }, 
       
        messages:{
            no_refer:{
                remote:'No referensi sudah digunakan!'
            }
        }
    });
</script>
