<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $img_edit = array(
                'src' => 'assets/images/icon/plus.png',
                'alt' => 'Tambah Property',
                'title' => 'Tambah Property',
                'border' => '0',
            );
            ?>
            <a class="page-help" href="<?php echo site_url('property/master/addpropertydetail'); ?>">
               <?php echo img($img_edit); ?></a>
             <?php
            $img_edit = array(
                'src' => 'assets/images/icon/back_alt.png',
                'alt' => 'Back',
                'title' => 'Back',
                'border' => '0',
            );
            ?>
            <a class="page-help" href="<?php echo site_url('property/master/'); ?>">
               <?php echo img($img_edit); ?></a>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="property">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Perizinan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = null;
                    foreach ($list as $data){
                        $i++
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_property; ?></td>
                        <td><center>
                            <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                            ?>
                                <a class="page-help" href="<?php echo site_url('property/master/propertydetail'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                            <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/minus.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                            ?>
                                <a class="page-help" href="<?php echo site_url('property/master/purge'."/".$data->id) ?>"
                                ><?php //echo img($img_edit); ?></a>
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
                        <th>Jenis Perizinan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
