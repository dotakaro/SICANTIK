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
            <?php
       

              /*  $new_entry = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Ketetapan',
                    'onclick' => 'parent.location=\'' .  site_url('ketetapan/add') . '/' . $id . '\''
                );
                echo form_button($new_entry);
                */
            ?><p style="text-align: right">
                                        <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/plus.png',
                                            'alt' => 'Tambah Ketetapan',
                                            'title' => 'Tambah Ketetapan',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('ketetapan/add/'.$id); ?>">
                                           <?php echo img($img_edit); ?></a>
                                         <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/back_alt.png',
                                            'alt' => 'Back',
                                            'title' => 'Back',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('ketetapan'); ?>">
                                           <?php echo img($img_edit); ?></a>
            </p>
        </fieldset>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="ketetapan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                        $i = null;
                        $data->trketetapan->order_by('id', 'asc');
                        $data->trketetapan->get();
                        foreach ($data->trketetapan as $list_trketetapan) {
                            $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $list_trketetapan->n_ketetapan; ?></td>
                        <td width="50px">
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',

                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('ketetapan/edit'.'/'. $data->id .'/'. $list_trketetapan->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <a class="page-help" href="<?php echo site_url('ketetapan/delete'.'/'. $data->id .'/'. $list_trketetapan->id) ?>"
                                ><?php echo img($img_delete); ?></a>
                            </center>
                        </td>
                    </tr>
                <?php
                    } }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
