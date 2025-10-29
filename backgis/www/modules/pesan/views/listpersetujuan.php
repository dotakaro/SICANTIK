<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
       
        <div class="entry">
           <table cellpadding="0" cellspacing="0" border="0" class="display" id="pesan">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Isi Pesan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pesan</th>
                        <th>Tanggal Pengiriman Pesan</th>
                        <th>Sumber Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i=1;
                    foreach ($list as $data){
                    $kelurahan = new trkelurahan();
                    $kecamatan = new trkecamatan();
                    $kecamatan->where('id', $data->kecamatan)->get();
                    $kelurahan->where('id', $data->kelurahan)->get();
                    $data->trstspesan->get();
                    $data->trsumber_pesan->get();
                ?>
                    <tr>
                        <td><?php echo $i;//$data->id; ?></td>
                        <td><?php echo $data->e_pesan_koreksi; ?></td>
                        <td><?php echo $data->nama; ?></td>
                        <td><?php echo $data->alamat; ?></td>
                        <td><?php echo $kelurahan->n_kelurahan; ?></td>
                        <td><?php echo $kecamatan->n_kecamatan; ?></td>
                        <td><?php echo $data->trstspesan->n_sts_pesan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry); ?></td>
                        <td><?php echo $data->trsumber_pesan->name; ?></td>
                        <td><center>
                            <?php
//                                $detail = array(
//                                    'name' => 'button',
//                                    'content' => 'Detail',
//                                    'class' => 'button-wrc',
//                                    'onclick' => 'parent.location=\''. site_url('pesan/pesanpersetujuan/edit') .'/'. $data->id . '\''
//                                );
//                                $delete = array(
//                                    'name' => 'button',
//                                    'class' => 'button-wrc',
//                                    'content' => 'Hapus',
//                                    'onclick' => 'parent.location=\''. site_url('pesan/delete') .'/'. $data->id . '\''
//                                );
//                                echo form_button($detail);
                             //   echo form_button($delete);
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                echo anchor(site_url('pesan/pesanpersetujuan/edit') .'/'. $data->id,img($img_edit))."&nbsp;";
                            ?></center>
                        </td>
                    </tr>
                <?php
                $i++;
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>NO</th>
                        <th>Isi Pesan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pesan</th>
                        <th>Tanggal Pengiriman Pesan</th>
                        <th>Sumber Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
