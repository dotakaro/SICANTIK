<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="notification_setting">
                <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:20%;">Nama Perizinan</th>
                    <th style="width:15%;">Keterangan</th>
                    <th style="width:10%;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($list as $key=>$data){
                    ?>
                    <tr>
                        <td><?php echo ($key+1);?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo ($data->setting_notifikasi->id)?'Sudah disetting':'Belum disetting';?></td>
                        <td style="text-align:center;">
                            <?php
                            $img_edit = array(
                                'src' => 'assets/images/icon/property.png',
                                'alt' => 'Edit',
                                'title' => 'Edit',
                                'border' => '0',
                            );

                            if($data->setting_notifikasi->id){
                            ?>
                            <a class="page-help" href="<?php echo site_url('notification_setting/edit'."/".$data->setting_notifikasi->id); ?>">
                                <?php
                                }else{
                                ?>
                                <a class="page-help" href="<?php echo site_url('notification_setting/add/'.$data->id); ?>">
                                    <?php
                                    }
                                    echo img($img_edit); ?>
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
