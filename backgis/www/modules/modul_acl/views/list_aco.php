<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            /*$addApi = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Build ACO',
                'onclick' => 'parent.location=\''. site_url('modul_acl/build_aco') . '\''
            );
            echo form_button($addApi);*/

            $addAco = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah ACO',
                'onclick' => 'parent.location=\''. site_url('modul_acl/add_aco') . '\''
            );
            echo form_button($addAco);

            $btnSync = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Sinkronisasi ACL dengan Menu',
                'onclick' => 'parent.location=\''. site_url('modul_acl/sync_with_menu') . '\''
            );
            echo form_button($btnSync);

            $btnDelAcl = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Hapus Semua ACL',
                'onclick' => 'delete_all_acl()'
            );
            echo form_button($btnDelAcl);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="listAco">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Key Permission</th>
                    <th>Nama Permission</th>
                    <th>Nama Modul Utama</th>
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
                        <td><?php echo $data->perm_key; ?></td>
                        <td><?php echo $data->perm_name; ?></td>
                        <td><?php echo $data->main_module_name; ?></td>
                        <td>
                            <?php
                            $imgEdit = array(
                                'src' => 'assets/images/icon/property.png',
                                'alt' => 'Edit',
                                'title' => 'Edit',
                                'border' => '0',
                            );
                            $confirmText = 'Apakah Anda yakin akan menghapusnya?';
                            /*$imgDelete = array(
                                'src' => 'assets/images/icon/cross.png',
                                'alt' => 'Delete',
                                'title' => 'Delete',
                                'border' => '0',
                                'onClick' => 'return confirm_link(\''.$confirmText.'\')',
                            );*/
                            ?>
                            <a class="page-help" href="<?php echo site_url('modul_acl/edit_aco'."/".$data->id) ?>">
                                <?php echo img($imgEdit); ?>
                            </a>
                            <!--<a class="page-help" href="<?php /*echo site_url('property_api/delete'."/".$data->id) */?>">
                                <?php /*echo img($imgDelete); */?>
                            </a>-->
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