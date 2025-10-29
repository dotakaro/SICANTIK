<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
                 <fieldset id="half">
                      <legend>Data Perizinan</legend>
            <?php
             $attr = array('id' => 'form');
            echo form_open('perizinan/koefisientarif/' . $save_method,$attr);
             ?>
             <?php echo form_hidden('id',$id);?>
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
                            echo form_label('Nama Property','nama_property');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                            echo $property->n_property;
                        ?>
                        <?php echo form_hidden('property_id',$property->id);?>
                        <?php echo form_hidden('level1_id',$dkoef->id);?>
                        <?php echo form_hidden('level2_id',$koeflev1->id);?>
                        <?php echo form_hidden('level3_id',$koeflev2->id);?>
                        <?php echo form_hidden('tesssss',$satuan_key);?>
                      </div>
                    </div>d
                      <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Kategori','kategori');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                           echo $dkoef->kategori.' ('.$dkoef->index_kategori.')';
                        ?>

                      </div>
          </div>
                      <div id="statusRail">
                      <div id="leftRail" >
                        <?php
                            echo form_label('Sub Kategori','kategori');
                        ?>
                      </div>
                      <div id="rightRail" >
                           <?php
                           echo $koeflev1->kategori.' ('.$koeflev1->index_kategori.')';
                           ?>
                      </div>
          </div>
                      <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Sub Kategori','kategori');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                          <b>  <?php
                           echo $koeflev2->kategori.' ('.$koeflev2->index_kategori.')';
                        ?>
                         </b>
                      </div>
          </div>
                 </fieldset>
<br>

            <fieldset id="half">
                      <legend>Data koefisien Tarif</legend>
        <br />
          <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Kategori','kategori');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                       <?php
                        $kategori_input = array(
                            'name' => 'kategori',
                            'value' => $kategori,
                            'class' => 'input-wrc required'
                        );
                        echo form_input($kategori_input);
                        ?>
                      </div>
          </div>

           <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Index Kategori','index_kategori');
                        ?>
                      </div>
                      <div id="rightRail">
                       <?php
                        $idx_kat_input = array(
                            'name' => 'idx_kategori',
                            'value' => $index_kategori,
                            'class' => 'input-wrc required digits'
                        );
                        echo form_input($idx_kat_input);
                        ?>
                      </div>
          </div>

        <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Satuan','satuan');
                        ?>
                      </div>
                      <div id="rightRail">
                        <select class="input-select-wrc" name="satuan">
                        <?php
                        $sel = null;
                            foreach ($satuan as $row){
                                ($row === $satuan_key) ? $sel ="selected='selected'" : $sel = " ";
                                echo "<option value='".$row."'".$sel.">".$row."</option>";
                            }
                         ?>
                          </select>
                      </div>
          </div>


           <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nama entry','identry');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                        if($i_entry==""){
                            $namaentry = "admin";
                        }else
                        {
                            $namaentry = $i_entry;
                        }

                        $id_entry_input = array(
                            'name' => 'i_entry',
                            'value' => $namaentry,
                            'class' => 'input-wrc required',
                        );
                        echo form_input($id_entry_input);
                        ?>
                      </div>
          </div>
           <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Tanggal entry','tgl_entry');
                        ?>
                      </div>
                      <div id="rightRail">
                       <?php
                        if($d_entry==""){
                            $tglentry = date("Y-m-d");
                        }else
                        {
                            $tglentry = $d_entry;
                        }
                        $tgl_entry_input = array(
                            'name' => 'd_entry',
                            'value' => $tglentry,
                            'readOnly'=>TRUE,
                             'class'=>'tarif'
                        );
                        echo form_input($tgl_entry_input);
                        ?>
                      </div>
          </div>
          </fieldset>

            <p style="padding-left: 200px">
            <?php
            $add_koefisien = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_koefisien);
            echo "<span></span>";
            $cancel_koefisien = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('perizinan/koefisientarif/edit1').'/'.$jenis_izin->id .'/'.$property->id.'/'.$dkoef->id.'/'.$koeflev1->id .'\''
            );
            echo form_button($cancel_koefisien);
            echo form_close();
            ?>
            </p>
        </div>
    </div>
    <br style="clear: both;" />
</div>