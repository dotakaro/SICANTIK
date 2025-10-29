<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                $add_alurijin = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Alur Izin',
                    'onclick' => 'parent.location=\''. site_url('perizinan/alurizin/create') . '\''
                );
                echo form_button($add_alurijin);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="alurizin">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Perizinan</th>
                        <th>Peran</th>
                        <th>Jumlah Jam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = NULL;
                    foreach ($list as $data){
                    $i++;
                    $data->trperizinan->get();
                    $data->user_auths->get();
                    

                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->user_auths->description; ?></td>
                        <td><?php echo $data->v_jam; ?></td>
                        <td><center>
                            <?php
                                $detail = array(
                                    'name' => 'button',
                                    'content' => 'Edit',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('perizinan/alurizin/edit') .'/'. $data->id . '\''
                                );
                                $delete = array(
                                    'name' => 'button',
                                    'class' => 'button-wrc',
                                    'content' => 'Hapus',
                                    'onclick' => 'parent.location=\''. site_url('perizinan/alurizin/delete') .'/'. $data->id . '\''
                                );
                                echo form_button($detail);
                                echo form_button($delete);
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
                        <th>Peran</th>
                        <th>Jumlah Jam</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>