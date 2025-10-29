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
//                    'content' => 'Tambah Investasi',
                    'content' => 'Tambah Jenis Produksi/Jasa',
                    'onclick' => 'parent.location=\''. site_url('perusahaan/investasi/create') . '\''
                );
                echo form_button($add_holiday);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="investasi">
                <thead>
                    <tr>
                        <th>No</th>
<!--                        <th>Jenis Investasi</th>-->
                        <th>KBLI (5 digit)</th>
<!--                        <th>Keterangan</th>-->
                        <th>Nama KBLI (5 digit)</th>
                        <th>Aksi</th>
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
                        <td><?php echo $data->n_investasi; ?></td>
                        <td><?php echo $data->keterangan; ?></td>
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
                                echo anchor(site_url('perusahaan/investasi/edit') .'/'. $data->id, img($img_edit)).'&nbsp;';
                                $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                echo anchor(site_url('perusahaan/investasi/delete') .'/'. $data->id, img($img_delete),
                                ' onClick="return confirm_link(\''.$confirm_text.'\');"');
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
                        <th>KBLI (5 digit)</th>
                        <th>Nama KBLI (5 digit)</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
