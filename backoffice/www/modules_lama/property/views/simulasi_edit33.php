<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">


                 <fieldset id="half">
                      <legend>Data Perizinan</legend>
            <?php
            echo form_open('property/simulasi/hitung');
                            $jumlah = 1;

        ?>

             <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nama Izin','nama_izin');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $jenis_izin->n_perizinan;
                        ?>
                        <?php echo form_hidden('izin_id',$jenis_izin->id);?>
                      </div>
              </div>
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Harga Retribusi','harga retribusi');
                        ?>
                      </div>
                      <div id="rightRail">
                        Rp<?php echo $retribusix->v_retribusi;?>,00
                        <input type="hidden" name="harga" id="retribusi_id" value="<?php echo $retribusix->v_retribusi;?>">
                      </div>
                    </div>
             </fieldset>

    </div>
        <div class="entry">
            <div id="tabs">

                <ul>
                    <li><a href="#tabs-1">Data Entry</a></li>
                </ul>
                <div id="tabs-1">
                     <fieldset  style="border-style: none">
                     <div id="statusMain">
                   <?php
                    $i = 0;
                    $z = 1;
                    $x = 0;
                    if ($x % 2 == 0) {
                        $bg = "";
                    } else {
                        $bg = "bg-grid";
                    }
                    ?>
                      <div id="statusMain">
                      <div id="leftMain" class="bg-grid">
                        <?php
                            echo form_label('LUAS BANGUNAN','luas_bangunan');
                        ?>
                      </div>
                      <div id="rightMain" class="bg-grid">
                        <?php
                        $luas_input = array(
                            'name' => 'luasbangunan',
                            'value' => '',
                            'class' => 'input-wrc'
                        );
                        echo form_input($luas_input);
                        
                        ?>
                      </div>
                      </div>
                      <br><br>
                    <?php
                    foreach ($list as $data) {
                        $i++;
                        $property_type = $data->c_type; // Input Type [Text]
                                                                                                                    //  echo form_hidden('property', $data->c_type);
                        if ($list_izin->id) {
                            foreach ($list_izin as $data_daftar) {

                                $entry_property = new tmproperty_jenisperizinan_trproperty();
                                $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                        ->where('trproperty_id', $data->id)->get();
                                if ($entry_property->tmproperty_jenisperizinan_id) {
                                    $entry_daftar = new tmproperty_jenisperizinan();
                                    $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                                    $entry_id = $entry_daftar->id;
                                    $data_entry = $entry_daftar->v_tinjauan;
                                    $data_koefisien = $entry_daftar->k_tinjauan;
                                }
                            }
                        } else {
                            $entry_id       = '';
                            $data_entry     = '';
                            $data_koefisien = 0 ;
                        }
?>
                        <div class="contentForm">
<!--                        <input type="hidden" id="property_id" value="<?php echo $data->id;?>" name="property_id[]" >-->

                            <?php
                            echo form_hidden('entry_id[]', $entry_id);
                            if ($i % 2 == 0) {
                                $colour = " class='bg-grid'";
                            } else {
                                $colour = "#FFFFFF";
                            }
                        
                        if ($property_type == '1') {
                           echo form_hidden('property_id[]', $data->id);
                           
                            //echo form_hidden('koef_id1[]', 1);
                            
                            echo "<div id='leftMain'.$colour.>";
                            echo $data->n_property;
                            $jumlah = $jumlah * $x;
                            $x++;
                            echo "</div>";

                            $list_koefisien = new trkoefesientarifretribusi();
                            $list_koefisien->where_related($data)->get();
                            if ($data->id === "2") {
                                echo "test";
                            }
                            if ($list_koefisien->id) {
                                foreach ($list_koefisien as $row) {
                                    $opsi_koefisien[$row->id] = $row->kategori;
                                }
                                $koef = new trkoefesientarifretribusi();
                                $koef->get_by_id($row->id);

                                $z = $z * $koef->index_kategori;
                            }
                            else
                                $opsi_koefisien = array('' => '',);

                            echo "<div id='rightMain' .$colour.>";



                            ?>

                            <?php
                            if ($data->id == '12') {
                                echo form_hidden('koef_id1[]', 0);
                                $jumlah_kecil=1;
                                $p = 0;
                                if ($p % 2 == 0) {
                                    $colour = " class='bg-grid'";
                                } else {
                                    $colour = "style='background-color:#FFFFFF";
                                }
                                echo "<table border='0'>";
                                $list_koefisien = new trkoefesientarifretribusi();
                                $list_koefisien->where_related($data)->get();
                                if ($list_koefisien->id) {

                                    foreach ($list_koefisien as $row_koef) {
                                        $p++;
                                        echo "<tr ><td width='275px'>" . $row_koef->kategori ."</td>";?><input type="hidden" id="index_koef" value="<?php echo $row_koef->index_kategori?>"><?php
                                        $propkoef = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                                        $propkoef->where('trkoefesientarifretribusi_id', $row_koef->id)->order_by('id', 'ASC')->get();

                                        if ($propkoef->id) {
                                            foreach ($propkoef as $row_propkoef) {

                                                $data_retribusi = new trkoefisienretribusilev1();
                                                $data_retribusi->where('id', $row_propkoef->trkoefisienretribusilev1_id)->get();
                                              
                                                $opsi_koef_klasifikasi[$data_retribusi->id] = $data_retribusi->kategori;
                                               
                                            }
                                        }
                                        echo "<td>" . form_dropdown('koef_id2[]', $opsi_koef_klasifikasi,
                                                '', 'class = "input-select-wrc" id="koefisien2_id' . $p . '"') . "</td></tr>";
                                        $opsi_koef_klasifikasi = NULL;

                                         echo form_hidden('retribusi_id[]', $row_koef->id);
                                    }
                                }
                                echo "</table>";
                                ?> <input type="hidden" id="jumlah_kecil" value="<?php echo $jumlah_kecil;?>"><?php
                            }

                           //untuk prasarana
                            else if ($data->id == '29') {
                                 echo form_hidden('koef_id1[]', 0);
                                $q = 0;$jumlah_kecil2 = 1;
                                if ($q % 2 == 0) {
                                    $colour = " class='bg-grid'";
                                } else {
                                    $colour = "style='background-color:#FFFFFF";
                                }
                                echo "<table border='0'>";

                                $list_koefisien = new trkoefesientarifretribusi();
                                $list_koefisien->where_related($data)->get();
                                if ($list_koefisien->id) {
                                    
                                    foreach ($list_koefisien as $row_koef2) {
                                        $q++;
                                        echo "<tr><td width='275px'>" . $row_koef2->kategori . "</td>";
                                      
                                        $propkoef2 = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                                        $propkoef2->where('trkoefesientarifretribusi_id', $row_koef2->id)->order_by('id', 'ASC')->get();
                                        if ($propkoef2->id) {
                                            $opsi_koef_prasarana = NULL;
                                            foreach ($propkoef2 as $row_propkoef2) {

                                                $data_retribusi2 = new trkoefisienretribusilev1();
                                                $data_retribusi2->where('id', $row_propkoef2->trkoefisienretribusilev1_id)->get();
                                                $opsi_koef_prasarana[$data_retribusi2->id] = $data_retribusi2->kategori;
                                               
                                                }

                                        }

                                        
                                        echo "<td>" . form_dropdown('koef_id3[]', $opsi_koef_prasarana,
                                             '', 'class = "input-select-wrc" id="koefisien3_id' . $q . '"') ."  ".
                                             "<input type='text' name='koef_value3[]' value='' id='nilai_input$q'>&nbsp;".$row_koef2->satuan.
                                                                                                                                            // $koef_input3 = array(
                                                                                                                                            //                'name' => 'koef_value3[]',
                                                                                                                                            //                'value' => '',
                                                                                                                                            //                'class' => 'input-wrc'
                                                                                                                                            //                );
                                                                                                                                            // echo form_input($koef_input3).
                                            "</td></tr>";
                                    
                                      echo form_hidden('retribusi_id2[]', $row_koef2->id);
                                                   }
                                                    
                                                  
                                }
                                echo "</table>"; 
                                ?>
                            <input type="hidden" id="jumlah_kecil2" value="<?php echo $jumlah_kecil2;?>"><?php
                            }
                          
                            
                            else { //
                              
                                echo form_dropdown('koef_id1[]', $opsi_koefisien,
                                       $data_koefisien, 'class = "input-select-wrc" id="koefisien_id' . $x . '"');
                                
                            }//


                            echo "</div>";



                            $opsi_koefisien = '';
                            echo '<br style="clear: both;" >';
                        }
                        ?>

                        <?php
                        if ($property_type == '2') {
                            '<b>' . $data->n_property . '</b>' ;
                            echo form_hidden('koef_id1[]', 0);
                            echo form_hidden('property_id[]', 0);
                        
                       }  else {
                            
                         }


                        ?>
     </div>

                    <?php
                         }
                    ?>
                            </div>
               </fieldset>
</div>

            </div>
        </div>
        <p style="padding-left: 35px; font-size:14px">



        </p>


        <div class="entry" style="text-align: center;">

            <input type="hidden" name="jumlahkoef" value="<?php echo $jumlah + 1;?>" id="jumlahkoef">

 <?php
            $add_daftar = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
           'value' => 'Hitung Retribusi',
       
            );
            echo form_submit($add_daftar);
            echo form_close();
            echo "<span></span>";
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Kembali',
                'onclick' => 'parent.location=\''. site_url('property/simulasi') . '\''
            );
            echo form_button($cancel_daftar);
            
            ?>


        </div>






<br style="clear: both;" >
    </div></div>