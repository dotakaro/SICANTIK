<script>
function validasi()
{
     var catatan = document.forms[0].pesankomentar.value;
    
    if (catatan.length=='0')
    {
        document.getElementById('erorCatatan').innerHTML = 'Field ini harus diisi';
        document.getElementById('erorCatatan').style.visibility = "visible"; 
        document.getElementById('erorCatatan').style.color = "#FF2F2F";
         return false; 
    }
   
}

</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">

            <?php 
            $attr = array('name' => 'form', 'id' => 'form','onsubmit' => 'return validasi()');
            echo form_open('permohonan/bap/save',$attr); ?>

            <?php echo form_hidden('id_bap', $id_bap); ?>
            <?php
            echo form_hidden('id', $id);
            echo form_hidden('tim_teknis_id', $tim_teknis_id);
            echo form_hidden('waktu_awal', $waktu_awal);
            $izin = new trperizinan();
            $izin->get_by_id($idjenis);
            $kelompok = $izin->trkelompok_perizinan->get();
            ?>

            <fieldset>
                <legend>Data Berita Acara Pemeriksaan</legend>
                <div id="statusMain">
                    <div id="leftMain">
                        <?php
                        echo form_label('No pendaftaran');
                        ?>
                    </div>
                    <div id="rightMain">
                        <?php echo form_hidden('nopendaftaran', $nopendaftaran); ?>
<?php echo $nopendaftaran; ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('Jenis layanan');
                        ?>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php
                        echo $jenislayanan;
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <?php
                        echo form_label('Nama Pemohon');
                        ?>
                    </div>
                    <div id="rightMain">
                        <?php
                        echo $namapemohon;
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('Alamat Pemohon');
                        ?>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php
                        echo $alamatpemohon;
                        ?>
                    </div>
                </div>

                <div id="statusMain">
                    <div id="leftMain" >
                        <?php
                        echo form_label('Nama Perusahaan');
                        ?>
                    </div>
                    <div id="rightMain" >
                        <?php echo $namaperusahaan; ?>
                    </div>
                </div>
                <?php
                //if($kelompok->id == "2" || $kelompok->id == "4"){
                ?>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('Tanggal Peninjauan');
                        ?>
                    </div>
                    <div id="rightMain"class="bg-grid" >
                        <?php
                        $tanggalperiksa_input = array(
                            'name' => 'tglperiksa',
                            'value' => $tglperiksa,
                            'readOnly'=>TRUE,
                            'class' => 'input-wrc required',
                            'id' => 'bap'
                        );
                        echo form_input($tanggalperiksa_input);
                        ?>
                    </div>
                </div>
                <?php
              //  }
                        if ($id_bap) {
                ?>
                            <div id="statusMain">
                                <div id="leftMain" >
                        <?php
                            echo form_label('No BAP', 'jenis_permohonan');
                        ?><br><br>
                        </div>
                        <div id="rightMain" >
                        <?php
                            echo $no_bap;
                        ?>
                        </div>
                    </div>
<?php
                        }
?>

                        <div id="statusMain">
                            <br><br>
                            <table border="1" width="900" cellpadding="2px" cellspacing="0" align="center">
                                <tr>
                                    <td colspan="6" align="center" bgcolor="#CED9FE" height="33px"><b>PROPERTY</b></td>
                                <tr>
                                <tr>
                                    <td colspan="3" align="center" height="25"><b>Data Berkas Permohonan</b></td>
                                    
                                    <td colspan="3" align="center" height="25"><b>Data Tinjauan</b></td>
                                    
                                <tr>
