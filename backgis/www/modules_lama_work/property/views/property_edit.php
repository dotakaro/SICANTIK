<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset>
                <legend>Data Perizinan</legend>
                <div id="statusRail">
                  <div id="leftRail">                      
                    <?php
                        echo form_label('Nama Izin','nama_izin');
                    ?>
                  </div>
                  <div id="rightRail">
                    <?php
                        echo $jenis_izin->n_perijinan;
                    ?>                      
                  </div>
                </div>
                <div id="statusRail">
                  <div id="leftRail">
                    <?php
                        echo form_label('Kelompok Izin','kelompok_izin');
                    ?>
                  </div>
                  <div id="rightRail">
                  </div>
                </div>
                <div id="statusRail">
                  <div id="leftRail">
                    <?php
                        echo form_label('Keterangan','keterangan');
                    ?>
                  </div>
                  <div id="rightRail">
                  </div>
                </div>
                <div id="statusRail">
                  <div id="leftRail">
                    <?php
                        echo form_label('Jenis Permohonan','jenis_permohonan');
                    ?>
                  </div>
                  <div id="rightRail">
                    <?php
                        echo $jenis_permohonan->n_permohonan;
                    ?>
                  </div>
                </div>
            </fieldset>
        </div>
        <div class="entry">
            <div class="usual">
              <ul class="idTabs">
                <li><a href="#idTab1" class="selected">Tab 1</a></li>
                <li><a href="#idTab2">Tab 2</a></li>
                <li><a href="#idTab3">Tab 3</a></li>
              </ul>
              <div id="idTab1" style="display: none; ">This is tab 1.</div>
              <div id="idTab2" style="display: none; ">More content in tab 2.</div>
              <div id="idTab3" style="display: block; ">Tab 3 is always last!</div>
            </div>            
        </div>
        <div class="entry">
            <?php
            echo form_open('pendaftaran/' . $save_method);
            echo form_hidden('id', $id);
            ?>
            <label class="label-wrc">ID Peran</label>
            <?php
            $id_daftar_input = array(
                'name' => 'id_daftar',
                'value' => $id_daftar,
                'class' => 'input-wrc'
            );
            echo form_input($id_daftar_input);
            ?><br />
            <label class="label-wrc">Hak Akses</label>
            <?php
            $description_input = array(
                'name' => 'description',
                'value' => $description,
                'class' => 'input-wrc'
            );
            echo form_input($description_input);
            ?><br />
            <label>&nbsp;</label>
            <div class="spacer"></div>

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
                'onclick' => 'parent.location=\''. site_url('pendaftaran') . '\''
            );
            echo form_button($cancel_daftar);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
