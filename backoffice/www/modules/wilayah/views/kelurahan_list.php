<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $add_holiday = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Kelurahan',
                'onclick' => 'parent.location=\'' . site_url('wilayah/kelurahan/create') . '\''
            );
            echo form_button($add_holiday);
            
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="kegiatan">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Kelurahan</th>
                        <th>Nama Kecamatan</th>
                        <th>Nama Kabupaten</th>
                        <th>Nama Provinsi</th>
                        <th>Kode Daerah</th>
                        <th width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($list_kelurahan as $data) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data->n_kelurahan; ?></td>
                            <td><?php echo $data->trkecamatan->n_kecamatan; ?></td>
                            <td><?php echo $data->trkecamatan->trkabupaten->n_kabupaten; ?></td>
                            <td><?php echo $data->trkecamatan->trkabupaten->trpropinsi->n_propinsi; ?></td>
                            <td><?php echo $data->kode_daerah; ?></td>
                            <td width="50">
                        <?php
                        $img_edit = array(
                            'src' => 'assets/images/icon/property.png',
                            'alt' => 'Edit',
                            'title' => 'Edit',
                            'border' => '0',
                        );
                        $img_delete = array(
                            'src' => 'assets/images/icon/cross.png',
                            'alt' => 'Delete',
                            'title' => 'Delete',
                            'border' => '0',
                        );
                        echo anchor(site_url('wilayah/kelurahan/edit') . '/' . $data->id, img($img_edit)) . '&nbsp;';
                        $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                        $cek_data = new trkelurahan();
                        $get_dat = $cek_data->cek_data($data->id);
                        if ($get_dat == 0) {
                            echo anchor(site_url('wilayah/kelurahan/delete') . '/' . $data->id, img($img_delete), ' onClick="return confirm_link(\'' . $confirm_text . '\');"');
                        }
                        ?>
                    
                    </td>
                    </tr>
    <?php
}
?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelurahan</th>
                        <th>Nama Kecamatan</th>
                        <th>Nama Kabupaten</th>
                        <th>Nama Provinsi</th>
                        <th>Kode Daerah</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
