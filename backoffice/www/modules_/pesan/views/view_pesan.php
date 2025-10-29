<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <h2 align="justify" style="border-left-color: #000">
          <fieldset>
            <legend style="color: #159729; font-size: 15px">
            <?php
            echo $liststspesan->n_sts_pesan;
            ?>
          </legend>
                <?php echo form_open('pesan'); ?>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    $Back_data = array(
                        'name'    => 'button',
                        'value'   => 'Back',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back'
                    );
                    echo form_submit($Back_data);
                 ?>
              </div>
            </div>
        </fieldset></h2>
           </div>
           
        <? echo form_close(); ?>

        <div class="entry" id="centre">
            
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pesan">
                <thead>
                    <tr>
                         <th>ID</th>
                        <th>Isi Pengaduan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pengaduan</th>
                        <th>Tindak Lanjut</th>
                        <th>Tanggal Pengiriman Pengaduan</th>
                        <th>Sumber Pengaduan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                    $kelurahan = new trkelurahan();
                    $kecamatan = new trkecamatan();
                    $kecamatan->where('id', $data->kecamatan)->get();
                    $kelurahan->where('id', $data->kelurahan)->get();
                    
                    $data->trstspesan->get();
                    $data->trsumber_pesan->get();


                ?>
                    <tr>
                        <td><?php echo $data->id; ?></td>
                        <td><?php echo $data->e_pesan; ?></td>
                        <td><?php echo $data->nama; ?></td>
                        <td><?php echo $data->alamat; ?></td>
                        <td><?php echo $kelurahan->n_kelurahan; ?></td>
                        <td><?php echo $kecamatan->n_kecamatan; ?></td>
                        <td><?php echo $data->trstspesan->n_sts_pesan; ?></td>
                        <td><?php echo $data->c_tindak_lanjut; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry); ?></td>
                        <td><?php echo $data->trsumber_pesan->name; ?></td>
                        <td><center>
                            <?php
//                                $detail = array(
//                                    'name' => 'button',
//                                    'content' => 'Detail',
//                                    'class' => 'button-wrc',
//                                    'onclick' => 'parent.location=\''. site_url('pesan/edit') .'/'. $data->id . '\''
//                                );
//                                $delete = array(
//                                    'name' => 'button',
//                                    'class' => 'button-wrc',
//                                    'content' => 'Hapus',
//                                    'onclick' => 'parent.location=\''. site_url('pesan/delete') .'/'. $data->id . '\''
//                                );
//                                echo form_button($detail);
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                echo anchor(site_url('pesan/edit') .'/'. $data->id,img($img_edit))."&nbsp;";
                            ?></center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Isi Pengaduan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pengaduan</th>
                        <th>Tindak Lanjut</th>
                        <th>Tanggal Pengiriman Pengaduan</th>
                        <th>Sumber Pengaduan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
