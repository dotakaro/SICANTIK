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
                'content' => 'Tambah Menu',
                'onclick' => 'parent.location=\''. site_url('setting_menu/add') . '\''
            );
            echo form_button($addKelompokIzin);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="listMenu">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Link</th>
                    <th>Parent Menu</th>
                    <th>Urutan</th>
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
                        <td><?php echo $data->title; ?></td>
                        <td><?php echo $data->link; ?></td>
                        <td><?php echo $data->parent_title; ?></td>
                        <td><?php echo $data->menu_order; ?></td>
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
                            <a class="page-help" href="<?php echo site_url('setting_menu/edit'."/".$data->id) ?>">
                                <?php echo img($imgEdit); ?>
                            </a>
                            <a class="page-help" href="<?php echo site_url('setting_menu/delete'."/".$data->id) ?>">
                                <?php echo img($imgDelete); ?>
                            </a>
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