<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <legend>Biodata Pengirim Pesan</legend>            
                    <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nama','Nama');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $nama;
                        ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Alamat','alamat');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                            echo $alamat;
                        ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Kelurahan','kelurahan');
                        ?>
                      </div>
                        <div id="rightRail" class="bg-grid">
                        <?php
                             $kel = new trkelurahan();
                            $kel->where('id', $kelurahan)->get();
                            echo $kel->n_kelurahan;
                        ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail">
                        <?php
                            echo form_label('Kecamatan','kecamatan');
                        ?>
                      </div>
                        <div id="rightRail">
                        <?php
                            $kec = new trkecamatan();
                            $kec->where('id', $kecamatan)->get();
                            echo $kec->n_kecamatan;
                        ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Telepon/Hp','telepon');
                        ?>
                      </div>
                        <div id="rightRail" class="bg-grid">
                        <?php
                            echo $telp;
                        ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail">
                        <?php
                            echo form_label('Tindak Lanjut','tindaklanjut');
                        ?>
                      </div>
                        <div id="rightRail">
                        <?php
                            echo $c_tindak_lanjut;
                        ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Tanggal Pengiriman Pesan','tanggal_pengiriman_pesan');
                        ?>
                      </div>
                        <div id="rightRail" class="bg-grid">
                        <?php
                            echo $d_entry;
                        ?>
                      </div>
                    </div>
                    <br style="clear: both">
            </fieldset>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('pesan/pesanpengiriman/' . $save_method,$attr);
        echo form_hidden('id', $id);
        echo form_hidden('nama', $nama);
        echo form_hidden('kelurahan', $kelurahan);
        echo form_hidden('kecamatan', $kecamatan);
        echo form_hidden('alamat', $alamat);
        echo form_hidden('c_tindak_lanjut', $c_tindak_lanjut);
        echo form_hidden('d_entry', $d_entry);
        echo form_hidden('telp', $telp);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1" title="Untuk Memberikan Koreksi Terhadap Surat Pengaduan">Pesan Pengaduan</a></li>
                    <li><a href="#tabs-2" title="Untuk Memberikan Data Detail Surat Balasan">Detail Tindak Lanjut</a></li>
                    <li><a href="#tabs-3" title="Untuk Memberikan Surat Respon Terhadap Surat Pengaduan">Respon Pengaduan</a></li>
                </ul>
                <div id="tabs-1">
                <div class="contentForm">
                     <?php echo form_label('Pesan Pengaduan'); ?>
                    <?php
                    $e_pesan_input = array(
                        'name' => 'e_pesan',
                        'value' => $e_pesan,
                        'readonly' => '',
                        'class' => 'input-area-wrc required',
                        'style' => 'width:55%'
                        );
                    echo form_textarea($e_pesan_input);
                    echo form_hidden('e_pesan', $e_pesan);
            ?>
                </div>
                 
                <div class="contentForm">
                    <?php echo form_label('Koreksi Pesan Pengaduan'); ?>
                    <?php
                    $e_pesan_koreksi_input = array(
                        'name' => 'e_pesan_koreksi',
                        'value' => $e_pesan_koreksi,
                        'class' => 'input-area-wrc required',
                        'style' => 'width:55%'
                        );
                    echo form_textarea($e_pesan_koreksi_input);
                    echo form_hidden('e_pesan_koreksi', $e_pesan_koreksi);
            ?>
            </div>
                    <br style="clear: both;" />
                </div>


<div id="tabs-2">
    <div class="bg-grid">
        <?php echo form_label('Dinas'); ?>
            <?php
            $c_skpd_tindaklanjut_input = array(
                'name' => 'c_skpd_tindaklanjut',
                'value' => $c_skpd_tindaklanjut,
                'class' => 'input-wrc required'
            );
            echo form_input($c_skpd_tindaklanjut_input);
            ?><p>
    </div>
            <?php echo form_label('Tanggal'); ?>
            <?php
            $d_tindak_lanjut_input = array(
                'name' => 'd_tindak_lanjut',
                'value' => $d_tindak_lanjut==NULL?date('Y-m-d'):$d_tindak_lanjut,
                'class' => 'input-wrc required',
                'readOnly'=>TRUE,
                'class' => 'pesan'
            );
            echo form_input($d_tindak_lanjut_input);
            ?><p>
    <div class="bg-grid">

            <?php echo form_label('Tanggal Tidak Lanjut Selesai'); ?>
            <?php
            
            $d_tindaklanjut_selesai_input = array(
                'name' => 'd_tindaklanjut_selesai',
                'value' => $d_tindaklanjut_selesai==NULL?date('Y-m-d'):$d_tindaklanjut_selesai,
                'class' => 'input-wrc required',
                 'readOnly'=>TRUE,
                'class' => 'pesan'
            );
            echo form_input($d_tindaklanjut_selesai_input);
            ?><p>
    </div>
            <br />
            <?php echo form_label('Nama Penanggung Jawab'); ?>

            <?php
            $nama_penanggungjawab_input = array(
                'name' => 'nama_penanggungjawab',
                'value' => $nama_penanggungjawab,
                'class' => 'input-wrc required'
            );
            echo form_input($nama_penanggungjawab_input);
            ?>
            
 </div>
<div id="tabs-3">
                   <td>
            <?php echo form_label('Isi Balasan Pengaduan'); ?>
                <div class="contentForm">
            <?php
            $e_tindak_lanjut_input = array(
                'name' => 'e_tindak_lanjut',
                'value' => $e_tindak_lanjut,
                'class' => 'input-area-wrc required',
                'style' => 'width:55%'
            );
            echo form_textarea($e_tindak_lanjut_input);
            ?>
                </div>
        </td>
  </div>
 </div>
        <div class="entry" style="text-align: center;">
            
        </div>
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
                'onclick' => 'parent.location=\''. site_url('pesan/pesanpengiriman') . '\''
            );
            echo form_button($cancel_daftar);
            echo form_close();
            ?>
    </div>
    </div>
    <br style="clear: both;" />
</div>
