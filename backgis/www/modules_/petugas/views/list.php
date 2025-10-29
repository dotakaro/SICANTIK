<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                $add_petugas = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Pegawai',
                    'onclick' => 'parent.location=\''. site_url('petugas/create') . '\''
                );
                echo form_button($add_petugas);
                
                if($ket_exist){
                    echo "<div class='entry' align=center><b style='color: #FF0000;'>Nama pegawai \"".$ket_exist."\" sudah digunakan !!</b></div>";
                }
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="petugas">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                    foreach ($list as $data){
                        $data->user->get();
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data->n_pegawai; ?></td>
                        <td><?php echo $data->nip; ?></td>
                        <td><?php echo $data->n_jabatan; ?></td>
                        <td>
                            <?php
                                if(strval($data->status) === "1") {
                                    echo "Penandatangan";
                                } else if(strval($data->status) === "2") {
                                    echo "Berita Acara Tinjauan";
                                } else if(strval($data->status) === "3") {
                                    echo "Surat Perintah & Berita Acara Pemeriksaan";
                                } else {
                                    echo "Pengguna";
                                }
                            ?>
                        </td>
                        <td><?php echo $data->user->username; ?></td>
                        <td>
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapus '.$data->n_pegawai.'?';
                                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                $img_users = array(
                                    'src' => 'assets/images/icon/users.png',
                                    'alt' => 'Jadikan Pengguna',
                                    'title' => 'Jadikan Pengguna',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\'Setelah membuatkan pengguna\nLangkah selanjutnya adalah mengkonfigurasi Pengguna.\')'
                                );
                                $img_pengguna = array(
                                    'src' => 'assets/images/aksespengguna2.png',
                                    'alt' => 'Ke Setting Pengguna',
                                    'title' => 'Ke Setting  Pengguna',
                                    'border' => '0',
                                    'width' => '16',
                                    'height' => '16'
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('petugas/edit'."/".$data->id) ?>" ><?php echo img($img_edit); ?></a>
                                <?php if(strval($data->status) == "0"){ ?>
                                <a class="page-help" href="<?php echo site_url('petugas/delete'."/".$data->id) ?>"  ><?php echo img($img_delete); ?></a>
                                <?php
                                }
                                    if($data->user->username === NULL) {
                                        ?>
                                <a class="page-help" href="<?php echo site_url('petugas/insertAsUser'."/".$data->id) ?>"><?php echo img($img_users); ?></a>
                                        <?php
                                    }
                                    else
                                    {
                                       ?>
                                            <a class="page-help" href="<?php echo site_url('pengguna/edit/'.$data->user->id) ?>"><?php echo img($img_pengguna); ?></a>
                                        <?php
                                    }
                                ?>
                            </center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
