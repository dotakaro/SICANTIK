<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php echo form_open('permohonan/penetapan/save'); ?>
            <?php echo form_hidden('id_bap', $id_bap); ?>
            <?php echo form_hidden('id', $id); ?>
            <?php echo form_hidden('jenislayanan', $jenislayanan);
            echo form_hidden('waktu_awal', $waktu_awal);?>

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
                        echo form_label('Tanggal Peninjauan', 'jenis_permohonan');
                        ?>
                    </div>
                    <div id="rightMain">

                        <?php echo $this->lib_date->mysql_to_human($tglperiksa); ?>
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

                            //ketika klasifikasi



                            ?>
                            <?php if ($i % 2 == 0) {
                                $color = "bgcolor='#FFFFFF'";
                            } else {
                                $color = "class='bg-grid'";//#D5D0F0
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
                             echo "<td colspan='3'>";
                                    echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
                                    echo "<tr bgcolor='" . $color . "'><td colspan='3'>".$data->n_property."</td></tr>";
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
                                            $data_retribusi = new trkoefisienretribusilev1();
                                            $data_retribusi->where('id', $koef_klasifikasi2)->get();

                                                                                if ($no % 2 == 0) {
                                                                                    $color = "class='bg-grid'";
                                                                                } else {
                                                                                    $color = "bgcolor='#FFFFFF'";
                                                                                }
                                                                                if($entry_klasifikasi2)$entry_klasifikasi2 ="  (".$entry_klasifikasi2.")";
                                            echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td>".$row_koef->kategori."</td>
                                                 <td>: ".$data_retribusi->kategori.$entry_klasifikasi2."</td></tr>";

                                       }
                                          echo "</table>";
                                    }

                              echo "</td>";}
                              else  if($data->id == '29'){

                                    echo "<td colspan='3'>";
                                    echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
                                    echo "<tr " . $color . "><td colspan='3'>".$data->n_property."</td></tr>";
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
                         
                                            $data_retribusi = new trkoefisienretribusilev1();
                                            $data_retribusi->where('id', $koef_prasarana2)->get();
                                                                        if ($no % 2 == 0) {
                                                                            $color = "class='bg-grid'";
                                                                        } else {
                                                                            $color = "bgcolor='#FFFFFF'";
                                                                        }
                                                                        if($entry_prasarana2 === '0') $entry_prasarana2 = "";
                                                                        if($entry_prasarana2) $entry_prasarana2 =" (". $entry_prasarana2." ".$row_koef->satuan.")";
                                            echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td>".$row_koef->kategori."</td>
                                                 <td>: ".$data_retribusi->kategori.$entry_prasarana2."</td></tr>";
                                        }
                                        echo "</table>";
                                   }

                            else{
                               $property_satuan = new trperizinan_trproperty();
                               $property_satuan->where('trproperty_id', $data->id)->get();
                                echo "<td>".$nprop."</td>";
                                if($data->c_type == '1'){
                                    if($hasil)
                                echo "<td colspan='2'>".$data_koefisien2->kategori." (".$hasil." ".$property_satuan->satuan.")</td>";
                                    else
                                echo "<td colspan='2'>".$data_koefisien2->kategori."</td>";
                                }else
                                echo "<td colspan='2'>".$hasil." ".$property_satuan->satuan."</td>";
                                }?>

                                   <?php
                                      if ($data_entryt == '0') {
                                            $hasil2 = "";
                                      } else {
                                            $hasil2 = $data_entryt;
                                      }
                            ?>
                             <?php
                             $no=1;
                             if($data->id == '12'){
                             echo "<td colspan='3'>";
                                    echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
                                    echo "<tr bgcolor='#FFFFFF'><td colspan='3'>".$data->n_property."</td></tr>";
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
                                                    $data_retribusi = new trkoefisienretribusilev1();
                                                    $data_retribusi->where('id', $koef_klasifikasi)->get();

                                                                                 if ($no % 2 == 0) {
                                                                                    $color = "class='bg-grid'";
                                                                                } else {
                                                                                    $color = "bgcolor='#FFFFFF'";
                                                                                }
                                                                                if($entry_klasifikasi)$entry_klasifikasi = "  (".$entry_klasifikasi.")";
                                          echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td >".$row_koef->kategori."</td>
                                                 <td>: ".$data_retribusi->kategori.$entry_klasifikasi."</td></tr>";

                                       }
                                          echo "</table>";
                                    }

                              echo "</td>";}
                              else  if($data->id == '29'){

                                    echo "<td colspan='3'>";
                                    echo "<table border='0' width='100%' cellpadding='0' cellspacing='0'>";
                                    echo "<tr bgcolor='#FFFFFF'><td colspan='3'>".$data->n_property."</td></tr>";
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
                                                    $data_retribusi = new trkoefisienretribusilev1();
                                                    $data_retribusi->where('id', $koef_prasarana2)->get();

                                                                                if ($no % 2 == 0) {
                                                                                    $color = "class='bg-grid'";
                                                                                } else {
                                                                                    $color = "bgcolor='#FFFFFF'";
                                                                                }
                                                                                if($entry_prasarana === '0') $entry_prasarana = "";
                                                                                if($entry_prasarana)$entry_prasarana="  (".$entry_prasarana." ".$row_koef->satuan.")";
                                            echo "<tr " . $color . "><td width='30px' bgcolor='#FFFFFF'>&nbsp</td><td>".$row_koef->kategori."</td>
                                                 <td>: ".$data_retribusi->kategori.$entry_prasarana."</td></tr>";
                                        }
                                        echo "</table>";
                                   }

                            else{
                               $property_satuan2 = new trperizinan_trproperty();
                               $property_satuan2->where('trproperty_id', $data->id)->get();
                                echo "<td>".$nprop."</td>";
                                if($data->c_type == '1'){
                                    if($hasil)
                                echo "<td colspan='2'>".$data_koefisien3->kategori." (".$hasil2." ".$property_satuan2->satuan.")</td>";
                                    else
                                echo "<td colspan='2'>".$data_koefisien3->kategori."</td>";
                                }else
                                echo "<td colspan='2'>".$hasil2." ".$property_satuan2->satuan."</td>";
                                }

                            ?>

                            <?php echo "</tr>"; ?>

                            <?php
                                  /*  if ($data->c_type == '1') {
                                        //echo $data_koefisien2->index_kategori;
                                        $z = $z * $data_koefisien2->index_kategori;
                                    }*/
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
<?php
                            echo form_label('Nilai Retribusi', 'nama_izin');
