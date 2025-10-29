<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <h2>
        <?php
            $tgla = $this->input->post('tgla');
            $tglb = $this->input->post('tglb');
            echo $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
        ?>
        </h2>


        <div class="entry" id="centre">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pesan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Isi Pesan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pesan</th>
                        <th>Tindak Lanjut</th>
                        <th>Tanggal Pengiriman Pesan</th>
                        <th>Sumber Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = null;
                    foreach ($list as $data){
                $i++;
                    $kelurahan = new trkelurahan();
                    $kecamatan = new trkecamatan();
                    $kecamatan->where('id', $data->kecamatan)->get();
                    $kelurahan->where('id', $data->kelurahan)->get();
                    $data->trstspesan->get();
                    $data->trsumber_pesan->get();


                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
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
                        <th>Isi Pesan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pesan</th>
                        <th>Tindak Lanjut</th>
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
