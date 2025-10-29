<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            echo form_open('pesan/' . $save_method);
            ?>
            <div style="padding-left: 14px">
            <fieldset id="half">
                <legend>Isi Biodata</legend>
                <div class="bg-grid">
                <label class="label-wrc">Nama</label>
            <?php
            $pesan_nama = array(
                'name' => 'nama',
                'value' => $nama,
                'class' => 'input-wrc'
            );
            echo form_input($pesan_nama);
            ?><br />
            </div>
            <label class="label-wrc">Telp/HP</label>
            <?php
            $pesan_telp = array(
                'name' => 'telp',
                'value' => $telp,
                'class' => 'input-wrc'
            );
            echo form_input($pesan_telp);
            ?><br />
            <div class="bg-grid">
            <label class="label-wrc">Alamat</label>
            <?php
            $pesan_alamat = array(
                'name' => 'alamat',
                'value' => $alamat,
                'class' => 'input-wrc'
            );
            echo form_input($pesan_alamat);
            ?><br />
            </div>

<!--..................................................................-->
<div class="contentForm" id="show_propinsi_usaha">
                        <b><?php  echo form_label('Propinsi'); ?></b>
                            <?php
                                foreach ($list_propinsi as $row){
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                }

                                echo form_dropdown('propinsi_usaha', $opsi_propinsi, $propinsi_usaha,
                                     'class = "input-select-wrc" id="propinsi_usaha_id"');
                            ?>
                        </div>
                        <div class="bg-grid" id="show_kabupaten_usaha">
                            <b> <?php echo form_label('Kabupaten'); ?></b>
                            <?php
                                foreach ($list_kabupaten as $row){
                                    $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                                }

                                echo form_dropdown('kabupaten_usaha', $opsi_kabupaten, $kabupaten_usaha,
                                     'class = "input-select-wrc" id="kabupaten_usaha_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kecamatan_usaha">
                                <b> <?php echo form_label('Kecamatan'); ?> </b>
                            <?php
                                foreach ($list_kecamatan as $row){
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }

                                echo form_dropdown('kecamatan_usaha', $opsi_kecamatan, $kecamatan_usaha,
                                     'class = "input-select-wrc" id="kecamatan_usaha_id"');
                            ?>
                        </div>
    <div class="bg-grid" id="show_kelurahan_usaha">
                            <b>  <?php  echo form_label('Kelurahan'); ?> </b>
                            <?php
                                foreach ($list_kelurahan as $row){
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }

                                echo form_dropdown('kelurahan_usaha', $opsi_kelurahan, $kelurahan_usaha,
                                     'class = "input-select-wrc" id="kelurahan_usaha_id"');
                            ?>
                        </div>
<!--.................................................................................................-->

            </fieldset>
                </div>
                <div class="entry">
                  <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Pesan Pengaduan</a></li>
                        <li><a href="#tabs-2" title="isi sumber pesan, kategori pesan dan Tanggal Pembuatan Pesan">Info. Pesan</a></li>
                    </ul>
         <div id="tabs-1">
            <?php echo form_label('Pesan Pengaduan'); ?>
            <?php
            $e_pesan_input = array(
                'name' => 'e_pesan',
                'value' => $e_pesan,
                'style' => 'width:55%',
                'class' => 'input-area-wrc'
            );
            echo form_textarea($e_pesan_input);
            ?><br />
         </div>
         <div id="tabs-2">
                <?php  echo form_label('Sumber Pesan'); ?>
                 <select class="input-select-wrc" name="sumber_pesan">
                <?php
                    echo "<option value='1' style='color: silver'>-Pilih Sumber Pesan-</option>";
                    foreach ($list_sumber as $row){
                        echo "<option value=".$row->id.">".$row->name."</option>";
                    }
                 ?>
                  </select>

            <br style="clear: both" />
            <div class="bg-grid">
                <?php  echo form_label('Status Pesan'); ?>
                 <select class="input-select-wrc" name="status_pesan">
                <?php
                    echo "<option value='1' style='color: silver'>-Pilih Status Pesan-</option>";
                    foreach ($list_status as $row){
                        echo "<option value=".$row->id.">".$row->n_sts_pesan."</option>";
                    }
                 ?>
                  </select>


            <br style="clear: both" />
            </div>

            <?php  echo form_label('Tanggal Penulisan'); ?>
            <?php
            $d_entry_input = array(
                'name' => 'd_entry',
                'value' => $this->lib_date->get_date_now(),
                'class' => 'input-wrc',
                'id' => 'pesan'
            );
            echo form_input($d_entry_input);

            ?><br />
          </div>
                </div>
                    <br>
                    <?php
            $add_pesan = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_pesan);
            echo "<span></span>";
            $cancel_message = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('pesan') . '\''
            );
            echo form_button($cancel_message);
            echo form_close();
            ?>

                </div>
            <label>&nbsp;</label>
            <div class="spacer"></div>
            
            
            
        </div>
    </div>

    <br style="clear: both;" />
</div>
