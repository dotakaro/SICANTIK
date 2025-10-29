

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">


<?php
if($cek_izin === "2"){
    echo form_open('property/simulasi/edit2');
}
else{
    echo form_open('property/simulasi/edit');} ?>

        <fieldset id="half">

            <legend>Per Jenis Perizinan</legend>
            <div id="statusRail">
              <div id="leftRail">
                    <?php  echo form_label('Jenis Izin');?>

              </div>
              <div id="rightRail">
                    <div class="contentForm" id="show_izin_jenis" >
                   <?php
                    if (!empty($list_izin2))
                    {
                                foreach ($list_izin2 as $row){

                                    if ($row->id == "2"){
                                        $cek_izin === "2";
                                    }else
                                    {
                                         $cek_izin === "1";
                                    }
                                    $opsi_izin[$row->id] = $row->n_perizinan;
                                    $cek_izin = $row->id;
                                }
                              //  print_r($opsi_izin);
                                echo form_dropdown('jenis_izin', $opsi_izin, '',
                                     'class = "input-select-wrc" id="izin_jenis"');
                        }
                        else
                        {
                             $opsi_izin['0'] = '-----------Tidak ada data----------';
                             echo form_dropdown('jenis_izin', $opsi_izin, '',
                                     'class = "input-select-wrc" id="izin_jenis"');
                        }
//
//                                echo form_dropdown('jenis_izin', $opsi_izin, $row->id,
//                                     'class = "input-select-wrc" id="izin_jenis"');
                               
                            ?>
                    <?php echo form_hidden('c_retribusi',1);?>
                    </div>
               </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                    <?php  echo form_label('Tarif Dasar Retribusi');?>

              </div>
              <div id="rightRail">
                    <div class="contentForm"  id="show_retribusi">
                <?php
                                foreach ($list_retribusi as $row){
                                   $opsi_retribusi[$row->id] = $row->v_retribusi;
                                   $retribusi = $row->v_retribusi;
                                }

                            ?>
                        <input type="text" name="jenis_retribusi" value="<?php echo number_format($retribusi,2,",",".");?>" readonly>
                    </div>
               </div>
            </div>
              <div id="statusRail">
              <div id="leftRail">
              </div>
              <div id="rightRail">
                    <?php
                    echo form_submit('Simulasi','Simulasi','class="button-wrc"');

                    $img_imb = array(
                               'src' => base_url().'assets/images/icon/green_edit.png',
                               'alt' => 'Simulasi IMB',
                               'title' => 'Simulasi IMB',
                               'border' => '0',
                               );
                    // echo anchor(site_url('property/simulasi/edit2') .'/'. 2, img($img_imb))."&nbsp;";

                     ?>
            </div>
            </div>
        <? echo form_close(); ?>
        </fieldset>
          </div>

        
            </div>
    <br style="clear: both;" />
</div>