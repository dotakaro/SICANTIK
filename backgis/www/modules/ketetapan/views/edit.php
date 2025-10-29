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
<!--                    <li><a href="#tabs-1">Pilih Ketetapan dari Database</a></li>-->
                    <li><a href="#tabs-2">Tambah Ketentuan Surat Izin</a></li>
                </ul>
<!--                <div id="tabs-1">
                    <?php
                    $attr = array('id' => 'form');
                        echo form_open('ketetapan/savelist',$attr);
                        echo form_hidden('id_izin', $id_izin);
                        
                        foreach ($list_izin as $listizin) {
                            $listizin->trketetapan->get();
                            foreach ($list as $list_ketetapan) {
                                $showed = TRUE;
                                if ($list_ketetapan->id === $listizin->trketetapan->id) {
                                    $showed = FALSE;
                                    break;
                                }

                                if ($showed) {
                                    $set = array(
                                        'name' => 'dasarhukum[]',
                                        'value' => $list_ketetapan->id
                                    );
                                    echo form_checkbox($set);
                                    echo word_wrap($list_ketetapan->n_ketetapan);
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
                            'onclick' => 'parent.location=\''. site_url('ketetapan/detail') . "/" . $id_izin . '\''
                        );
                        echo form_button($cancel_list);
                        echo form_close();
                    ?>
                </div>-->
                <div id="tabs-2">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('ketetapan/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    ?>
                    <label class="label-wrc">Deskripsi</label>
                    <?php
                    $deskripsi = array(
                        'name' => 'n_ketetapan',
                        'value' => $n_ketetapan,
                        'class' => 'input-area-wrc required',
                        'style' => 'min-width:400pt'
                    );
                    echo form_textarea($deskripsi);
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
                        'onclick' => 'parent.location=\''. site_url('ketetapan/detail') . "/" . $id_izin . '\''
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
                    <li><a href="#tabs-1">Edit Ketetapan</a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('ketetapan/' . $save_method,$attr);
                    echo form_hidden('id_izin', $id_izin);
                    echo form_hidden('id_ketetapan', $id_ketetapan);
                    ?>
                    <label class="label-wrc">Ketetapan</label>
                    <?php
                    $deskripsi = array(
                        'name' => 'n_ketetapan',
                        'value' => $n_ketetapan,
                        'class' => 'input-area-wrc required',
                        'style' => 'min-width: 400pt'
                    );
                    echo form_textarea($deskripsi);
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
                        'onclick' => 'parent.location=\''. site_url('ketetapan/detail') . "/" . $id_izin . '\''
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
