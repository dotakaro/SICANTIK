<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="property_tim_teknis">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perizinan</th>
                        <th>Nama Unit Kerja</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $key=>$data){
                ?>
                    <tr>
                        <td><?php echo ($key+1);?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo $data->n_unitkerja; ?></td>
                        <td><?php echo ($data->property_teknis_header_id)?'Sudah disetting':'Belum disetting';?></td>
                        <td style="text-align:center;">
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );

                                if($data->property_teknis_header_id){
                                ?>
                                    <a class="page-help" href="<?php echo site_url('property_tim_teknis/edit'."/".$data->property_teknis_header_id); ?>">
                                <?php
                                }else{
                                ?>
                                    <a class="page-help" href="<?php echo site_url('property_tim_teknis/add/'.$data->trperizinan_id.'/'.$data->trunitkerja_id); ?>">
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
