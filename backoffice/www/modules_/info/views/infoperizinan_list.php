<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="perizinaninfo">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="73%">Nama Perizinan</th>
                        <th width="10%">Durasi Pengerjaan (Hari)</th>
                        <th width="10%">Masa Berlaku Izin (Tahun)</th>
                        <th width="5%">Aksi</th>
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
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo $data->v_hari; ?></td>
                        <td><?php 
							echo ($data->v_berlaku_tahun!='')?$data->v_berlaku_tahun.' ':'';
						 	echo $data->v_berlaku_satuan;
							?>
						</td>
                        <td><center>
                            <?php
                                $img_info = array(
                                    'src' => base_url().'assets/images/icon/information.png',
                                    'alt' => 'Info Perizinan',
                                    'title' => 'Info Perizinan',
                                    'border' => '0',
                                );
                                echo anchor(site_url('info/infoperizinan/detail') .'/'. $data->id, img($img_info))."&nbsp;";
                            ?>
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
