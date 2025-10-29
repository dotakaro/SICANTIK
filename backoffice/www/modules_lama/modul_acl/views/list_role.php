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

            $addRole = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Peran',
                'onclick' => 'parent.location=\''. site_url('modul_acl/add_role') . '\''
            );
            echo form_button($addRole);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="listRole">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
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
                        <td><?php echo $data->description; ?></td>
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
                            <a class="page-help" href="<?php echo site_url('modul_acl/edit_role'."/".$data->id) ?>">
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