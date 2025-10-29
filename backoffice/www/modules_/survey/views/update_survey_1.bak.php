<div id="content"
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <legend>Data Permohonan</legend>
                <div id="statusRail">
                  <div id="leftRail" class="bg-grid">
                    <?php
                        echo form_label('No Pendaftaran','no_daftar');
                    ?>
                  </div>
                  <div id="rightRail" class="bg-grid">
                    <?php
                        echo $no_daftar;
                    ?>
                  </div>
                </div>
                <div id="statusRail">
                  <div id="leftRail">
                    <?php
                        echo form_label('Nama Pemohon','nama_pemohon');
                    ?>
                  </div>
                  <div id="rightRail">
                    <?php
                        echo $nama_pemohon;
                    ?>
                  </div>
                </div>
                <div id="statusRail">
                  <div id="leftRail" class="bg-grid">
                    <?php
                        echo form_label('Alamat','alamat');
                    ?>
                  </div>
                  <div id="rightRail" class="bg-grid">
                    <?php
                        echo $alamat_pemohon;
                    ?>
                  </div>
                </div>
                <div id="statusRail">
                  <div id="leftRail">
                    <?php
                        echo form_label('Jenis Izin','jenis_izin');
                    ?>
                  </div>
                  <div id="rightRail">
                    <?php
                        echo $jenis_izin;
                    ?>
                  </div>
                </div>
                <div id="statusRail">
                  <div id="leftRail" class="bg-grid">
                    <?php
                        echo form_label('Lokasi Izin');
                    ?>
                  </div>
                  <div id="rightRail" class="bg-grid">
                    <?php
                        echo $permohonan->a_izin;
                    ?>
                  </div>
                </div>
            </fieldset>
        </div>
        <?php
            //echo $save_method;
            echo form_open('survey/' . $save_method);
            echo form_hidden('id_daftar', $id_daftar);
            echo form_hidden('waktu_awal', $waktu_awal);
            if (isset($from)) {
                echo form_hidden('from', $from);
            }
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Entry</a></li>
                    <?php
                        $izin_kelompok = $permohonan->trperizinan->trkelompok_perizinan->get();
                        if(in_array($izin_kelompok->id,$arr_izin_rekomendasi)){
                    ?>
                    <li><a href="#tabs-2">Data Surat Rekomendasi</a></li>
                    <?php
                        }
                    ?>
                </ul>
                <div id="tabs-1">
                    <table cellpadding="0" cellspacing="0" border="0" class="display">
                        <tr>
                            <td align="left" width="20%"></td>
                            <td align="left" width="20%"></td>
                            <td align="left" width="60%"></td>
                        </tr>
                    <?php
                        $i = 0;
                        $z = 0;
			foreach ($list as $data){
                            $i++;
                            $property_type = $data->c_type; // Input Type [Text]
                            $entry_id = '';
                            $data_entry = '';
                            $data_entry2 = '';
                            $data_koefisien = 0;
                            $data_koefisien2 = 0;
                            //echo $data->n_property.'<br>';
                            
                            if($list_daftar->id){
                                foreach ($list_daftar as $data_daftar){
                        			            
                                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                        ->where('trproperty_id', $data->id)->get();
                                    
                                    if($entry_property->tmproperty_jenisperizinan_id){
                                        //echo $entry_property->tmproperty_jenisperizinan_id.' Property ID : '.$entry_property->trproperty_id.'<br>';
                                    
                                        $entry_daftar = new tmproperty_jenisperizinan();
                                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                                        $entry_id = $entry_daftar->id;
                                        $data_entry = $entry_daftar->v_tinjauan;
                                        $data_koefisien = $entry_daftar->k_tinjauan;
                                        $data_entry2 = $entry_daftar->v_property;
                                        $data_koefisien2 = $entry_daftar->k_property;
                                        
//                            else{
//                                $entry_id = '';
//                                $data_entry = '';
//                                $data_entry2 = '';
//                                $data_koefisien = 0;
//                                $data_koefisien2 = 0;
//                            }
                            $perizinan = $permohonan->trperizinan->get();
                            $kelompok = $perizinan->trkelompok_perizinan->get();
                            $data_relasi = new trperizinan_trproperty();
                            $data_relasi
                            ->where('trproperty_id', $data->id)
                            ->where('trperizinan_id', $perizinan->id)->get();
                            
                            //echo 'PropertyId :'.$data_relasi->trproperty_id.' Parent :'.$data_relasi->c_parent.' Order :'.$data_relasi->c_order.'<br>';
                            
                            if($data_relasi->c_order == 1){
                                echo "<tr>";
                                echo "<td colspan='3'><b>";
                                $parent = new trproperty();
                                $parent->get_by_id($data_relasi->c_parent);
                                echo $parent->n_property;
                                echo "</b></td>";
                                echo "</tr>";
                            }
                            
                            //echo $data_relasi->c_order.' '.$data_relasi->c_parent.'<br>';
                    ?>
                            <tr>
<!--                    <div class="contentForm">-->
                        <?php
                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $perizinan->id)
                        ->where('trproperty_id', $data->id)->get();
                        $id_tl = $izin_property->c_tl_id;
                        //Indra
                        if($id_tl == '1'){
                            $z++;
                            if($z==3) $z = 1;
                            if($z==2) $bg = "";
                            else $bg = "class='bg-grid'";
                            echo form_hidden('property_id[]', $data->id);
                            echo form_hidden('entry_id[]', $entry_id);
                            $property_input = array(
                                'name' => 'property_value[]',
                                'value' => $data_entry,
                                'class' => 'input-wrc'
                            );

                            ##
                            if($data->id == '12'){ //Hanya Property KLASIFIKASI
                                    echo "<td valign='top' ".$bg.">".$data->n_property."</td>";
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();
                                    if($list_koefisien->id){
                                        $xx = 0;
                                        echo "<td ".$bg." colspan='2'>";
                                        echo form_hidden('property_value[]', 0);
                                        echo form_hidden('koefisien_id[]',0);
                                        echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
                                        foreach ($list_koefisien as $row_koef){
                                            $xx++;
                                            if($xx==3) $xx = 1;
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
                                            echo form_hidden('retribusi_id[]', $row_koef->id);
                                            echo form_hidden('klasifikasi_id[]', $klasifikasi_id);
                                            $koef_input = array(
                                                'name' => 'koef_value[]',
                                                'value' => $entry_klasifikasi,
                                                'class' => 'input-wrc'
                                            );

                                            if($xx==2) $bg_2 = "style='background-color: #FFF'";
                                            else $bg_2 = "";
                                            echo "<tr>";
                                            echo "<td ".$bg_2." height='20' width='25%'>".$row_koef->kategori."</td>";
                                            $propkoef = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                                            $propkoef->where('trkoefesientarifretribusi_id', $row_koef->id)->order_by('id', 'ASC')->get();
                                            if($propkoef->id){
                                                foreach ($propkoef as $row_propkoef){
                                                    $data_retribusi = new trkoefisienretribusilev1();
                                                    $data_retribusi->where('id', $row_propkoef->trkoefisienretribusilev1_id)->get();
                                                    $opsi_koef_klasifikasi[$data_retribusi->id] = $data_retribusi->kategori." - ".$data_retribusi->index_kategori."";
                                                }
                                            }else $opsi_koef_klasifikasi = array(''  => '',);
                                            echo "<td ".$bg_2." width='25%'>".form_dropdown('koef_id[]', $opsi_koef_klasifikasi,
                                                 $koef_klasifikasi, 'class = "input-select-wrc"')."</td>";
            //                                echo "&nbsp;&nbsp;";
                                            echo "<td ".$bg_2." width='50%'>".form_input($koef_input)." ".$row_koef->satuan."</td>";
                                            $opsi_koef_klasifikasi = '';
                                            echo "</tr>";
                                            echo form_hidden('koef_value2[]', $entry_klasifikasi2);
                                            echo form_hidden('koef_id2[]', $koef_klasifikasi2);
                                        }
                                        echo "</table></td>";
                                    }
                            ##
                            }else if($data->id == '29'){ //Hanya Property PRASARANA
                                    echo "<td valign='top' ".$bg.">".$data->n_property."</td>";
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();
                                    if($list_koefisien->id){
                                        $xx = 0;
                                        echo "<td ".$bg." colspan='2'>";
                                        echo form_hidden('property_value[]', 0);
                                        echo form_hidden('koefisien_id[]',0);
                                        echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
                                        foreach ($list_koefisien as $row_koef){
                                            $xx++;
                                            if($xx==3) $xx = 1;
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
                                            echo form_hidden('retribusi_id3[]', $row_koef->id);
                                            echo form_hidden('prasarana_id[]', $prasarana_id);
                                            $koef_input3 = array(
                                                'name' => 'koef_value3[]',
                                                'value' => $entry_prasarana,
                                                'class' => 'input-wrc'
                                            );

                                            if($xx==2) $bg_2 = "style='background-color: #FFF'";
                                            else $bg_2 = "";
                                            echo "<tr>";
                                            echo "<td ".$bg_2." height='20' width='25%'>".$row_koef->kategori."</td>";
                                            $propkoef = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                                            $propkoef->where('trkoefesientarifretribusi_id', $row_koef->id)->order_by('id', 'ASC')->get();
                                            if($propkoef->id){
                                                foreach ($propkoef as $row_propkoef){
                                                    $data_retribusi = new trkoefisienretribusilev1();
                                                    $data_retribusi->where('id', $row_propkoef->trkoefisienretribusilev1_id)->get();
                                                    $opsi_koef_prasarana[$data_retribusi->id] = $data_retribusi->kategori." - ".$data_retribusi->index_kategori."";
                                                }
                                            }else $opsi_koef_prasarana = array(''  => '',);
                                            echo "<td ".$bg_2." width='25%'>".form_dropdown('koef_id3[]', $opsi_koef_prasarana,
                                                 $koef_prasarana, 'class = "input-select-wrc"')."</td>";
            //                                echo "&nbsp;&nbsp;";
                                            echo "<td ".$bg_2." width='50%'>".form_input($koef_input3)." ".$row_koef->satuan."</td>";
                                            $opsi_koef_prasarana = '';
                                            echo "</tr>";
                                            echo form_hidden('koef_value4[]', $entry_prasarana2);
                                            echo form_hidden('koef_id4[]', $koef_prasarana2);
                                        }
                                        echo "</table></td>";
                                    }
                            ##
                            }else{
                                $property_satuan = new trperizinan_trproperty();
                                $property_satuan->where('trproperty_id', $data->id)->get();
                                if($property_type == '1'){ // Combo Box
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();
                                    if($list_koefisien->id){
                                        foreach ($list_koefisien as $row){
                                            if($kelompok->id == 4)
                                            $opsi_koefisien[$row->id] = $row->kategori." - ".$row->index_kategori;
                                            else
                                            $opsi_koefisien[$row->id] = $row->kategori;
                                        }
                                    }else $opsi_koefisien = array(''  => '',);
                                    echo "<td ".$bg.">".$data->n_property."</td>";
                                    echo "<td ".$bg.">".form_dropdown('koefisien_id[]', $opsi_koefisien,
                                         $data_koefisien, 'class = "input-select-wrc"')."</td>";
    //                                echo "&nbsp;&nbsp;";
                                    echo "<td ".$bg.">".form_input($property_input)." ".$property_satuan->satuan."</td>";
                                    $opsi_koefisien = '';
                                }else if($property_type == '2'){
//                                    echo "<td ".$bg."><b>".$data->n_property."</b></td>";
                                    echo "<td></td>";
                                    echo "<td>".form_hidden('property_value[]', 0)."</td>";
                                    echo "<td>".form_hidden('koefisien_id[]',0)."</td>";
                                }else{
                                    echo "<td ".$bg.">".$data->n_property."</td>";
                                    echo "<td ".$bg." colspan='2'>".form_input($property_input)." ".$property_satuan->satuan
                                         .form_hidden('koefisien_id[]',0)."</td>";
                                }
                            }
                            echo form_hidden('property_value2[]', $data_entry2);
                            echo form_hidden('koefisien_id2[]', $data_koefisien2);

                        }
                        ?>
<!--                        <br style="clear: both;" />-->
                            </tr>
<!--                    </div>-->
                    <?php
                                    }
                                }
                            }
                        }
                    ?>
                    </table>