<?php
                        $i = 0;
                        $z = 1;
                        foreach ($list as $data) {
                            $i++;
                            $entry_id = '';
                            $data_entry = '';
                            $data_entry2 = '';
                            $data_koefisien = 0;
                            $data_koefisien2 = 0;
                            //tambahan
                            $data_koefisient = 0;
                            $data_entryt = '';

                            if ($list_daftar->id) {
                                foreach ($list_daftar as $data_daftar) {
                                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                            ->where('trproperty_id', $data->id)->get();
                                    if ($entry_property->tmproperty_jenisperizinan_id) {
                                        $entry_daftar = new tmproperty_jenisperizinan();
                                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                                        $entry_id = $entry_daftar->id;
                                        $data_entry = $entry_daftar->v_property;
                                        $data_koefisien = $entry_daftar->k_property;
                                        $data_entryt = $entry_daftar->v_tinjauan;
                                        $data_koefisient = $entry_daftar->k_tinjauan;
//                                    }
//                                }
//                            } else {
//                                $entry_id = '';
//                                $data_entry = '';
//                                $data_koefisien = 0;
//                                $data_entryt = '';
//                                $data_koefisient = 0;
//                            }
                           /* $data_koefisien2 = new trkoefesientarifretribusi();
                            $data_koefisien2->get_by_id($data_koefisien);
                            $data_koefisien3 = new trkoefesientarifretribusi();
                            $data_koefisien3->get_by_id($data_koefisient);*///diremark karena membuat error

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $idjenis)
                        ->where('trproperty_id', $data->id)->get();
                        $id_tl = $izin_property->c_tl_id;
                        if($id_tl == '1'){
                            if ($i % 2 == 0) {
                                $color = "#FFFFFF";
                            } else {
                                $color = "#CED9FE";
                            }

                            if ($data->c_type !== '2'){
                                    echo "<tr bgcolor='" . $color . "'>";
                                    
                            if ($data->c_type == '2') {
                                $nprop = "<b>" . $data->n_property . "</b>";
                            } else {
                                $nprop = $data->n_property;
                            }

                          if ($data_entry == '0') {
                                $hasil = "";
                          } else {
                                if($data->c_type == '1'){
                                    if($data_entry)
                                    $hasil = $data_koefisien2->kategori.' ('.$data_entry.')';
                                    else
                                    $hasil = $data_koefisien2->kategori;
                                }else{
                                    if($data_entry) $hasil = $data_entry." ".$izin_property->satuan;
                                    else $hasil = "";
                                }
                          } 
                            ?>
                           
                            <td><?php echo $nprop; ?></td>
                            <td colspan="2"><?php echo $hasil; ?></td>

                
                            <td><?php echo $nprop; ?></td>
                                      <?php
                                      if ($data_entryt == '0') {
                                            $hasil2 = "";
                                      } else {
                                            if($data->c_type == '1')
                                                if($data_entryt)
                                                $hasil2 = $data_koefisien3->kategori.' ('.$data_entryt.')';
                                                else
                                                $hasil2 = $data_koefisien3->kategori;
                                            else{
                                                if($data_entryt) $hasil2 = $data_entryt." ".$izin_property->satuan;
                                                else $hasil2 = "";
                                            }
                                      }
                            ?>
                            <td colspan="2"><?php echo $hasil2; ?></td>
                            </tr>

                            <?php
                            }
                        }
                                    if ($data->c_type == '1') {
                                        //echo $data_koefisien3->index_kategori;
                                        $z = $z * $data_koefisien3->index_kategori;
                                    }
                            ?>


                            <?php
                                    }
                                }
                            }
                        }
                            ?>
                    </table>

                    <br>

                </div>

                <div id="statusMain">
                    <div id="leftMain">

                    </div>

                    <div id="rightMain" style="font-size: 14px;">

                        <?php if($idjenis == '2') $total = $retribusi;
                        else $total = $retribusi * $z; ?>
                        <?php
                      if($m_hitung=="1")
                        {
                            //echo $n_manual->v_tinjauan;
                            echo form_hidden('nilai_retribusi', $n_manual->v_tinjauan);
                        }else
                        {
                            echo form_hidden('nilai_retribusi', $total); 
                        }
                        ?>
                        
                        <br>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <?php
                        echo form_label('Catatan', 'nama_izin');
                        ?><br><br>
                    </div>
                    <div id="rightMain">
                        <?php
                        $pesan = array(
                            'name' => 'pesankomentar',
                            'value' => $pesan,
                            'class' => 'input-area-wrc',
                            'id'    => 'pesankomentar'
                        );
                        echo form_textarea($pesan);
                        ?>
                         <p id="erorCatatan" style="visibility: hidden;"></p>
                        <br><br>
                    </div>
                </div>                    
                        <div class="entry" style="text-align: center;">
                    <?php
                        $save = array(
                            'name' => 'submit',
                            'class' => 'submit-wrc',
                            'content' => 'Simpan',
                            'type' => 'submit',
                            'value' => 'Simpan'
                        );
                        echo form_submit($save);
                        echo form_close();
                        echo "<span></span>";
                        $cancel_daftar = array(
                            'name' => 'button',
                            'class' => 'button-wrc',
                            'content' => 'Batal',
                            'onclick' => 'parent.location=\'' . site_url('permohonan/bap') . '\''
                        );
                        echo form_button($cancel_daftar);
                    ?>
                </div>

            </fieldset>
        </div>

        <br style="clear: both;" />
    </div>
</div>