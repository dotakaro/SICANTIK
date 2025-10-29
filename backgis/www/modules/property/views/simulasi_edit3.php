 <script type="text/javascript" charset="utf-8">
              function hitung(){
                          var retribusi = document.getElementById('retribusi_id').value;
                          var jumlah    = document.getElementById('jumlahkoef').value;
                          var property  = document.getElementById('property_id').value;
                          var index     = document.getElementById('index_koef').value;
                          var jumlah_kecil  = document.getElementById('jumlah_kecil').value;
                          var jumlah_kecil2 = document.getElementById('jumlah_kecil2').value;

                          var koef ;    var koef1  = 1;
                          var koeff;    var koeff3;
                          var kali = 1; var i = 1;
                          var nilai;    var tambah = 0;
                          var tambah2 = 0;

                          for(i;i<jumlah_kecil;i++)
                              {
                                 var zz = 'koefisien2_id' + i;
                                 koeff  = document.getElementById(zz).value;
                                 tambah = tambah + (index * koeff);
                           
                              }

                          for(i;i<jumlah_kecil2;i++)
                              {
                                 var zzz = 'koefisien3_id' + i;
                                 koeff3  = document.getElementById(zz).value;
                                 var sss = 'nilai_input' +i;
                                 nilai   = document.getElementById(sss).value;
                                 tambah2 = tambah2 + (nilai * koeff3);

                              }
                          for(i;i<jumlah;i++)//jumlah = 7
                              {
                                   var yy = 'koefisien_id' + i;
                                   koef  = document.getElementById(yy).value;
                                   if(property === '12'){
                                   koef = tambah;}
                                   else if(property === '29'){
                                   koef = tambah2;}
                               
                                   koef1 = koef1*koef;
                                   
                                   
                              }
                          //
                          var total = Math.round(retribusi * koef1);

                          //alert("Total Retribusi Yang harus dibayarkan Rp. "+total+",-");
                          alert("nilai looping kecil "+tambah+"nilai looping kedua"+tambah2+"Total Retribusi Yang harus dibayarkan Rp. "+total+",-");

                 }
 </script>


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

                        <input type="hidden" name="retribusi_id" id="retribusi_id" value="<?php echo $retribusix->v_retribusi;?>">
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
                          <input type="text" name="luas" id="luas">

                      </div>
                      </div>
                      <br><br>
                    <?php
                    foreach ($list as $data) {
                        $i++;
                        $property_type = $data->c_type; // Input Type [Text]
                        echo form_hidden('property', $data->c_type);

                        if ($list_izin->id) {
                            foreach ($list_izin as $data_daftar) {

                                $entry_property = new tmproperty_jenisperizinan_trproperty();
                                $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                        ->where('trproperty_id', $data->id)->get();
                                if ($entry_property->tmproperty_jenisperizinan_id) {
                                    $entry_daftar = new tmproperty_jenisperizinan();
                                    $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                                    $entry_id       = $entry_daftar->id;
                                    $data_entry     = $entry_daftar->v_tinjauan;
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
                        <input type="hidden" id="property_id" value="<?php echo $data->id;?>" name="property_id" >
                            <?php

                        if ($i % 2 == 0) {
                            $colour = " class='bg-grid'";
                        } else {
                            $colour = "#FFFFFF";
                        }
                        echo form_hidden('property_id', $data->id);
                     
                        if ($property_type == '1') {
                            $jumlah = 1;
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
                                    $opsi_koefisien[$row->index_kategori] = $row->kategori;
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
                                      
                                                                           
                                        echo "<tr><td width='275px' bgcolor=" . $colour . ">" . $row_koef->kategori ."</td>";?><input type="hidden" id="index_koef" value="<?php echo $row_koef->index_kategori?>"><?php

                                        $propkoef = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                                        $propkoef->where('trkoefesientarifretribusi_id', $row_koef->id)->order_by('id', 'ASC')->get();

                                        if ($propkoef->id) {
                                            foreach ($propkoef as $row_propkoef) {

                                                $data_retribusi = new trkoefisienretribusilev1();
                                                $data_retribusi->where('id', $row_propkoef->trkoefisienretribusilev1_id)->get();
                                                // if($row_propkoef->id){
                                                $opsi_koef_klasifikasi[$data_retribusi->index_kategori] = $data_retribusi->kategori;
                                                //}
                                            }
                                        }
                                        echo "<td bgcolor=" . $colour . ">" . form_dropdown('koef_id[]', $opsi_koef_klasifikasi,
                                                '', 'class = "input-select-wrc" id="koefisien2_id' . $p . '"') . "</td></tr>";
                                        $opsi_koef_klasifikasi = NULL;
                                    }
                                }
                                echo "</table>";
                                ?> <input type="hidden" id="jumlah_kecil" value="<?php echo $jumlah_kecil;?>"><?php
                            }

                            //untuk prasarana kedua
                          /*   else if ($data->id == '29') {
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
                                        $prasarana_id     = '';
                                        $entry_prasarana  = '';
                                        $koef_prasarana   = 0 ;
                                        $entry_prasarana2 = '';
                                        $koef_prasarana2  = 0 ;

                                        if ($list_prasarana->id) {
                                            foreach ($list_prasarana as $data_prasarana) {
                                                $jumlah_kecil = $p;

                                                $entry_koefisien = new tmproperty_prasarana_trkoefesientarifretribusi();
                                                $entry_koefisien->where('tmproperty_prasarana_id', $data_prasarana->id)
                                                        ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                                if ($entry_koefisien->tmproperty_prasarana_id) {
                                                    $entry_daftar_prasarana = new tmproperty_prasarana();
                                                    $entry_daftar_prasarana->get_by_id($entry_koefisien->tmproperty_prasarana_id);

                                                    $prasarana_id       = $entry_daftar_prasarana->id;
                                                    $entry_prasarana    = $entry_daftar_prasarana->v_tinjauan;
                                                    $koef_prasarana     = $entry_daftar_prasarana->k_tinjauan;
                                                    $entry_prasarana2   = $entry_daftar_prasarana->v_prasarana;
                                                    $koef_prasarana2    = $entry_daftar_prasarana->k_prasarana;
                                                }
                                            }
                                        } else {
                                            $prasarana_id     = '';
                                            $entry_prasarana  = '';
                                            $koef_prasarana   = 0 ;
                                            $entry_prasarana2 = '';
                                            $koef_prasarana2  = 0 ;
                                        }


                                        echo "<tr><td width='275px' bgcolor=" . $colour . ">" . $row_koef->kategori ."</td>";?><input type="hidden" id="index_koef" value="<?php echo $row_koef->index_kategori?>"><?php

                                        $propkoef = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                                        $propkoef->where('trkoefesientarifretribusi_id', $row_koef->id)->order_by('id', 'ASC')->get();

                                        if ($propkoef->id) {
                                            foreach ($propkoef as $row_propkoef) {

                                                $data_retribusi = new trkoefisienretribusilev1();
                                                $data_retribusi->where('id', $row_propkoef->trkoefisienretribusilev1_id)->get();
                                                // if($row_propkoef->id){
                                                $opsi_koef_prasarana[$data_retribusi->index_kategori] = $data_retribusi->kategori;
                                                //}
                                            }
                                        }
                                        echo "<td bgcolor=" . $colour . ">" . form_dropdown('koef_id[]', $opsi_koef_prasarana,
                                                $koef_prasarana, 'class = "input-select-wrc" id="koefisien2_id' . $p . '"') . "</td></tr>";
                                        $opsi_koef_prasarana = NULL;
                                    }
                                }
                                echo "</table>";
                                ?> <input type="hidden" id="jumlah_kecil" value="<?php echo $jumlah_kecil;?>"><?php
                            }

                            */



                            //untuk prasarana
                        /*    else if ($data->id == '29') {
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
                                      

                                        echo "<tr><td width='275px' bgcolor=" . $colour . ">" . $row_koef2->kategori . "</td>";
                                        $opsi_koef_prasarana = NULL;
                                        $propkoef = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                                        $propkoef->where('trkoefesientarifretribusi_id', $row_koef2->id)->order_by('id', 'ASC')->get();
                                        if ($propkoef->id) {
                                            foreach ($propkoef as $row_propkoef2) {

                                                $data_retribusi = new trkoefisienretribusilev1();
                                                $data_retribusi->where('id', $row_propkoef2->trkoefisienretribusilev1_id)->get();
                                                $opsi_koef_klasifikasi[$data_retribusi->index_kategori] = $data_retribusi->kategori;
                                            //    echo $data_retribusi->id.'-'.$data_retribusi->kategori.'<br>';
                                            }
                                        }
                                  //      echo "<td bgcolor=" . $colour . ">" . form_dropdown('koef_id[]', $opsi_koef_prasarana,
                                    //           ' ', 'class = "input-select-wrc" id="koefisien3_id' . $q . '"') ."   ".
                                      //          "<input type='text' name='luas' id='nilai_input$q'>".
                                        //        "</td></tr>";
                                    

                                        echo "<td bgcolor=" . $colour . ">" . form_dropdown('koef_id[]', $opsi_koef_klasifikasi,
                                                '', 'class = "input-select-wrc" id="koefisien2_id' . $p . '"') . "</td></tr>";
                                        $opsi_koef_klasifikasi = NULL;
                                    }
                                }
                                echo "</table>";?> <input type="hidden" id="jumlah_kecil2" value="<?php echo $jumlah_kecil2;?>"><?php
                            }
                          */

                            else if($data->id == '29'){ //Hanya Property PRASARANA
                                    echo "<td valign='top' ".$bg.">".$data->n_property."</td>";
                                    $list_koefisien = new trkoefesientarifretribusi();
                                    $list_koefisien->where_related($data)->get();
                                    if($list_koefisien->id){
                                        $xx = 0;
                                        echo "<td ".$bg." colspan='2'>";
                                        echo "<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
                                        foreach ($list_koefisien as $row_koef){
                                            $xx++;
                                            if($xx==3) $xx = 1;
                                                   $koef_input3 = array(
                                                'name' => 'koef_value3[]',
                                                'value' => '',
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
                                                    $data_retribusi2 = new trkoefisienretribusilev1();
                                                    $data_retribusi2->where('id', $row_propkoef->trkoefisienretribusilev1_id)->get();
                                                    $opsi_koef_prasarana[$data_retribusi2->index_kategori] = $data_retribusi2->kategori;
                                                }
                                            }else $opsi_koef_prasarana = array(''  => '',);
                                            echo "<td ".$bg_2." width='25%'>".form_dropdown('koef_id3[]', $opsi_koef_prasarana,
                                                 '', 'class = "input-select-wrc"')."</td>";
            //                                echo "&nbsp;&nbsp;";
                                            echo "<td ".$bg_2." width='50%'>".form_input($koef_input3)."</td>";
                                            $opsi_koef_prasarana = '';
                                            echo "</tr>";

                                        }
                                        echo "</table></td>";
                                    }
                            ##
                            }



                            else { //
                                echo form_dropdown('koefisien_id', $opsi_koefisien,
                                        $entry_id, 'class = "input-select-wrc" id="koefisien_id' . $x . '"');
                            }//


                            echo "</div>";



                            $opsi_koefisien = '';
                            echo '<br style="clear: both;" >';
                        }
                        ?>
                       
                        <?php
                        if ($property_type == '2') {
                            '<b>' . $data->n_property . '</b>' ;
                            echo form_hidden('koefisien_id', 1);
                        } else {
                            echo form_hidden('koefisien_id', 1);
                            // $jumlah = 1;
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
                'type' => 'button',
                'value' => 'Hitung Retribusi',
                'onclick' => 'hitung()',

            );
            echo form_submit($add_daftar);
            echo "<span></span>";
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Kembali',
                'onclick' => 'parent.location=\''. site_url('property/simulasi') . '\''
            );
            echo form_button($cancel_daftar);
            echo form_close();
            ?>


        </div>






<br style="clear: both;" >
    </div></div>