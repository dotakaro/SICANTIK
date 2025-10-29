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
            </div><p style="text-align: right">
                                        <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/plus.png',
                                            'alt' => 'Tambah Menimbang SK',
                                            'title' => 'Tambah Menimbang SK',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('menimbang/add/'.$id); ?>">
                                           <?php echo img($img_edit); ?></a>
                                         <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/back_alt.png',
                                            'alt' => 'Back',
                                            'title' => 'Back',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('menimbang'); ?>">
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
                        $data->trmenimbang->order_by('id', 'asc');
                        $data->trmenimbang->get();
                        foreach ($data->trmenimbang as $list_trmenimbang) {
                            $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $list_trmenimbang->deskripsi; ?></td>
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
                                <a class="page-help" href="<?php echo site_url('menimbang/edit'.'/'. $data->id .'/'. $list_trmenimbang->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <a class="page-help" href="<?php echo site_url('menimbang/delete'.'/'. $data->id .'/'. $list_trmenimbang->id) ?>"
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
