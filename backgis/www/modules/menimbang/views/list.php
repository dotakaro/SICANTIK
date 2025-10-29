<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="ketetapan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Perizinan</th>
                        <th>Property Menimbang SK</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = null;
                    foreach ($list as $data){
                        $data->trmenimbang->get();
                        $i++
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo $data->trmenimbang->count(); ?></td>
                        <td width="50">
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('menimbang/detail'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                            </center>
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
