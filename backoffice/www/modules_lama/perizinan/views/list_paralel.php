<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <center><?php echo $this->session->flashdata('pesan'); ?></center>
            <?php
            $add_role = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Izin Pararel Baru',
                'onclick' => 'parent.location=\'' . site_url('perizinan/paralel/add') . '\''
            );
            echo form_button($add_role);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="paralel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Perizinan Pararel</th>
                        <th>Jumlah Izin Terkait</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = null;
                    foreach ($list as $data) {
                        $data->trperizinan->get();
                        $i++
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data->n_paralel; ?></td>
                            <td><?php
                    if (0 < $data->trperizinan->count()) {
                        echo "<center>" . $data->trperizinan->count() . "</center>";
                    } else {
                        echo "<center>Belum ada izin terkait</center>";
                    }
                        ?>
                            </td>
                            <td>
                    <center>
                        <?php
                        $img_edit = array(
                            'src' => 'assets/images/icon/property.png',
                            'alt' => 'Edit',
                            'title' => 'Edit',
                            'border' => '0',
                        );
                        ?>
                        <a class="page-help" href="<?php echo site_url('perizinan/paralel/detail' . "/" . $data->id) ?>"><?php echo img($img_edit); ?></a>

                        <?php
                        $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                        $img_cancel = array(
                            'src' => 'assets/images/icon/cross.png',
                            'alt' => 'Cancel',
                            'title' => 'Delete',
                            'border' => '0',
                            'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
                        );
                        $cek_dt = new trparalel();
                        $get_dt = $cek_dt->cek_data($data->id);
                        //var_dump($get_dt) ;
                        if ($get_dt->total == 0) {
                            ?>
                        <a class="page-help" href="<?php echo site_url('perizinan/paralel/delete' . "/" . $data->id) ?>"><?php echo img($img_cancel); ?></a>
                            <?php
                        }
                        ?>

                        

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
                        <th>Jenis Perizinan Pararel</th>
                        <th>Jumlah Izin Terkait</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
