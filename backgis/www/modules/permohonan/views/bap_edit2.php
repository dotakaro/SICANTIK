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
            echo form_hidden('waktu_awal', $waktu_awal);
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
                            'class' => 'input-wrc required',
                            'id' => 'bap'
                        );
                        echo form_input($tanggalperiksa_input);
                        ?>
                    </div>
                </div>
                <?php
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
                            <table border="1" width="auto" cellpadding="2px" cellspacing="0" align="center">
                                <tr>
                                    <td colspan="6" align="center" class="bg-grid" height="33px"><b>PROPERTY</b></td>
                                <tr>
                                <tr>
                                    <td colspan="3" align="center" height="25"><b>Data Berkas Permohonan</b></td>
                                    <td colspan="3" align="center" height="25" ><b>Data Tinjauan</b></td>
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
                                        $data_koefisien = $entry_daftar->k_tinjauan;//ubah by rido sebelumnya k_property;
                                        $data_entryt = $entry_daftar->v_tinjauan;
                                        $data_koefisient = $entry_daftar->k_tinjauan;
//                                    }
//                                }
//                            }
//                            else {
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

                            //ketika klasifikasi

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $idjenis)->where('trproperty_id', $data->id)->get();
                        $id_tl = $izin_property->c_tl_id;
                        if($id_tl == '1'){
                             

                            ?>
                            <?php if ($i % 2 == 0) {
                                $color = "#FFFFFF";
                            } else {
                                $color = "class='bg-grid'";
                            } ?>

                            <?php echo "<tr " . $color . ">"; ?>
                            <?php
                            if ($data->c_type == '2') {
                                $nprop = "<b>" . $data->n_property . "</b>";
                            } else {
                                $nprop = $data->n_property;
                            }

                              if ($data_entry == '0') {
                                    $hasil = "";
                              } else {
                                    $hasil = $data_entry;
                              }
                            ?>

                            <?php $no=1;
                             if($data->id == '12'){  
                             echo "<td colspan='3' valign='top'>";
                                    echo "<table border='0' width='100%' cellpadding='3' cellspacing='0'>";
                                    echo "<tr><td colspan='3'>".$data->n_property."</td></tr>";
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();
                                    if($list_koefisien->id){
                                      foreach ($list_koefisien as $row_koef){
                                            $no++;
                                            $klasifikasi_id = '';
                                            $entry_klasifikasi = '';
                                            $koef_klasifikasi = 0;
                                            $entry_klasifikasi2 = '';
                                            $koef_klasifikasi2 = 0;
                                            if($list_klasifikasi->id){
                                                foreach ($list_klasifikasi as $data_klasifikasi){
                                                    $entry_koefisien = new tmproperty_klasifikasi_trkoefesientarifretribusi();
                                                    $entry_koefisien->where('tmproperty_klasifikasi_id', $data_klasifikasi->id)
                                                    ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                                    if($entry_koefisien->tmproperty_klasifikasi_id){
                                                        $entry_daftar_klasifikasi = new tmproperty_klasifikasi();
                                                        $entry_daftar_klasifikasi->get_by_id($entry_koefisien->tmproperty_klasifikasi_id);

                                                        $klasifikasi_id = $entry_daftar_klasifikasi->id;
                                                        $entry_klasifikasi = $entry_daftar_klasifikasi->v_tinjauan;
                                                        $koef_klasifikasi = $entry_daftar_klasifikasi->k_tinjauan;
                                                        $entry_klasifikasi2 = $entry_daftar_klasifikasi->v_klasifikasi;
                                                        $koef_klasifikasi2 = $entry_daftar_klasifikasi->k_klasifikasi;
                                                    }
                                                }
                                            }else{
                                                $klasifikasi_id = '';
                                                $entry_klasifikasi = '';
                                                $koef_klasifikasi = 0;
                                                $entry_klasifikasi2 = '';
                                                $koef_klasifikasi2 = 0;
                                            }
                                             if ($no % 2 == 0) {
                                                $color = "class='bg-grid'";
                                            } else {
                                                $color = "bgcolor='#FFFFFF'";
                                            }
                                            $data_retribusi = new trkoefisienretribusilev1();
                                            $data_retribusi->where('id', $koef_klasifikasi2)->get(); // k_klasifikasi
                                            echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td>".$row_koef->kategori."</td>
                                                 <td>: ".$data_retribusi->kategori."</td></tr>";
                                          
                                       }
                                          echo "</table>";
                                    }
                                
                              echo "</td>";
                              }else  if($data->id == '29'){                               
                                    echo "<td colspan='3' valign='top'>";
                                    echo "<table border='0' width='100%' cellpadding='3' cellspacing='0'>";
                                    echo "<tr><td colspan='3'>".$data->n_property."</td></tr>";
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();
                                   
                                    foreach ($list_koefisien as $row_koef){
                                            $no++;
                                            $prasarana_id = '';
                                            $entry_prasarana = '';
                                            $koef_prasarana = 0;
                                            $entry_prasarana2 = '';
                                            $koef_prasarana2 = 0;
                                            if($list_prasarana->id){
                                                foreach ($list_prasarana as $data_prasarana){
                                                    $entry_koefisien = new tmproperty_prasarana_trkoefesientarifretribusi();
                                                    $entry_koefisien->where('tmproperty_prasarana_id', $data_prasarana->id)
                                                    ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                                    if($entry_koefisien->tmproperty_prasarana_id){
                                                        $entry_daftar_prasarana = new tmproperty_prasarana();
                                                        $entry_daftar_prasarana->get_by_id($entry_koefisien->tmproperty_prasarana_id);

                                                        $prasarana_id = $entry_daftar_prasarana->id;
                                                        $entry_prasarana = $entry_daftar_prasarana->v_tinjauan;
                                                        $koef_prasarana = $entry_daftar_prasarana->k_tinjauan;
                                                        $entry_prasarana2 = $entry_daftar_prasarana->v_prasarana;
                                                        $koef_prasarana2 = $entry_daftar_prasarana->k_prasarana;
                                                    }
                                                }
                                            }else{
                                                $prasarana_id = '';
                                                $entry_prasarana = '';
                                                $koef_prasarana = 0;
                                                $entry_prasarana2 = '';
                                                $koef_prasarana2 = 0;
                                            }
                                              if ($no % 2 == 0) {
                                                $color = "class='bg-grid'";
                                            } else {
                                                $color = "bgcolor='#FFFFFF'";
                                            }

                                            $data_prasarana = new trkoefisienretribusilev1();
                                            $data_prasarana->where('id', $koef_prasarana2)->get(); //k_prasarana
                                            if($entry_prasarana2 === '0') $entry_prasarana2 = "";
                                            if($entry_prasarana2) $entry_prasarana2 = "(".$entry_prasarana2." ".$row_koef->satuan.")";
                                            echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td>".$row_koef->kategori."</td>
                                                 <td>: ".$data_prasarana->kategori." ".$entry_prasarana2."</td></tr>";
                                        }
                                        echo "</table>";
                                   }else
                                     {
                                       $property_satuan = new trperizinan_trproperty();
                                       $property_satuan->where('trproperty_id', $data->id)->get();
                                       
                                        echo "<td>".$nprop."</td>";
                                        if($data->c_type == '1'){
                                            if($hasil)
                                            {
                                                echo "<td colspan='2'>".$data_koefisien2->kategori." (".$hasil." ".$property_satuan->satuan.")</td>";
                                            }
                                            else
                                            {
                                                echo "<td colspan='2'>".$data_koefisien2->kategori."</td>";
                                            }                                                
                                        }else{
                                        if($data_entry) $hasil = $data_entry." ".$property_satuan->satuan;
                                        else $hasil = "";
                                        echo "<td colspan='2'>".$hasil."</td>";
                                        }
                                    }
                                    
                            $no=1;
                             if($data->id == '12'){
                             echo "<td colspan='3' valign='top'>";
                                    echo "<table border='0' width='100%' cellpadding='3' cellspacing='0'>";
                                    echo "<tr><td colspan='3'>".$data->n_property."</td></tr>";
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();
                                    if($list_koefisien->id){
                                      foreach ($list_koefisien as $row_koef){
                                            $no++;
                                            $klasifikasi_id = '';
                                            $entry_klasifikasi = '';
                                            $koef_klasifikasi = 0;
                                            $entry_klasifikasi2 = '';
                                            $koef_klasifikasi2 = 0;
                                            if($list_klasifikasi->id){
                                                foreach ($list_klasifikasi as $data_klasifikasi){
                                                    $entry_koefisien = new tmproperty_klasifikasi_trkoefesientarifretribusi();
                                                    $entry_koefisien->where('tmproperty_klasifikasi_id', $data_klasifikasi->id)
                                                    ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                                    if($entry_koefisien->tmproperty_klasifikasi_id){
                                                        $entry_daftar_klasifikasi = new tmproperty_klasifikasi();
                                                        $entry_daftar_klasifikasi->get_by_id($entry_koefisien->tmproperty_klasifikasi_id);

                                                        $klasifikasi_id = $entry_daftar_klasifikasi->id;
                                                        $entry_klasifikasi = $entry_daftar_klasifikasi->v_tinjauan;
                                                        $koef_klasifikasi = $entry_daftar_klasifikasi->k_tinjauan;
                                                        $entry_klasifikasi2 = $entry_daftar_klasifikasi->v_klasifikasi;
                                                        $koef_klasifikasi2 = $entry_daftar_klasifikasi->k_klasifikasi;
                                                    }
                                                }
                                            }else{
                                                $klasifikasi_id = '';
                                                $entry_klasifikasi = '';
                                                $koef_klasifikasi = 0;
                                                $entry_klasifikasi2 = '';
                                                $koef_klasifikasi2 = 0;
                                            }
                                             if ($no % 2 == 0) {
                                                $color = "class='bg-grid'";
                                            } else {
                                                $color = "bgcolor='#FFFFFF'";
                                            }
                                            $data_retribusi2 = new trkoefisienretribusilev1();
                                            $data_retribusi2->where('id', $koef_klasifikasi)->get(); //k_tinjauan
                                            echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td>".$row_koef->kategori."</td>
                                                 <td>: ".$data_retribusi2->kategori."</td></tr>";

                                       }
                                          echo "</table>";
                                    }

                              echo "</td>";
                              }else  if($data->id == '29'){
                                    echo "<td colspan='3' valign='top'>";
                                    echo "<table border='0' width='100%' cellpadding='3' cellspacing='0'>";
                                    echo "<tr><td colspan='3'>".$data->n_property."</td></tr>";
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();

                                    foreach ($list_koefisien as $row_koef){
                                            $no++;
                                            $prasarana_id = '';
                                            $entry_prasarana = '';
                                            $koef_prasarana = 0;
                                            $entry_prasarana2 = '';
                                            $koef_prasarana2 = 0;
                                            if($list_prasarana->id){
                                                foreach ($list_prasarana as $data_prasarana){
                                                    $entry_koefisien = new tmproperty_prasarana_trkoefesientarifretribusi();
                                                    $entry_koefisien->where('tmproperty_prasarana_id', $data_prasarana->id)
                                                    ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                                    if($entry_koefisien->tmproperty_prasarana_id){
                                                        $entry_daftar_prasarana = new tmproperty_prasarana();
                                                        $entry_daftar_prasarana->get_by_id($entry_koefisien->tmproperty_prasarana_id);

                                                        $prasarana_id = $entry_daftar_prasarana->id;
                                                        $entry_prasarana = $entry_daftar_prasarana->v_tinjauan;
                                                        $koef_prasarana = $entry_daftar_prasarana->k_tinjauan;
                                                        $entry_prasarana2 = $entry_daftar_prasarana->v_prasarana;
                                                        $koef_prasarana2 = $entry_daftar_prasarana->k_prasarana;
                                                    }
                                                }
                                            }else{
                                                $prasarana_id = '';
                                                $entry_prasarana = '';
                                                $koef_prasarana = 0;
                                                $entry_prasarana2 = '';
                                                $koef_prasarana2 = 0;
                                            }
                                              if ($no % 2 == 0) {
                                                $color = "class='bg-grid'";
                                            } else {
                                                $color = "bgcolor='#FFFFFF'";
                                            }

                                            $data_prasarana2 = new trkoefisienretribusilev1();
                                            $data_prasarana2->where('id', $koef_prasarana)->get(); //k_tinjauan
                                            if($entry_prasarana === '0') $entry_prasarana = "";
                                            if($entry_prasarana) $entry_prasarana = "(".$entry_prasarana." ".$row_koef->satuan.")";
                                            echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td>".$row_koef->kategori."</td>
                                                 <td>: ".$data_prasarana2->kategori." ".$entry_prasarana."</td></tr>";
                                        }
                                        echo "</table>";
                                   }

                            else{
                               $property_satuan = new trperizinan_trproperty();
                               $property_satuan->where('trproperty_id', $data->id)->get();
                                echo "<td>".$nprop."</td>";
                                      if ($data_entryt == '0') {
                                            $hasil2 = "";
                                      } else {
                                            if($data->c_type == '1'){
                                                if($hasil)
                                            $hasil2 = $data_koefisien2->kategori." (".$data_entryt." ".$property_satuan->satuan.")";
                                                else
                                            $hasil2 = $data_koefisien2->kategori;
                                            }else{
                                            if($data_entryt) $hasil2 = $data_entryt." ".$property_satuan->satuan;
                                            else $hasil2 = "";
                                            }
                                      }
                            echo "<td colspan='2'>".$hasil2."</td>";
                        }
                        echo "</tr>";
                        
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