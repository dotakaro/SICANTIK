<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="sms_interaktif">
                <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:10%;">No HP</th>
                    <th style="width:10%;">Nama</th>
                    <th style="width:10%;">Tipe SMS</th>
                    <th style="width:30%;">Isi SMS</th>
                    <th style="width:15%;">Tanggal Masuk</th>
                    <th style="width:5%;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($list as $key=>$data){
                    ?>
                    <tr>
                        <td><?php echo ($key+1);?></td>
                        <td><?php echo $data->no_hp; ?></td>
                        <td><?php echo $data->nama;?></td>
                        <td><?php echo $data->tipe_sms;?></td>
                        <td><?php echo $data->isi_sms;?></td>
                        <td><?php echo $data->tgl_masuk;?></td>
                        <td style="text-align:center;">
                            <?php
                            $img_edit = array(
                                'src' => 'assets/images/icon/property.png',
                                'alt' => 'Reply',
                                'title' => 'Reply',
                                'border' => '0',
                            );
//                            if($data->replied == 0){
                                echo anchor(site_url('sms_interaktif/edit') .'/'. $data->id, img($img_edit))."&nbsp;";
//                            }
                            $img_info = array(
                                'src' => base_url().'assets/images/icon/information.png',
                                'alt' => 'Info SMS',
                                'title' => 'Info SMS',
                                'border' => '0',
                            );
                            $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                            $img_delete = array(
                                'src' => base_url() . 'assets/images/icon/cross.png',
                                'alt' => 'Delete',
                                'title' => 'Delete',
                                'border' => '0',
                                'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
                            );
                            echo anchor(site_url('sms_interaktif/view') .'/'. $data->id, img($img_info))."&nbsp;";
                            echo anchor(site_url('sms_interaktif/delete') . '/' . $data->id, img($img_delete));
                            ?>
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
