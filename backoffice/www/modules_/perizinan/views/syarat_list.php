<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="syaratizin">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Perizinan</th>
                        <th>Kelompok Perizinan</th>
                        <th>Jumlah Syarat</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = null;
                    foreach ($list as $data){
                        $data->trkelompok_perizinan->get();
                        $data->tralur_perizinan->get();
                        $data->trsyarat_perizinan->get();                      
                        $i++
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo $data->trkelompok_perizinan->n_kelompok; ?></td>
                        <td><?php
                                if($data->trsyarat_perizinan->count() > 0) {
                                    echo "<center>" . $data->trsyarat_perizinan->count() . "</center>";
                                } else {
                                    echo "<center>Kosong</center>";
                                }
                            ?>
                        </td>
                        <td><center>
                            <?php
                                $img_detail = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Detail',
                                    'title' => 'Detail',
                                    'border' => '0',
                                );
                                echo anchor(site_url('perizinan/persyaratanizin/detail') .'/'. $data->id, img($img_detail));
                                echo "&nbsp;&nbsp;";
                                
                                /*$cek_dt = new trproperty();
                                $is_use_in_permohonan = $cek_dt->is_perizinan_used_in_permohonan($data->id);
                                if(!$is_use_in_permohonan){
                                    $img_delete = array(
                                        'src' => base_url().'assets/images/icon/cross.png',
                                        'alt' => 'Detail',
                                        'title' => 'Detail',
                                        'border' => '0',
                                    );
                                    echo anchor(site_url('perizinan/persyaratanizin/delete') .'/'. $data->id.'/',img($img_delete));
                                }*/
                            ?></center>
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
                        <th>Kelompok Perizinan</th>
                        <th>Jumlah Syarat</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>