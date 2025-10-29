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
              

           /*     $new_entry = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Dasar Hukum',
                    'onclick' => 'parent.location=\'' .  site_url('dasarhukum/add') . '/' . $id . '\''
                );
                echo form_button($new_entry);
                */
            ?><p style="text-align: right">
                                        <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/plus.png',
                                            'alt' => 'Tambah Dasar Hukum',
                                            'title' => 'Tambah Dasar Hukum',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('dasarhukum/add/'.$id); ?>">
                                           <?php echo img($img_edit); ?></a>
                                        <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/back_alt.png',
                                            'alt' => 'Back',
                                            'title' => 'Back',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('dasarhukum/'); ?>">
                                           <?php echo img($img_edit); ?></a>
            </p>
        </fieldset>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="dasar_hukum">
                <thead>
                    <tr>
                        <th>No</th>
<!--                        <th>Nama Dasar Hukum</th>-->
                        <th>Deskripsi</th>
<!--                        <th>Tanggal Berlaku</th>
                        <th>Tanggal Kadaluarsa</th>-->
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $lib_date = new Lib_date();
                    foreach ($list as $data){
                        $i = null;
                        $data->trdasar_hukum->order_by('id', 'asc');
                        $data->trdasar_hukum->get();
                        foreach ($data->trdasar_hukum as $list_trdasar_hukum) {
                            $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
<!--                        <td><?php echo $list_trdasar_hukum->nama; ?></td>-->
                        <td><?php echo $list_trdasar_hukum->deskripsi; ?></td>
<!--                        <td>
                            <?php
                                echo $lib_date->mysql_to_human($list_trdasar_hukum->tgl_berlaku); ?>
                        </td>
                        <td>
                            <?php
                                echo $lib_date->mysql_to_human($list_trdasar_hukum->tgl_berakhir);
                            ?>
                        </td>-->
                        <td>
                            <?php
                                $rel = new trdasar_hukum();
                                $rel->where(array(
                                    'id' => $list_trdasar_hukum->id,
                                    
                                ))->get();

                                if ($rel->type === "1") {
                                    echo "SKRD";
                                } else if($rel->type === "0" ) {
                                    echo "Surat Izin";
                                }
                            ?>
                        </td>
                        <td width="10%">
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
                                <a class="page-help" href="<?php echo site_url('dasarhukum/edit' . "/" . $data->id .'/'. $list_trdasar_hukum->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <a class="page-help" href="<?php echo site_url('dasarhukum/delete'. "/" . $data->id .'/'. $list_trdasar_hukum->id) ?>"
                                ><?php echo img($img_delete); ?></a>
                            </center>
                        </td>
                    </tr>
                <?php
                    } }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
