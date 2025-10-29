<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                $add_key = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah API Key',
                    'onclick' => 'parent.location=\''. site_url('api/maintainer/create') . '\''
                );
                echo form_button($add_key);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="key">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Level</th>
                        <th>Abaikan Batas</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                ?>
                    <tr>
                        <td><?php echo $data->key; ?></td>
                        <td><?php echo $data->level; ?></td>
                        <td><?php echo $data->ignore_limits; ?></td>
                        <td><?php echo $data->date_created; ?></td>
                        <td width="50">
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $img_delete = array(
                                    'src' => 'assets/images/icon/minus.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('api/maintainer/edit'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <a class="page-help" href="<?php echo site_url('api/maintainer/delete'."/".$data->id) ?>"
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
                        <th>Key</th>
                        <th>Level</th>
                        <th>Abaikan Batas</th>
                        <th>Tanggal Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
