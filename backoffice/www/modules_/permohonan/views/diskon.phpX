<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
                 <fieldset id="half">
                      <legend>Data Pembuatan SKRD</legend>
            <?php
            $attr = array('name' => 'form', 'id' => 'form');
            echo form_open('permohonan/skrd/' . $save_method, $attr);
             ?>
             <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Nomor Pendaftaran','nomor');
                            echo form_hidden('ids',$listdist->id);
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                            echo $listpermohonan->pendaftaran_id;
                        ?>
                        <?php echo form_hidden('permohonan_id',$listpermohonan->id);?>
                      </div>
                    </div>
             <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Jenis Izin','jenis_izin');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $listizin->n_perizinan;
                        ?>
                        <?php echo form_hidden('izin_id',$listizin->id);?>
                      </div>
                    </div>
             <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Jenis Permohonan','jenis_permohonan');
                        ?>
                      </div>
                      <div id="rightRail" >
                        <?php
                            echo $listjenis->n_permohonan;
                        ?>
                        <?php echo form_hidden('j_permohonan_id',$listjenis->id);?>
                      </div>
                    </div>
                    <div id="statusRail" class="bg-grid">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nama Pemohon','nama_pemohon');
                        ?>
                      </div>
                      <div id="rightRail"  class="bg-grid">
                        <?php
                            echo $listpemohon->n_pemohon;
                        ?>
                        <?php echo form_hidden('pemohon_id',$listpemohon->id);?>
                      </div>
                    </div>
                 </fieldset>
<br>




            <fieldset id="content">
                      <legend>Keringanan Retribusi</legend>
        <br />
       
          <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Berdasarkan','kategori');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                       <?php
                        $e_berdasarkan_input = array(
                            'name' => 'e_berdasarkan',
                            'value' => $e_berdasarkan,
                            'class' => 'input-wrc required'
                        );
                        echo form_input($e_berdasarkan_input);
                        ?>
                      </div>
          </div>

           <div id="statusRail">
               <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nomor Surat','nomor_surat');
                        ?>
                      </div>
               <div id="rightRail" class="bg-grid">
                       <?php
                        $i_nomor_surat_input = array(
                            'name' => 'i_nomor_surat',
                            'value' => $i_nomor_surat,
                            'class' => 'input-wrc required'
                        );
                        echo form_input($i_nomor_surat_input);
                        ?>
                      </div>
          </div>

        <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Isi Surat','isi_surat');
                        ?>
                      </div>
            <div id="rightRail" >
                       <?php
                        $e_surat_input = array(
                            'name' => 'e_surat',
                            'value' => $e_surat,
                            'class' => 'input-area-wrc required',
                            'style'=>  'width: 90%'
                        );
                        echo form_textarea($e_surat_input);
                        ?>
                      </div>
          </div>

        <div id="statusRail">
            <div id="leftRail" >
                        <?php
                            echo form_label('Nama Pemohon','nama_pemohon');
                        ?>
                      </div>
            <div id="rightRail">
                       <?php
                        $n_pemohon_input = array(
                            'name' => 'n_pemohon',
                            'value' => $n_pemohon,
                            'class' => 'input-wrc required'
                        );
                        echo form_input($n_pemohon_input);
                        ?>
                      </div>
          </div>
        <div id="statusRail">
                      <div id="leftRail"  class="bg-grid">
                        <?php
                            echo form_label('Persentase','persentase');
                        ?>
                      </div>
                      <div id="rightRail"  class="bg-grid">
                       <?php
                        $v_prosentase_retribusi_input = array(
                            'name' => 'v_prosentase_retribusi',
                            'value' => $v_prosentase_retribusi,
                            'style' => 'width:10%',
                            'class' => 'input-wrc required digits'
                        );
                        echo form_input($v_prosentase_retribusi_input);
                        ?>
                          <font color="#1B8F28" title="Persen"><?php echo '%' ?></font>
                      </div>
          </div>

        <div id="statusRail">
                      <div id="leftRail" >
                        <?php
                            echo form_label('Nama entry','identry');
                        ?>
                      </div>
                      <div id="rightRail" >
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
            <div id="leftRail"  class="bg-grid">
                        <?php
                            echo form_label('Tanggal Entry','tanggal');
                        ?>
                      </div>
                      <div id="rightRail"  class="bg-grid">
                       <?php
            $d_entry_input = array(
                'name' => 'd_entry',
                'value' => $this->lib_date->get_date_now(),
               
                 'class' => 'input-wrc required',
                    'readOnly'=>TRUE,
                'class' => 'monbulan'
            );
            echo form_input($d_entry_input);
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
                'onclick' => 'parent.location=\''. site_url('permohonan/skrd').'/'.''.'/'.''. '\''
            );
            echo form_button($cancel_koefisien);
            echo form_close();
            ?>
            </p>
        </div>
    </div>
    <br style="clear: both;" />
</div>