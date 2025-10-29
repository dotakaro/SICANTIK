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
                        echo 'Nama Perizinan ';
                        ?>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <?php
                        echo $nama_izin;
                        ?>
                    </div>
                </div>
                <?php
                /*               $new_entry = array(
                  'name' => 'button',
                  'class' => 'button-wrc',
                  'content' => 'Tambah Property',
                  'onclick' => 'parent.location=\'' .  site_url('property/master/add') . '/' . $id . '\''
                  );
                  echo form_button($new_entry);
                 */
                ?>
                <p style="text-align: right">
                    <?php
                    $img_plus = array(
                        'src' => 'assets/images/icon/plus.png',
                        'alt' => 'Tambah Property',
                        'title' => 'Tambah Property',
                        'border' => '0',
                    );
                    ?>
                    <a class="page-help" href="<?php echo site_url('property/master/add/' . $id); ?>">
                        <?php echo img($img_plus); ?></a>
                    <?php
                    $img_back = array(
                        'src' => 'assets/images/icon/back_alt.png',
                        'alt' => 'Back',
                        'title' => 'Back',
                        'border' => '0',
                    );
                    ?>
                    <a class="page-help" href="<?php echo site_url('property/master/'); ?>">
                        <?php echo img($img_back); ?></a>
                </p>
            </fieldset>
        </div>
        <?php
        if ($ket_exist) {
            echo "<div class='entry' title='Silahkan cari di tab Tambah Property Database' align=center><b style='color: #FF0000;'>Nama property \"" . $ket_exist . "\" sudah ada di Database !!</b></div>";
        }
        ?>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="property_list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Property</th>
                        <th>Nama Grup</th>
                        <th>Parent</th>
                        <th>Urutan</th>
                        <th>Kode Retribusi</th>
                        <th>Tinjauan Lapangan/BAP</th>
                        <th>SKRD</th>
                        <th>Surat Izin</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>    
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($list as $data) {
                        $prop = new trproperty();
                        $prop->where('id', $data->trproperty_id)->get();
                        if ($data->c_parent !== $data->trproperty_id) {
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                    <?php
                                    echo $prop->n_property;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $prop->where('id', $data->c_parent)->get();
                                    echo $prop->n_property;
                                    ?>
                                </td>
                                <td align="center"><?php echo $data->c_parent_order; ?></td>
                                <td align="center"><?php echo $data->c_order; ?></td>
                                <td>
                                    <?php
                                    if ($data->c_retribusi_id === "1") {
                                        echo "Ada";
                                    } else if ($data->c_retribusi_id === "0" || $data->c_retribusi_id === NULL) {
                                        echo "Tidak Ada";
                                    }
                                    ?>
                                </td>
                                <td align="center"><?php
                            if ($data->c_tl_id == 0)
                                echo "Tidak Ada"; else
                                echo "Ada";
                            ?></td>
                                <td align="center"><?php
                            if ($data->c_skrd_id == 0)
                                echo "Tidak Ada"; else
                                echo "Ada";
                                    ?></td>
                                <td align="center"><?php
                            if ($data->c_sk_id == 0)
                                echo "Tidak Ada"; else
                                echo "Ada";
                                    ?></td>
                                <td>
                                    <?php
                                    $prop->where('id', $data->trproperty_id)->get();
                                    if ($prop->c_type === "1") {
                                        echo "ComboBox";
                                    } else if ($prop->c_type === "0") {
                                        echo "TextBox";
                                    } else if ($prop->c_type === "4") {
                                        echo "Tanggal";
                                    }
                                    ?>
                                </td>
                                <td>

                                    <?php
                                    $img_edit = array(
                                        'src' => 'assets/images/icon/property.png',
                                        'alt' => 'Edit',
                                        'title' => 'Edit',
                                        'border' => '0',
                                    );
                                    ?>
                                    <a class="page-help" href="<?php echo site_url('property/master/property' . "/" . $data->trperizinan_id . '/' . $data->trproperty_id) ?>">
                                    <?php echo img($img_edit); ?>
                                    </a>
                                    <?php
                                    $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                    $img_cancel = array(
                                        'src' => 'assets/images/icon/cross.png',
                                        'alt' => 'Hapus',
                                        'title' => 'Delete',
                                        'border' => '0',
                                        'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
                                    );
                                    
                                    //edited 09-04-2013
                                    $cek_dt = new trproperty();
                                    //$gt_dt = $cek_dt->cek_data($data->trproperty_id, $data->trperizinan_id);
                                    $gt_dt = $cek_dt->is_perizinan_used_in_permohonan($data->trperizinan_id);
                                    //var_dump($gt_dt);
                                    ?>

                                    <a class="page-help" href="<?php echo site_url('property/master/delete' . "/" . $data->trperizinan_id . '/' . $data->trproperty_id) ?>">
                                        <?php
                                            if (!$gt_dt) {
                                                echo img($img_cancel);
                                            }
                                        
                                        ?>
                                    </a>

                                    <!-- end edit -->
                                </td>
                            </tr>
        <?php
        $i++;
    }
}
?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
