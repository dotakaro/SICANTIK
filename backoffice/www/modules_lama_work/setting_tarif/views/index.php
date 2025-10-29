<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                $add_tarif = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Tarif',
                    'onclick' => 'parent.location=\''. site_url('setting_tarif/add') . '\''
                );
                echo form_button($add_tarif);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="setting_tarif">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Satuan</th>
                        <th>Jenis Izin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                ?>
                    <tr>
                        <td><?php echo $data->nama_item; ?></td>
                        <td><?php echo $data->satuan; ?></td>
                        <?php
                        $trperizinan = new trperizinan();
                        $izin = $trperizinan->where('id',$data->trperizinan_id)->get();
                        ?>
                        <td><?php echo $izin->n_perizinan; ?></td>
                        <td style="text-align:center;">
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapus '.$data->nama_item.'?';
                                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('setting_tarif/edit'."/".$data->id); ?>">
                                <?php echo img($img_edit); ?>
                                </a>
                                <a class="page-help" href="<?php echo site_url('setting_tarif/delete'."/".$data->id) ?>">
                                <?php echo img($img_delete); ?>
                                </a>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Nama Item</th>
                        <th>Satuan</th>
                        <th>Jenis Izin</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
