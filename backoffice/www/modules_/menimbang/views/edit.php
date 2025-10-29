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
                    <li><a href="#tabs-2">Tambah Menimbang SK Baru</a></li>
                </ul>
                <div id="tabs-2">
                    <?php
                     $attr = array('id' => 'form');
                    echo form_open('menimbang/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    ?>
                    <label class="label-wrc">Deskripsi</label>
                    <?php
                    $deskripsi_input = array(
                        'name' => 'deskripsi',
                        'value' => $deskripsi,
                        'class' => 'input-area-wrc required',
                        'style' => 'min-width:400pt'
                    );
                    echo form_textarea($deskripsi_input);
                    ?>
                    <br style="clear: both" />
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
                        'onclick' => 'parent.location=\''. site_url('menimbang/detail') . "/" . $id_izin . '\''
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
                    <li><a href="#tabs-1">Edit Menimbang SK</a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                     $attr = array('id' => 'form');
                    echo form_open('menimbang/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    echo form_hidden('id_ketetapan', $id_ketetapan);
                    ?>
                    <label class="label-wrc">Deskripsi</label>
                    <?php
                    $deskripsi_input = array(
                        'name' => 'deskripsi',
                        'value' => $deskripsi,
                        'class' => 'input-area-wrc required',
                        'style' => 'min-width: 400pt'
                    );
                    echo form_textarea($deskripsi_input);
                    ?>
                    <br style="clear: both" />
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
                        'onclick' => 'parent.location=\''. site_url('menimbang/detail') . "/" . $id_izin . '\''
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
