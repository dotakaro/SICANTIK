<script type="text/javascript" charset="utf-8">
              function hitung(){
                   var jumlah2    = document.getElementById('jumlah2').value;
                   var jumlah3    = document.getElementById('jumlah3').value;
                   var jumlahh    = document.getElementById('jumlahh').value;
                   var harga      = document.getElementById('harga_retribusi').value;
                   var luas       = document.getElementById('luas').value;

                   var i = 1; var ii = 1;
                   var koefa; var koefb;  var tambah = 0;
                   var koefaa; var koefbb; var koefbbb; var koefcc;  var tambahh = 0; var cek =0;
                   var koeff; var kali = 1;

                   //jumlah pertama
                   for(i;i<jumlah2;i++)
                              {
                                 var zzz = 'index2a' + i;
                                 var zz = 'index2b' + i;
                                 koefa  = document.getElementById(zzz).value;
                                 koefb  = document.getElementById(zz).value;
                                 tambah = tambah + (koefa * koefb);

                              }

                 //jumlah kedua
                 for(ii;ii<jumlah3;ii++)
                              {  var xxxx= 'index3bb'+ ii;
                                 var xxx = 'index3a' + ii;
                                 var xx  = 'index3b' + ii;
                                 var x   = 'index3c' + ii;
                                 koefaa  = document.getElementById(xxx).value;
                                 koefbb  = document.getElementById(xx).value;
                                 koefcc  = document.getElementById(x).value;
                                 koefbbb = document.getElementById(xxxx).value;
                                 
                                 tambahh = tambahh + (koefaa * koefbb * koefcc * koefbbb);
                                 
                            // alert("test"+i+".");
                         }

                  //jumlah ketiga
                  for(i;i<jumlahh;i++)
                      {
                          var y = 'koef' + i;
                          koeff = document.getElementById(y).value;
                          kali = kali * koeff;
                      }

                      var total = Math.round((luas*tambah*kali*harga)+tambahh);
                    alert("Total Retribusi Yang harus dibayarkan Rp. "+total+",00");
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

                        <input type="hidden" name="retribusi_id" id="harga_retribusi" value="<?php echo $retribusix->v_retribusi;?>">
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
                      <div id="leftMain">
                          <b>
                        <?php
                            echo form_label('LUAS BANGUNAN','luas_bangunan');
                        ?>
                              </b>
                      </div>
                      <div id="rightMain">
                      <?php echo $luas; ?>.00&nbsp;&nbsp;M<sup>2</sup>
                          <input type="hidden" name="luas" value="<?php echo $luas;?>" id="luas">
                      </div>
                      </div>
                         
                    <div class="contentForm">
   <?php
        $entry_len = count($property_id);
        $entry_lena = count($koef_id1);
        $is_array = NULL;
        $updated = FALSE;
        $bar=0;$no=0;
                    if ($bar % 2 == 0)  {
                                            $colour = " class='bg-grid'";
                                        } else {
                                            $colour = "#FFFFFF";
                                        }
        for($i=0;$i < $entry_len;$i++) {
            
            if($is_array !== $property_id[$i]) {
                $relasi_entry = new trproperty();
                $relasi_entry->get_by_id($property_id[$i]);
                $property_type = $relasi_entry->c_type;
                $relasi_kontribusi = new trkoefesientarifretribusi();
                $relasi_kontribusi->get_by_id($koef_id1[$i]);
                
                $a = $relasi_kontribusi->kategori;
                $b = $relasi_kontribusi->index_kategori;
                

                        if ($property_type == '1') {
                           $no++;
                            echo "<div id='leftMain'>";
                            echo "<b>".$relasi_entry->n_property."</b>";
                            echo "</div>";

                            echo "<div id='rightMain'>";
                    $entry_data = new tmproperty_jenisperizinan();
                    if($relasi_entry->id == '12'){ //Hanya untuk KLASIFIKASI

                    $klasifikasi_len = count($retribusi_id);
                    $is_array_klasifikasi = NULL;
                    $no2=0;
                    echo "<table border='0'>";
                    for($z=0;$z < $klasifikasi_len;$z++) {
                        $no2++;
                        if($is_array_klasifikasi !== $retribusi_id[$z]) {
                            $relasi_klasifikasi = new trkoefesientarifretribusi();
                            $relasi_klasifikasi->get_by_id($retribusi_id[$z]);
                            $relasi_level = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                            $relasi_level->where('trkoefesientarifretribusi_id',$retribusi_id[$z])->get();
                            
                            $relasi_level1 = new trkoefisienretribusilev1();
                            //$relasi_level1->get_by_id($relasi_level->trkoefisienretribusilev1_id);
                            $relasi_level1->get_by_id($koef_id2[$z]);
                            echo "<tr>";
                            echo "<td width='300px'>"."<b>".$relasi_klasifikasi->kategori."</b></td>";
                            echo "<td width='140px'>".$relasi_level1->kategori."<br></td>";
                            echo "<input type='hidden' id='index2a$no2' value='$relasi_klasifikasi->index_kategori'>";
                            echo "<input type='hidden' id='index2b$no2' value='$relasi_level1->index_kategori'>";
                            echo "<td width='40px'>".$relasi_level1->index_kategori."</td>";
                            echo "</tr>";

                            
                        }
                        $is_array_klasifikasi = $retribusi_id[$z];
                    }
                    echo "</table>";
                    $jumlah2 = $no2 + 1;
                    echo "<input type='hidden' id='jumlah2' value='$jumlah2'>";
                
                    echo "<br>";
                }else if($relasi_entry->id == '29'){ //Hanya untuk PRASARANA

                    $prasarana_len = count($retribusi_id2);
                    $is_array_prasarana = NULL;

                    $no3=0;
                    echo "<table border='0'>";
                    for($x=0;$x < $prasarana_len;$x++) {
                        $no3++;
                        if($is_array_prasarana !== $retribusi_id2[$x]) {
                            $relasi_prasarana = new trkoefesientarifretribusi();
                            $relasi_prasarana->get_by_id($retribusi_id2[$x]);
                            $relasi_level = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                            $relasi_level->where('trkoefesientarifretribusi_id',$retribusi_id2[$x])->get();
                            $relasi_level1 = new trkoefisienretribusilev1();
                            $relasi_level1->get_by_id($koef_id3[$x]);
                            echo "<tr>";
                            echo "<td width='300px'>"."<b>".$relasi_prasarana->kategori."</b></td>";
                            echo "<td width='140px'>".$relasi_level1->kategori."</td>";
                            echo "<input type='hidden' id='index3a$no3' value='$relasi_prasarana->index_kategori'>";
                            echo "<input type='hidden' id='index3b$no3' value='$relasi_level1->index_kategori'>";
                            echo "<input type='hidden' id='index3bb$no3' value='$relasi_level1->v_index_kategori'>";
                            echo "<td width='40px'>".$relasi_level1->index_kategori."</td>";
                            echo "<td width='40px'>".$koef_value3[$x]."&nbsp;&nbsp;&nbsp;".$relasi_prasarana->satuan."</td>";
                            echo "<input type='hidden' id='index3c$no3' value='$koef_value3[$x]'>";
                            echo "</tr>";

                            $index2[$x] = $relasi_prasarana->index_kategori;
                            $index2 = $index2[$x]."<br>";
                            
                        }
                        $is_array_prasarana = $retribusi_id2[$x];
                    }
                     echo "</table>";
                    $jumlah3 = $no3 + 1;
                    echo "<input type='hidden' id='jumlah3' value='$jumlah3'>";
                     echo "<b>-----------------------------------------------------------------------------------------------------------------------------------------------------------------</b><br>";
                     echo "<table border='0' align='center'><tr><td><b>Total retribusi yang harus dibayar</td><td><b>Rp.".$total.",00</b></td></tr></table>";
                           
              
                }else{      
                            echo $a;
                            echo "<input type='hidden' id='koef$no' value='$b'>";
                            
                }
                           
                        echo "</div>";
                        }
                        if ($property_type == '2') {
                            '<b>' . $relasi_entry->n_property . '</b>' ;
                        }
                

            }
            $is_array = $property_id[$i];
        }
                            $jumlahh = $no - 1;
                            echo "<input type='hidden' id='jumlahh' value='$jumlahh'>";
                            ?>

                      
                      </div>

        


                      <div id="statusMain" >
                      <div id="leftMain">
                       
                      </div>
                          <div id="rightMain" align="left" style="padding-left: 450px">

 <?php
                        echo "<table border='0'>";
                        echo "<tr>";
                        echo "<td>";
                            $add_daftar = array(
                            'name' => 'button',
                            'class' => 'submit-wrc',
                            'content' => 'Nilai Retribusi',
                            'type' => 'button',
                            'value' => 'Hitung Retribusi',
                            'onclick' => 'hitung()',

                        );
                        echo form_button($add_daftar);
                        echo form_close();
                        echo "<td>";
                        echo form_open('property/simulasi/cetak_simulasi_imb/');
                        echo form_hidden('property_id',$property_id);
                        echo form_hidden('koef_id1',$koef_id1); //id koefisien
                        echo form_hidden('entry_id',$entry_id);//id tmproperty_jenisperizinan
                        echo form_hidden('retribusi_id',$retribusi_id);
                        echo form_hidden('koef_id2',$koef_id2);
                        echo form_hidden('retribusi_id2',$retribusi_id2);
                        echo form_hidden('koef_value3',$koef_value3);
                        echo form_hidden('koef_id3',$koef_id3);

                        echo form_hidden('luas',$luas);
                        echo form_hidden('harga',$retribusix->v_retribusi);
                        echo form_hidden('hargaharusbayar',$total);

                        $cetak_daftar = array(
                            'name' => 'submit',
                            'class' => 'submit-wrc',
                            'content' => 'Cetak Retribusi',
                            'type' => 'submit',
                            
                          //'onclick' => 'parent.location=\''. site_url('property/simulasi/cetak_simulasi_imb') . '\''


                        );
                        echo form_button($cetak_daftar);
                        echo form_close();

                        echo "<td>";
                        echo "<span></span>";
                        $cancel_daftar = array(
                            'name' => 'button',
                            'class' => 'button-wrc',
                            'content' => 'Kembali',
                            'onclick' => 'parent.location=\''. site_url('property/simulasi/edit2/2') . '\''
                        );
                        echo form_button($cancel_daftar);
                        echo form_close();
                        echo "</tr>";
                        echo "</table>";
        ?>
                      </div>
                      </div>
          </fieldset>
                  
                </div>
           </div>
        </div>
        <p style="padding-left: 35px; font-size:14px">



        </p>


        <div class="entry" style="text-align: center;">

           



        </div>


<br style="clear: both;" >
    </div></div>