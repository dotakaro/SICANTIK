  
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
                    echo form_label('Lokasi Izin','lokasi');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    echo $daftar->a_izin;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('Nama Izin','nama_izin');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    $daftar->trperizinan->get();
                    echo $daftar->trperizinan->n_perizinan;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Dinas','dinas');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    $daftar->trperizinan->trunitkerja->get();
                    echo $daftar->trperizinan->trunitkerja->n_unitkerja;
                ?>
              </div>
            </div>
        </fieldset>
        </div>
        <?php                
        $attr = array(
                'class' => 'form',
                'id' => 'form'
            );
        echo form_open('pelayanan/rekomendasi/' . $save_method,$attr);
        echo form_hidden('id_daftar', $id_daftar);
        echo form_hidden('id_surat', $id_surat);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Permohonan Rekomendasi</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <div class="contentForm">
                            <?php
                                $norefer_input = array(
                                    'id' => 'no_surat',
                                    'name' => 'no_surat',
                                    'value' => $no_surat,
                                    'class' => 'input-wrc required digits'
                                );
                                echo form_label('No Surat ');
                                echo form_input($norefer_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                $lamp_input = array(
                                    'name' => 'lampiran',
                                    'id' => 'lampiran',
                                    'value' => $lampiran,
                                    'class' => 'input-wrc required'
                                );
                                echo form_label('Lampiran');
                                echo form_input($lamp_input);
                            ?>
                        </div>
                        <div class="contentForm">
                                <?php
                                $tgldaftar_input = array(
                                    'name' => 'tgl_surat',
                                    'value' => $tgl_surat,
                                    'readOnly' => TRUE,
                                    'class' => 'input-wrc required',
                                    'id' => 'inputTanggal1'
                                );
                                echo form_label('Tgl Terima Rekomendasi ');
                                echo form_input($tgldaftar_input);
                            ?>
                        </div>
<!--                        <div class="contentForm">
                                <?php
                                $ket_input = array(
                                    'name' => 'keterangan',
                                    'value' => $keterangan,
                                    'class' => 'input-area-wrc'
                                );
                                echo form_label('Keterangan');
                                echo form_textarea($ket_input);
                            ?>
                        </div>-->
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
//            if($id_link == "1") $link_back = "pelayanan/pendaftaran";
//            else if($id_link == "2" || $id_link == "3" || $id_link == "4") $link_back = "pendaftaran/index/".$id_link;
//            else if($id_link == "5") $link_back = "permohonan/sk";
            $link_back = "pendataan";
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url($link_back) . '\''
            );
            echo form_button($cancel_daftar);
            echo "<span></span>";
            if($lampiran!="")
            {
            $cetak = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Cetak',
                'onclick' => 'parent.location=\''. site_url('pelayanan/rekomendasi/cetak') .'/'. $id_daftar . '\''
            );
            echo form_button($cetak);
            }
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
