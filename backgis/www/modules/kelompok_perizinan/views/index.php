<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $addKelompokIzin = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Kelompok Perizinan',
                'onclick' => 'parent.location=\''. site_url('kelompok_perizinan/add') . '\''
            );
            echo form_button($addKelompokIzin);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="listKelompokIzin">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kelompok Izin</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($list as $data){
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_kelompok; ?></td>
                        <td style="text-align: center;">
                            <?php
                            $imgEdit = array(
                                'src' => 'assets/images/icon/property.png',
                                'alt' => 'Edit',
                                'title' => 'Edit',
                                'border' => '0',
                            );
                            $confirmText = 'Apakah Anda yakin akan menghapusnya?';
                            $imgDelete = array(
                                'src' => 'assets/images/icon/cross.png',
                                'alt' => 'Delete',
                                'title' => 'Delete',
                                'border' => '0',
                                'onClick' => 'return confirm_link(\''.$confirmText.'\')',
                            );
                            ?>
                            <a class="page-help" href="<?php echo site_url('kelompok_perizinan/edit'."/".$data->id) ?>">
                                <?php echo img($imgEdit); ?>
                            </a>
                            <?php
                            if(!$data->trperizinan->id){//Jika tidak ada izin di kelompok tersebut, bisa delete
                            ?>
                            <a class="page-help" href="<?php echo site_url('kelompok_perizinan/delete'."/".$data->id) ?>">
                                <?php echo img($imgDelete); ?>
                            </a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>