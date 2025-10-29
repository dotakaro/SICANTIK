
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
                    'content' => 'Tambah Unit Kerja',
                    'onclick' => 'parent.location=\''. site_url('unitkerja/create') . '\''
                );
                echo form_button($add_role);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="unitkerja">
                <thead>
                    <tr>
                        <th>Nama Unit Kerja</th>
<!--                        <th>Institusi Daerah</th>-->
                        <th>Wilayah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                ?>
                    <tr>
                        <td><?php echo $data->n_unitkerja; ?></td>
<!--                        <td>--><?php //echo ($data->flag_institusi_daerah) ? 'Ya' : 'Tidak'; ?><!--</td>-->
<!--                        <td>--><?php //echo $data->kode_daerah; ?><!--</td>-->
                        <td><?php echo $data->n_daerah; ?></td>
                        <td>
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapus '. $data->n_unitkerja.'?';
                                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('unitkerja/edit'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <a class="page-help" href="<?php echo site_url('unitkerja/delete'."/".$data->id) ?>"
                                ><?php echo img($img_delete); ?></a>
                            </center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Nama Unit Kerja</th>
<!--                        <th>Institusi Daerah</th>-->
                        <th>Wilayah</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
