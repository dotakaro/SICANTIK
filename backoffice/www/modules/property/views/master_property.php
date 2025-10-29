<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                $add_role = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'List Status Property',
                    'onclick' => 'parent.location=\''. site_url('property/master/propertieslist') . '\''
                );
                echo form_button($add_role);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="property">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Perizinan</th>
                        <th>Kelompok Perizinan</th>
<!--                        <th>Lama Proses (Jam)</th>-->
                        <th>Jumlah Property</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = null;
                    foreach ($list as $data){
                        $data->trkelompok_perizinan->get();
                        $data->tralur_perizinan->get();
                        $data->trproperty->get();
                        $i++
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo $data->trkelompok_perizinan->n_kelompok; ?></td>
<!--                        <td><?php echo $data->tralur_perizinan->v_jam; ?></td>-->
                        <td><?php
                                $jumlah = 0;
                                $list_prop = new trperizinan_trproperty();
                                $list_prop->where('trperizinan_id', $data->id)->get();
                                foreach($list_prop as $prop){
                                    if($prop->c_parent !== $prop->trproperty_id){
                                        $jumlah++;
                                    }
                                }
//                                if($data->trproperty->where('c_parent != trproperty_id')->count()) {
                                if($jumlah !== 0){
                                    echo "<center>" . $jumlah . "</center>";
                                } else {
                                    echo "<center>Belum ada property</center>";
                                }
                            ?>
                        </td>
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
                                <a class="page-help" href="<?php echo site_url('property/master/detail'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
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
                        <th>Kelompok Perizinan</th>
<!--                        <th>Lama Proses (Jam)</th>-->
                        <th>Jumlah Property</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
