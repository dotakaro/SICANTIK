<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>

       <h2 align="justify" style="border-left-color: #000">
          <fieldset>
            <legend style="color: #159729; font-size: 15px;">
            <?php
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
            //echo $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
            ?>

            <div id="statusRail">
              <div id="leftRail">
                <?php
                    $Back_data = array(
                        'name'    => 'button',
                        'value'   => 'Back',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('pesan/pesanbalasan') . '\''
                    );
                    $cetak =  array(
                        'name' => 'submit',
                        'class'=>'button-wrc',
                        'content' => 'Cetak',
                        'type' => 'submit',
                        'onclick' => 'parent.location=\''. site_url('pesan/pesanbalasan/cetak') .'/'.$tgla.'/'.$tglb. '\''

                    );
                    $cetakSop =  array(
                        'name' => 'submit',
                        'class'=>'button-wrc',
                        'content' => 'Cetak Detail',
                        'type' => 'submit',
                        'onclick' => 'parent.location=\''. site_url('pesan/pesanbalasan/cetakSop') .'/'.$tgla.'/'.$tglb. '\''

                    );
                    echo form_submit($Back_data);
                    echo form_button($cetak);
                   // echo form_button($cetakSop);
                 ?>
              </div>
            </div>
          </legend>
        </fieldset>
      </h2>

        <div class="entry" id="centre">
             <table cellpadding="0" cellspacing="0" border="0" class="display" id="pesan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengirim</th>
                        <th>Surat Pengaduan</th>
                        <th>Surat Pengaduan Koreksi</th>
                        <th>Media</th>
                        <th>Surat Balasan</th>
                        <th>Dinas</th>
                        <th>Pengguang Jawab</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = Null;
                    foreach ($list as $data){
                    $data->trstspesan->get();
                    $data->trsumber_pesan->get();
                    $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->nama; ?></td>
                        <td><?php echo $data->e_pesan; ?></td>
                        <td><?php echo $data->e_pesan_koreksi; ?></td>
                        <td><?php echo $data->trsumber_pesan->name; ?></td>
                        <td><?php echo $data->e_tindak_lanjut; ?></td>
                        <td><?php echo $data->c_skpd_tindaklanjut; ?></td>
                        <td><?php echo $data->nama_penanggungjawab; ?></td>
                        <td><center>
                            <?php
                                $cetak_surat = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak Surat Balasan',
                                    'title' => 'Cetak Surat Balasan Pengaduan',
                                    'border' => '0',
                                );
                                echo anchor(site_url('pesan/pesanbalasan/cetak_jawaban') .'/'. $data->id, img($cetak_surat))."&nbsp;";
                            ?></center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengirim</th>
                        <th>Surat Pengaduan</th>
                        <th>Surat Pengaduan Koreksi</th>
                        <th>Media</th>
                        <th>Surat Balasan</th>
                        <th>Dinas</th>
                        <th>Pengguang Jawab</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