?>
                        </div>

                        <div id="rightMain">
                        <?php
                        if($m_hitung=="1")
                        {
                            if (!empty($hitManualRet->v_tinjauan))
                            {
                                echo "Rp. ".$this->terbilang->nominal($hitManualRet->v_tinjauan, 2);
                                echo form_hidden('nilai_retribusi', $hitManualRet->v_tinjauan); 
                            }
                            else
                            {
                                echo "Rp. ".$this->terbilang->nominal('0', 2);
                                echo form_hidden('nilai_retribusi', '0'); 
                            }
                        }
                        else
                        {
                        if(empty($retribusi)) $retribusi = 0;
                        echo "Rp. ".$this->terbilang->nominal($retribusi, 2);
                        ////$z; ?>
                        <?php echo form_hidden('nilai_retribusi', $retribusi); 
                        }
                        ?>
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
                            if ($status == '1') {
                                $cek = TRUE;
                            } else {
                                $cek = FALSE;
                            }
                            $status1 = array(
                                'name' => "status",
                                'value' => "1",
                                'checked' => $cek
                            );
                            if ($status == '1') {
                                $checked = FALSE;
                            } else {
                                $checked = TRUE;
                            }
                            $status2 = array(
                                'name' => "status",
                                'value' => "2",
                                'checked' => $checked
                            );
                            if($ditetapkan === "1"){
                                echo "<b>";
                                if($status === "1") echo "Diizinkan";
                                else echo "Ditolak";
                                echo "</b>";
                            }else{
                                echo form_radio($status1) . " Diizinkan";
                                echo form_radio($status2) . " Ditolak";
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
             if($ditetapkan !== "1") echo form_submit($save);
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