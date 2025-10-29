<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                $add_role = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Pengguna',
                    'onclick' => 'parent.location=\''. site_url('pengguna/create') . '\''
                );
                echo form_button($add_role);

                if($ket_exist){
                    echo "<div class='entry' align=center><b style='color: #FF0000;'>Username \"".$ket_exist."\" sudah digunakan !!</b></div>";
                }
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="user">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Asli Pengguna</th>
                        <th>Log Masuk Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                ?>
                    <tr>
                        <td><?php echo $data->username; ?></td>
                        <td><?php echo $data->realname; ?></td>
                        <td><?php if ($data->last_login) echo timespan($data->last_login); else echo "0 Menit"; ?></td>
                        <td>
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapus '.$data->username.'?';
                                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('pengguna/edit'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <?php if($data->id !== 1){ ?><a class="page-help" href="<?php echo site_url('pengguna/delete'."/".$data->id) ?>"
                                ><?php echo img($img_delete); ?></a>
                                <?php } ?>
                            </center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Username</th>
                        <th>Nama Asli Pengguna</th>
                        <th>Log Masuk Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
