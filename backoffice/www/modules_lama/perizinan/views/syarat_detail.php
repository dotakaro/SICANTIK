<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <legend>Jenis Perizinan</legend>
                <div id="statusRail">
                    <div id="leftRail" class="bg-grid">
                        <?php
                        echo 'Nama Perizinan';
                        ?>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <?php
                        foreach ($list as $data) {
                            echo $data->n_perizinan;
                        }
                        ?>
                    </div>
                </div>
                <br style="clear: both"><p style="text-align: right">
                    <?php
                    $img_edit = array(
                        'src' => 'assets/images/icon/plus.png',
                        'alt' => 'Tambah Syarat Izin',
                        'title' => 'Tambah Syarat Izin',
                        'border' => '0',
                    );
                    ?>
                    <a class="page-help" href="<?php echo site_url('perizinan/persyaratanizin/create/' . $id); ?>">
                        <?php echo img($img_edit); ?></a>
                    <?php
                    /*    $new_entry = array(
                      'name' => 'button',
                      'class' => 'button-wrc',
                      'content' => 'Tambah Syarat Izin',
                      'onclick' => 'parent.location=\'' .  site_url('perizinan/persyaratanizin/create') . '/' . $id . '\''
                      );
                      echo form_button($new_entry);
                     */
                    ?>
                    <?php
                    $img_edit = array(
                        'src' => 'assets/images/icon/back_alt.png',
                        'alt' => 'Back',
                        'title' => 'Back',
                        'border' => '0',
                    );
                    ?>
                    <a class="page-help" href="<?php echo site_url('perizinan/persyaratanizin/'); ?>">
                        <?php echo img($img_edit); ?></a>
                </p>
            </fieldset>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="syaratizin_detail">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Syarat Izin</th>
                        <th>Status</th>
                        <th>Izin Baru/Daftar Ulang</th>
                        <th>Perpanjangan</th>
                        <th>Perubahan</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($list as $data) {
                        $i = null;
                        $data->trsyarat_perizinan->order_by('status', 'asc');
                        $data->trsyarat_perizinan->get();
                        foreach ($data->trsyarat_perizinan as $list_syarat) {
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $list_syarat->v_syarat; ?></td>
                                <td>
        <?php
        if ($list_syarat->status == "1")
            $status_data = "Wajib";
        else
            $status_data = "Tidak Wajib";
        echo form_label($status_data);
        ?>
                                </td>
                                <td><?php
                            $show_syarat = new trperizinan_syarat();
                            $show_syarat
                                    ->where('trsyarat_perizinan_id', $list_syarat->id)
                                    ->where('trperizinan_id', $data->id)->get();
                            $var = $show_syarat->c_show_type;

                            $rule = strval(decbin($var));
                            if (strlen($rule) < 4) {
                                $len = 4 - strlen($rule);
                                $rule = str_repeat("0", $len) . $rule;
                            }
                            $arr_rule = str_split($rule);
                            $c_daftar_ulang = $arr_rule[0];
                            $c_baru = $arr_rule[1];
                            $c_perpanjangan = $arr_rule[2];
                            $c_ubah = $arr_rule[3];
                            $opsi_ya = "Ya";
                            $opsi_tidak = "Tidak";
                            if ($c_baru == "1")
                                echo $opsi_ya;
                            else
                                echo $opsi_tidak;
        ?></td>
                                <td><?php
                            if ($c_perpanjangan == "1")
                                echo $opsi_ya;
                            else
                                echo $opsi_tidak;
        ?></td>
                                <td><?php
                            if ($c_ubah == "1")
                                echo $opsi_ya;
                            else
                                echo $opsi_tidak;
        ?></td>
                                <td>
                        <center>
                            <?php
                            $img_edit = array(
                                'src' => base_url() . 'assets/images/icon/property.png',
                                'alt' => 'Edit',
                                'title' => 'Edit',
                                'border' => '0',
                            );
                            $img_delete = array(
                                'src' => base_url() . 'assets/images/icon/cross.png',
                                'alt' => 'Delete',
                                'title' => 'Delete',
                                'border' => '0',
                            );
                            echo anchor(site_url('perizinan/persyaratanizin/edit') . '/' . $data->id . '/' . $list_syarat->id, img($img_edit));
                            echo "&nbsp;";
                            $confirm_text = 'Apakah Anda yakin akan menghapusnya?';

                            /*$cek_dt = new trperizinan();
                            $get_dt = $cek_dt->cek_data($data->id);
                            //var_dump($get_dt) ;
                            if ($get_dt->total == 0) {
                                 echo anchor(site_url('perizinan/persyaratanizin/delete') . '/' . $data->id . '/' . $list_syarat->id, img($img_delete), ' onClick="return confirm_link(\'' . $confirm_text . '\');"');
                            }*/
                            
                            /*$data_syarat=new trperizinan();
                            $juml = $data_syarat->cek_data( $list_syarat->id);
                            if ($juml->total == 0) 
                            {
                                echo anchor(site_url('perizinan/persyaratanizin/delete') . '/' . $data->id . '/' . $list_syarat->id, img($img_delete), ' onClick="return confirm_link(\'' . $confirm_text . '\');"');
                            }*/
                            
                            //added 12-04-2013
                            // by mucktar
                            $data_permohonan_izin = new trproperty();
                            $status_daftar = $data_permohonan_izin->is_perizinan_used_in_permohonan($data->id);
                            if (!$status_daftar) 
                            {
                                echo anchor(site_url('perizinan/persyaratanizin/delete') . '/' . $data->id . '/' . $list_syarat->id, img($img_delete), ' onClick="return confirm_link(\'' . $confirm_text . '\');"');
                            }
                            ?>
                        </center>
                        </td>
                        </tr>
        <?php
    }
}
?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Syarat Izin</th>
                        <th>Status</th>
                        <th>Izin Baru/Daftar Ulang</th>
                        <th>Perpanjangan</th>
                        <th>Perubahan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
