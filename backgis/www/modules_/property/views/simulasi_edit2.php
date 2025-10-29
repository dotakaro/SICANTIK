 <script type="text/javascript" charset="utf-8">
              function hitung(){
                var retribusi = document.getElementById('retribusi_id').value;
                var jumlah    = document.getElementById('jumlahkoef').value;
               // var loop = jumlah +1;
                var koef ;
		var koef1=1;
                var i = 1;
              
                for(i;i<jumlah;i++){
               
                var xx = 'koefisien_id'+i;
                koef = document.getElementById(xx).value;
		koef1 = koef1*koef;
                }
                
               
               
                var total = Math.round(retribusi * koef1);
                $('#result').html("<b>Total Retribusi Yang harus dibayarkan Rp. "+total+",-</b>");

                //alert("Total Retribusi Yang harus dibayarkan Rp. "+total+",-");
               
                  
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
                          $jumlah = 1;
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
                        Rp<?php
                            echo $retribusix->v_retribusi;
                        ?>,00
                      
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
                <fieldset>
                     <div id="statusMain">
                   <?php
                        $i = 0; $z=1; $x=0;
                            if ($x %2 == 0) { $bg = "";} else { $bg = "bg-grid"; }
                        foreach ($list as $data){
                            $i++;
                            $property_type = $data->c_type; // Input Type [Text]
                            echo form_hidden('property', $data->c_type);
                          
                            if($list_izin->id){
                                foreach ($list_izin as $data_daftar){
                                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                    ->where('trproperty_id', $data->id)->get();
                                    if($entry_property->tmproperty_jenisperizinan_id){
                                        $entry_daftar = new tmproperty_jenisperizinan();
                                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                                        $entry_id = $entry_daftar->id;
                                        $data_entry = $entry_daftar->v_tinjauan;
                                        $data_koefisien = $entry_daftar->k_tinjauan;

                                    }
                                }
                            }else{
                                $entry_id = '';
                                $data_entry = '';
                                $data_koefisien = 0;
                            }
                            
                            
                    ?>

                   

     <div class="contentForm">
                        <input type="hidden" id="property_id" value="<?php echo $data->id;?>" name="property_id" >
                            <?php
                            if ($i %2 == 0) { $colour=" class='bg-grid'";} else { $colour="#FFFFFF"; }
                            echo form_hidden('property_id', $data->id);
                          
                            if($property_type == '1')
                                  {$x++;

                           echo "<div id='leftMain'.$colour.>";
                           echo $data->n_property;
                           echo "</div>";
                                   $list_koefisien = new trkoefesientarifretribusi();
                                   $list_koefisien->where_related($data)->get();
                                   if($list_koefisien->id)
                                   {    $jumlah = 1;
                                    echo "<div id='rightMain' .$colour.>";
                                        echo "<select class = 'input-select-wrc' name='koefisien_id' id='koefisien_id".$x."'>";
                                    
                                        foreach ($list_koefisien as $row)
                                            { 
                                                echo $row->kategori."<br />";
                                              $opsi_koefisien[$row->index_kategori] = $row->kategori."-".$row->index_kategori;
                                            echo "<option value='".$row->index_kategori."'>".$row->kategori."-".$row->index_kategori."</option>";
                                            }
                                              echo "</select>"; 
                                               echo "</div>";                                           $koef = new trkoefesientarifretribusi();
                                            $koef->get_by_id($row->id);
                                            
                                            $z = $z*$koef->index_kategori;
                                   }
                                   else {$opsi_koefisien = array(''  => '',); }

                                  
 //                                 echo form_dropdown('koefisien_id', $opsi_koefisien,
//                                    $entry_id, 'class = "input-select-wrc" id="koefisien_id'.$x.'"');
                                  
                                  $jumlah = $jumlah * $x;

                                   $opsi_koefisien = ''; echo '<br style="clear: both;" >';

                                 }
                                
                                 if($property_type == '2')
                                 {
                                        '<b>'.$data->n_property.'</b>';
                                        echo form_hidden('koefisien_id',1);
                                 }
                                 else
                                 {    
                                        echo form_hidden('koefisien_id',1);
                                       // $jumlah = 1;
                                 }
                               
                        ?>
     </div>
              
                    <?php
                        }
                    ?>
                            </div>

</div>
                     </fieldset>
            </div>
        </div>
<div style="margin-left: 35px; font-size:15px;" id="result"></div>


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