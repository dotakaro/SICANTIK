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
                'content' => 'Tambah Provinsi',
                'onclick' => 'parent.location=\'' . site_url('wilayah/create') . '\''
            );
            echo form_button($add_holiday);

            $get_data_kel = new trkelurahan();
            $list_ext_wilayah = $get_data_kel->cek_data_all_wilyah();
            $my_excep = array();
            if ($list_ext_wilayah) {
                foreach ($list_ext_wilayah as $row) {
                    $my_excep[] = $row->id_propinsi;
                }
            }
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="kegiatan">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Provinsi</th>
                        <th>Kode Daerah</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($list as $data) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data->n_propinsi; ?></td>
                            <td><?php echo $data->kode_daerah; ?></td>
                            <td width="50">
                    <center>
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
                        echo anchor(site_url('wilayah/edit') . '/' . $data->id, img($img_edit)) . '&nbsp;';
                        $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                         if (in_array($data->id, $my_excep)) {

                            } else {
                                echo anchor(site_url('wilayah/delete') . '/' . $data->id, img($img_delete), ' onClick="return confirm_link(\'' . $confirm_text . '\');"');
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