<!--                    <br style="clear: both;" />-->
                </div>
                <?php
                    if($izin_kelompok->id == '1' || $izin_kelompok->id == '3'){
                ?>
                <div id="tabs-2">
                    <?php
                    echo form_label('No Surat');
                    $no_surat_input = array(
                        'name' => 'no_surat',
                        'value' => $no_surat,
                        'class' => 'input-wrc',
                        'id' => 'no_surat'
                    );
                    echo form_input($no_surat_input);
                    ?>
                    <br style="clear: both;" />
                    <?php
                    echo form_label('Tanggal Surat');
                    $tgl_surat_input = array(
                        'name' => 'tgl_surat',
                        'value' => $tgl_surat,
                        'class' => 'input-wrc',
                        'id' => 'tgl_surat'
                    );
                    echo form_input($tgl_surat_input);
                    ?>
                    <br style="clear: both;" />
                    <?php
                    echo form_label('Deskripsi');
                    $deskripsi_input = array(
                        'name' => 'deskripsi',
                        'value' => $deskripsi,
                        'class' => 'inputarea-wrc',
                        'id' => 'deskripsi'
                    );
                    echo form_textarea($deskripsi_input);
                    ?>
                    <br style="clear: both;" />
                </div>
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
            echo form_submit($add_daftar);
            echo "<span></span>";
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('survey/result') . '\''
            );
            echo form_button($cancel_daftar);
            echo "<span></span>";
//            $cancel = array(
//                'name' => 'button',
//                'class' => 'button-wrc',
//                'content' => 'Hapus Entry Data',
//                'onclick' => 'parent.location=\''. site_url('survey/cancel') . "/" . $id_daftar . '\''
//            );
//            echo form_button($cancel);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
