<script type="text/javascript">
function myfunction()
{
    /*var total=""
    for(var i=0; i < document.form1.status.length; i++){
    if(document.form1.status[i].checked)
    total +=document.form1.status[i].value + "\n"
}
     alert(total);*/
     var nilai="";
     if(document.form1.status[0].checked==true)
     {
        nilai="apakah anda yakin akan melakukan penetapan izin.?";
     }
     else
     {
        nilai="apakah anda yakin akan melakukan penolakan izin.?";
     }
  
     if(confirm(nilai)==true)
     {
        return true;
     }
     else
     {
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
             $attr = array('id' => 'form','name'=>'form1','onsubmit'=>'return myfunction()');
            echo form_open('permohonan/penetapan/save',$attr); ?>
            <?php echo form_hidden('id_bap', $id_bap); ?>
            <?php echo form_hidden('id', $id); ?>
            <?php echo form_hidden('jenislayanan', $jenislayanan);
            echo form_hidden('waktu_awal', $waktu_awal);
            $izin = new trperizinan();
            $izin->get_by_id($idjenis);
            $kelompok = $izin->trkelompok_perizinan->get();
                ?>

            <fieldset>
                <legend>Daftar Berita acara</legend>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('No pendaftaran', 'nama_izin');
                        ?>
                    </div>
                    <div id="rightMain" class="bg-grid">

                        <?php echo $nopendaftaran; ?>
                        <?php echo form_hidden('nopendaftaran', $nopendaftaran); ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <?php
                        echo form_label('Jenis layanan', 'kelompok_izin');
                        ?>
                    </div>
                    <div id="rightMain">
                        <?php
                        echo $jenislayanan;
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('Nama Pemohon', 'keterangan');
                        ?>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php
                        echo $namapemohon;
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <?php
                        echo form_label('Alamat Pemohon', 'jenis_permohonan');
                        ?>
                    </div>
                    <div id="rightMain">
                        <?php
                        echo $alamatpemohon;
                        ?>
                    </div>
                </div>

                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('Nama Perusahaan', 'jenis_permohonan');
                        ?>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php echo $namaperusahaan; ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <?php
                        echo form_label('Catatan');
                        ?><br><br>
                    </div>
                    <div id="rightMain">
                        <?php echo $pesan; ?>
                        <?php echo form_hidden('pesankomentar', $pesan); ?>
                        <br><br>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('No BAP', 'jenis_permohonan');
                        ?>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php echo $nosk; ?>
                        <?php echo form_hidden('nobap', $nosk); ?>
                    </div>
                </div>
				<div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <?php
                        echo form_label('Alamat Izin', 'alamatizin');
                        ?>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php echo $alamatizin; ?>
                        <?php echo form_hidden('alamatizin', $nosk); ?>
                    </div>
                </div>
                
                <div id="statusMain">
                    <div id="leftMain">
                        <?php
                        echo form_label('Tanggal Peninjauan', 'jenis_permohonan');
                        ?>
                    </div>
                    <div id="rightMain">

                        <?php echo $this->lib_date->mysql_to_human($tglperiksa); ?>
                    </div>
                </div>
                
                <div id="statusMain">
                    <br>
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
                            $data_koefisien2 = new trkoefesientarifretribusi();
                            $data_koefisien2->get_by_id($data_koefisien);
                            $data_koefisien3 = new trkoefesientarifretribusi();
                            $data_koefisien3->get_by_id($data_koefisient);
                            ?>
                            <?php if ($i % 2 == 0) {
                                $color = "#FFFFFF";
                            } else {
                                $color = "#CED9FE";
                            } ?>

                            <?php echo "<tr bgcolor='" . $color . "'>"; ?>
                            <?php
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
                                }else
                                    $hasil = $data_entry;
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
                                            else
                                                $hasil2 = $data_entryt;
                                      }
                            ?>
                            <td colspan="2"><?php echo $hasil2; ?></td>
                            <?php
                            echo "</tr>"; ?>

                            <?php
                                    if ($data->c_type == '1') {
                                        //echo $data_koefisien2->index_kategori;
                                        $z = $z * $data_koefisien2->index_kategori;
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
<?php echo form_label('Nilai Retribusi', 'nama_izin'); ?>
                        </div>

                        <div id="rightMain">
                       <?php
                       if(empty($retribusi)) { $retribusi = 0; }

                        echo "Rp. ".$this->terbilang->nominal($retribusi, 2);
                        ////$z;
                        echo form_hidden('nilai_retribusi', $retribusi);

                        ?>
                        <?php ?>
                            <br>
                        </div>
                    </div>


                    <div id="statusMain">
                        <div id="leftMain">
                        <?php
                            echo form_label('Permohonan perizinan', 'nama_izin');
                        ?>
                        </div>

                        <div id="rightMain">

                        <?php
                            $cek = TRUE;
                            $checked = FALSE;
//                            if ($status == '1') {
//                                $cek = TRUE;
//                            } else {
//                                $cek = FALSE;
//                            }
                            $status1 = array(
                                'name' => "status",
                                'value' => "1",
                                'id'=>'status',
                                'checked' => $cek
                            );
//                            if ($status == '1') {
//                                $checked = FALSE;
//                            } else {
//                                $checked = TRUE;
//                            }
                            $status2 = array(
                                'name' => "status",
                                'value' => "2",
                                'id'=>'status',
                                'checked' => $checked
                            );
                            switch($ditetapkan){
                                case 1:
                                case 2:
                                    echo "<b>";
                                    if($status === "1")
                                        echo "Diizinkan";
                                    else
                                        echo "Ditolak";
                                    echo "</b>";
                                    break;
                                default:
                                    echo form_radio($status1) . " Diizinkan";
                                    echo form_radio($status2) . " Ditolak";
                                    break;
                            }
                            if($ditetapkan === "1"){
                                echo "<b>";
                                if($status === "1") echo "Diizinkan";
                                else echo "Ditolak";
                                echo "</b>";
                            }else{

                            }
                            
                        ?>
                            <br>
                        </div>
                    </div>
                    <br>
        <div class="entry" style="text-align: center;">
           <?php
            $save = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
             );
             if($ditetapkan == 0)
                echo form_submit($save);

             echo form_close();
            echo "<span></span>";
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('permohonan/penetapan') . '\''
            );
            echo form_button($cancel_daftar);
             ?>

                </div>


            </fieldset>
        </div>


        <br style="clear: both;" />
    </div>
</div>