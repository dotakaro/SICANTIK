    <div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
            <legend>Data Permohonan</legend>
            <div id="statusRail" style="font-weight: bold">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('No Pendaftaran','no_daftar');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    echo $daftar->pendaftaran_id;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Nama','nama');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    $daftar->tmpemohon->get();
                    echo $daftar->tmpemohon->n_pemohon;
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
                    echo $daftar->tmpemohon->a_pemohon;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Nama Izin','nama_izin');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    $daftar->trperizinan->get();
                    echo $daftar->trperizinan->n_perizinan;
                ?>
              </div>
            </div>
        </fieldset>
        </div>
        <?php
        echo form_open('permohonan/sk/' . $save_method);
        echo form_hidden('id_daftar', $id_daftar);
        echo form_hidden('id_surat', $id_surat);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Pembuatan SK</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <div class="contentForm">
                            <?php
                                $norefer_input = array(
                                    'name' => 'no_surat',
                                    'value' => $no_surat,
                                    'class' => 'input-wrc'
                                );
                                echo form_label('No Surat');
                                echo form_input($norefer_input);
                            ?>
                        </div>
                        <div class="contentForm">
                                <?php
                                $tglsk_input = array(
                                    'name' => 'tgl_surat',
                                    'value' => $tgl_surat,
                                    'class' => 'input-all',
                                    'id' => 'inputTanggal'
                                );
                                echo form_label('Tanggal Surat');
                                echo form_input($tglsk_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                foreach ($list_petugas as $row){
                                    $opsi_petugas[$row->id] = $row->n_pegawai;
                                }

                                //echo form_label('Petugas');
                                //echo form_dropdown('petugas', $opsi_petugas, $petugas,'class = "input-select-wrc"');
                            ?>
                        </div>
                    </div>
                    <div id="contentright">
                    </div>
                    <br style="clear: both;" />
                </div>
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
                'onclick' => 'parent.location=\''. site_url('permohonan/sk') . '\''
            );
            echo form_button($cancel_daftar);
            /*
             * echo "<span></span>";

            $cetak = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'SK Diserahkan',
                'onclick' => 'parent.location=\''. site_url('permohonan/sk/cetak') .'/'. $id_daftar . '\''
            );
            if($no_surat){
                if($sts_surat !== "1") echo form_button($cetak);
            }
             * 
             */
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
