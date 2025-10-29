<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                if($method !== 'editing') {
                    ?>

            <div id="tabs">
                <ul>
                    <li><a href="#tabs-2">Tambah Mengingat SK Baru</a></li>
                </ul>
                <div id="tabs-2">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('mengingat/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    ?>
                    <br style="clear: both" />
                    <label class="label-wrc">Deskripsi</label>
                    <?php
                    $deskripsi = array(
                        'name' => 'deskripsi',
                        'value' => $deskripsi,
                        'class' => 'input-area-wrc required',
                        'style' => 'min-width:400pt'
                    );
                    echo form_textarea($deskripsi);
                    ?>
                    <br style="clear: both" />
                    <label class="label-wrc">Status</label>
                    <?php
                        $status = "<input type=\"radio\" name=\"status\" ";
                        $status_0 = "value=\"0\"";
                        $status_1 = "value=\"1\"";
                        if ($status_cont === '0') {
                            echo $status . $status_0 . "checked=\"checked\" />";
                            echo "SK";
                            echo $status . $status_1 . " />";
                            echo "SKRD";
                        } else {
                            echo $status . $status_0 . " />";
                            echo "SK";
                            echo $status . $status_1 . "checked=\"checked\" />";
                            echo "SKRD ";
                        }
                    ?>
                    <br style="clear: both;" />
                    <?php
                    $add = array(
                        'name' => 'submit',
                        'class' => 'submit-wrc',
                        'content' => 'Simpan',
                        'type' => 'submit',
                        'value' => 'Simpan'
                    );
                    echo form_submit($add);
                    echo "<span></span>";
                    $cancel_role = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Batal',
                        'onclick' => 'parent.location=\''. site_url('mengingat/detail') . "/" . $id_izin . '\''
                    );
                    echo form_button($cancel_role);
                    echo form_close();
                    echo form_close();
                    ?>
                </div>
            </div>

                    <?php
                } else {
                    ?>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Edit Mengingat SK Baru</a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('mengingat/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    echo form_hidden('id_dasar_hukum', $id_dasar_hukum);
                    ?>
                    <br style="clear: both" />
                    <label class="label-wrc">Deskripsi</label>
                    <?php
                    $deskripsi = array(
                        'name' => 'deskripsi',
                        'value' => $deskripsi,
                        'class' => 'input-area-wrc required',
                        'style' => 'min-width: 400pt'
                    );
                    echo form_textarea($deskripsi);
                    ?>
                    <br style="clear: both" />
                    <label class="label-wrc">Status</label>
                    <?php
                        $status = "<input type=\"radio\" name=\"status\" ";
                        $status_0 = "value=\"0\"";
                        $status_1 = "value=\"1\"";
                        if ($status_cont === '0') {
                            echo $status . $status_0 . "checked=\"checked\" />";
                            echo "SK";
                            echo $status . $status_1 . " />";
                            echo "SKRD";
                        } else {
                            echo $status . $status_0 . " />";
                            echo "Surat Izin ";
                            echo $status . $status_1 . "checked=\"checked\" />";
                            echo "SKRD ";
                        }
                    ?>
                    <br style="clear: both;" />
                    <?php
                    $add = array(
                        'name' => 'submit',
                        'class' => 'submit-wrc',
                        'content' => 'Simpan',
                        'type' => 'submit',
                        'value' => 'Simpan'
                    );
                    echo form_submit($add);
                    echo "<span></span>";
                    $cancel_role = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Batal',
                        'onclick' => 'parent.location=\''. site_url('mengingat/detail') . "/" . $id_izin . '\''
                    );
                    echo form_button($cancel_role);
                    echo form_close();
                    echo form_close();
                    ?>
                </div>
            </div>
                    <?php
                }
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
