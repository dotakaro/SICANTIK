<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
            <legend>Data Perizinan Paralel</legend>
            <div class="contentForm">
                <?php
                    echo form_label('Jenis Perizinan Paralel');
                ?>
                <?php
                $paralel = new trparalel();
                $paralel->get_by_id($id_paralel);
                echo $paralel->n_paralel;
                ?>
              </div>
            <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail" style="text-align: right">
                <?php
                $img_edit = array(
                    'src' => 'assets/images/icon/plus.png',
                    'alt' => 'Tambah Kaitan Izin Paralel',
                    'title' => 'Tambah Kaitan Izin Paralel',
                    'border' => '0',
                );
                ?>
                <a class="page-help" href="<?php echo site_url('perizinan/paralel/adddetail/' . $id_paralel); ?>">
                   <?php echo img($img_edit); ?></a>
                <?php
                $img_edit = array(
                    'src' => 'assets/images/icon/back_alt.png',
                    'alt' => 'Back',
                    'title' => 'Back',
                    'border' => '0',
                );
                ?>
                <a class="page-help" href="<?php echo site_url('perizinan/paralel/'); ?>">
                   <?php echo img($img_edit); ?></a>
              </div>
            </div>
        </fieldset><br>
            <?php
               /* $add_role = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Kaitan Izin Pararel',
                    'onclick' => 'parent.location=\''. site_url('perizinan/paralel/adddetail/' . $id_paralel) . '\''
                );
                echo form_button($add_role);*/
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="paralel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Perizinan Pararel</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
		<tbody>
                <?php
                    $i = null;
                    foreach ($list as $data){
                        $i++
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td>
                            <center>
                                <?php
                                $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                $img_cancel = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Cancel',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('perizinan/paralel/deletedetail'."/".$id_paralel."/".$data->id) ?>">
                                    <?php echo img($img_cancel); ?>
                                </a>
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
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
