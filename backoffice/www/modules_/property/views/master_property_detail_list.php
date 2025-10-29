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
                    echo 'Nama Property';
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    echo $nama_property;
                ?>
              </div>
            </div>
            <?php

            ?>
            <p style="text-align: right">
                <?php
                $img_edit = array(
                    'src' => 'assets/images/icon/plus.png',
                    'alt' => 'Tambah Property',
                    'title' => 'Tambah Property',
                    'border' => '0',
                );
                ?>
                <a class="page-help" href="<?php echo site_url('property/master/addDetailProperty/'.$id_izin."/".$id_property); ?>">
                   <?php echo img($img_edit); ?></a>
                <?php
                $img_edit = array(
                    'src' => 'assets/images/icon/back_alt.png',
                    'alt' => 'Back',
                    'title' => 'Back',
                    'border' => '0',
                );
                ?>
                <a class="page-help" href="<?php echo site_url('property/master/property/'.$id_izin."/".$id_property); ?>">
                   <?php echo img($img_edit); ?></a>
            </p>
        </fieldset>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="property_list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Property</th>
                        <th>Nama Grup</th>
                        <th>Kode Retribusi</th>
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
                        if($data->c_parent !== $data->trproperty_id) {

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
                                <td>
                                    <?php
                                        if ($data->c_retribusi_id === "1") {
                                            echo "Ada";
                                        } else if ($data->c_retribusi_id === "0" || $data->c_retribusi_id === NULL) {
                                            echo "Tidak Ada";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $prop->where('id', $data->trproperty_id)->get();
                                        if ($prop->c_type === "1") {
                                            echo "ComboBox";
                                        } else if ($prop->c_type === "0") {
                                            echo "TextBox";
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
                                        <a class="page-help" href="<?php echo site_url('property/master/property'."/".$data->trperizinan_id .'/'. $data->trproperty_id) ?>">
                                            <?php echo img($img_edit); ?>
                                        </a>
                                        <?php
                                        $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                        $img_cancel = array(
                                            'src' => 'assets/images/icon/cross.png',
                                            'alt' => 'Cancel',
                                            'title' => 'Cancel Survey',
                                            'border' => '0',
                                            'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('property/master/delete'."/".$data->trperizinan_id .'/'. $data->trproperty_id) ?>">
                                            <?php echo img($img_cancel); ?>
                                        </a>

                                    </center>
                                </td>
                            </tr>
                <?php
                    $i++;
                        }
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Property</th>
                        <th>Nama Grup</th>
                        <th>Kode Retribusi</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
