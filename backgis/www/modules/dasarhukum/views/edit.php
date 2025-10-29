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
<!--                    <li><a href="#tabs-1">Pilih Dasar Hukum dari Database</a></li>-->
                    <li><a href="#tabs-2">Tambah Dasar Hukum Baru</a></li>
                </ul>
<!--                <div id="tabs-1">
                    <?php
                    $attr = array('id' => 'form');
                        echo form_open('dasarhukum/savelist',$attr);
                        echo form_hidden('id_izin', $id_izin);
                        
                        foreach ($list_izin as $listizin) {
                            $listizin->trdasar_hukum->get();
                            foreach ($list as $list_dasar_hukum) {
                                $showed = TRUE;
                                if ($list_dasar_hukum->id === $listizin->trdasar_hukum->id) {
                                    $showed = FALSE;
                                    break;
                                }

                                if ($showed) {
                                    $set = array(
                                        'name' => 'dasarhukum[]',
                                        'value' => $list_dasar_hukum->id
                                    );
                                    echo form_checkbox($set);
                                    echo word_wrap($list_dasar_hukum->deskripsi);
                                    echo "<br />";
                                }
                            }
                        }
                        $add_dasar_hukum = array(
                            'name' => 'submit',
                            'class' => 'submit-wrc',
                            'content' => 'Simpan',
                            'type' => 'submit',
                            'value' => 'Simpan'
                        );
                        echo form_submit($add_dasar_hukum);
                        echo "<span></span>";
                        $cancel_list = array(
                            'name' => 'button',
                            'class' => 'button-wrc',
                            'content' => 'Batal',
                            'onclick' => 'parent.location=\''. site_url('dasarhukum/detail') . "/" . $id_izin . '\''
                        );
                        echo form_button($cancel_list);
                        echo form_close();
                    ?>
                </div>-->
                <div id="tabs-2">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('dasarhukum/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    ?>
<!--                    <label class="label-wrc">Nama Dasar Hukum</label>-->
                    <?php
                    $nama = array(
                        'name' => 'nama',
                        'value' => $nama,
                        'class' => 'input-wrc',
                    );
//                    echo form_input($nama);
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
<!--                    <label class="label-wrc">Tanggal Berlaku</label>-->
                    <?php
                    $tgl_berlaku = array(
                        'name' => 'tgl_berlaku',
                        'value' => $tgl_berlaku,
                        'class' => 'input-wrc required date',
                        'readOnly'=>TRUE,
                        'id' => 'tgl_berlaku'
                    );
//                    echo form_input($tgl_berlaku);
                    ?>
                    <br style="clear: both" />
<!--                    <label class="label-wrc">Tanggal Berakhir</label>-->
                    <?php
                    $tgl_berakhir = array(
                        'name' => 'tgl_berakhir',
                        'value' => $tgl_berakhir,
                        'class' => 'input-wrc required date',
                        'readOnly'=>TRUE,
                        'id' => 'tgl_berakhir'
                    );
//                    echo form_input($tgl_berakhir);
                    ?>
                    <br style="clear: both" />
                    <label class="label-wrc">Status</label>
                    <?php
                        $status = "<input type=\"radio\" name=\"status\" ";
                        $status_0 = "value=\"0\"";
                        $status_1 = "value=\"1\"";
                        if ($status_cont === '0') {
                            echo $status . $status_0 . "checked=\"checked\" />";
                            echo "Surat Izin ";
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
                        'onclick' => 'parent.location=\''. site_url('dasarhukum/detail') . "/" . $id_izin . '\''
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
                    <li><a href="#tabs-1">Edit Dasar Hukum Baru</a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('dasarhukum/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    echo form_hidden('id_dasar_hukum', $id_dasar_hukum);
                    ?>
<!--                    <label class="label-wrc">Nama Dasar Hukum</label>-->
                    <?php
                    $nama = array(
                        'name' => 'nama',
                        'value' => $nama,
                        'class' => 'input-wrc',
                    );
                    //echo form_input($nama);
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
<!--                    <br style="clear: both" />
                    <label class="label-wrc">Tanggal Berlaku</label>-->
                    <?php
                    $tgl_berlaku = array(
                        'name' => 'tgl_berlaku',
                        'value' => $tgl_berlaku,
                        'class' => 'input-wrc',
                        'id' => 'tgl_berlaku'
                    );
//                    echo form_input($tgl_berlaku);
                    ?>
<!--                    <br style="clear: both" />
                    <label class="label-wrc">Tanggal Berakhir</label>-->
                    <?php
                    $tgl_berakhir = array(
                        'name' => 'tgl_berakhir',
                        'value' => $tgl_berakhir,
                        'class' => 'input-wrc',
                        'id' => 'tgl_berakhir'
                    );
//                    echo form_input($tgl_berakhir);
                    ?>
                    <br style="clear: both" />
                    <label class="label-wrc">Status</label>
                    <?php
                        $status = "<input type=\"radio\" name=\"status\" ";
                        $status_0 = "value=\"0\"";
                        $status_1 = "value=\"1\"";
                        if ($status_cont === '0') {
                            echo $status . $status_0 . "checked=\"checked\" />";
                            echo "Surat Izin ";
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
                        'onclick' => 'parent.location=\''. site_url('dasarhukum/detail') . "/" . $id_izin . '\''
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
