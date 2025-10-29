<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>         
        <div class="entry" id="centre">
             <table cellpadding="0" cellspacing="0" border="0" class="display" id="pesan">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Isi Pesan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No<br />Telepon</th>
                        <th>Status Pesan</th>
                        <th>Tanggal<br />Pengiriman Pesan</th>
                        <th width="70">Aksi</th>
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

                ?>
                    <tr>
                        <td><?php echo $i;//$data->id; ?></td>
                        <td><?php echo $data->e_pesan_koreksi; ?></td>
                        <td><?php echo $data->nama; ?></td>
                        <td><?php echo $data->alamat.', Kec :'.$kecamatan->n_kecamatan.', Kel :'.$kelurahan->n_kelurahan; ?></td>
                        <td><?php echo $data->telp; ?></td>
                        <td><?php echo $data->trstspesan->n_sts_pesan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry); ?></td>
                        <td><center>
                            <?php
                                $cetak_surat = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak Surat Pengaduan',
                                    'title' => 'Cetak Surat Pengaduan',
                                    'border' => '0',
                                );
//                                echo form_button($detail);
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                echo anchor(site_url('pesan/pesanpengiriman/viewPesan') .'/'. $data->id,img($img_edit))."&nbsp;";
                                echo anchor(site_url('pesan/pesanpengiriman/cetak_surat') .'/'. $data->id, img($cetak_surat))."&nbsp;";
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
                        <th>No.Telepon</th>
                        <th>Status Pesan</th>
                        <th>Tanggal Pengiriman Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
