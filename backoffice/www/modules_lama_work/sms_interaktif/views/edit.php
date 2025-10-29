<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <?php
                $attr = array('id' => 'form'/*,'onSubmit'=>'return cekNilai();'*/);
                echo form_open('sms_interaktif/save', $attr);
                echo form_hidden('id', $data_sms->id);
                ?>
                <legend>Data Permohonan</legend>
                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo form_label('Nomor HP');
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        echo $data_sms->no_hp;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail"  class="bg-grid">
                        <?php
                        echo form_label('Nama');
                        ?>
                    </div>
                    <div id="rightRail"  class="bg-grid">
                        <?php
                        echo $data_sms->nama;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail"  class="bg-grid">
                        <?php
                        echo form_label('Tanggal Masuk');
                        ?>
                    </div>
                    <div id="rightRail"  class="bg-grid">
                        <?php
                        echo $data_sms->tgl_masuk;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo form_label('Tipe SMS');
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        echo $data_sms->tipe_sms;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail"  class="bg-grid">
                        <?php
                        echo form_label('Isi SMS');
                        ?>
                    </div>
                    <div id="rightRail"  class="bg-grid">
                        <?php
                        echo $data_sms->isi_sms;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo form_label('Raw SMS');
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        echo $data_sms->raw_sms;
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo form_label('Balas SMS');
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        echo ($data_sms->raw_sms ==1)?"Sudah":"Belum";
                        ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo form_label('SMS Balasan');
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        $reply_sms = array(
                            'name' => 'reply_sms',
                            'value' => $data_sms->reply_sms,
                            'style' => 'width:98%',
                            'class' => 'input-area-wrc required'
                        );
                        echo form_textarea($reply_sms);
                        ?>
                    </div>
                </div>
                <div style="text-align:right">
                    <?php
                    /*$img_back = array(
                        'src' => 'assets/images/icon/back_alt.png',
                        'alt' => 'Back',
                        'title' => 'Back',
                        'border' => '0',
                    );
                    echo anchor(site_url('sms_interaktif/index'), img($img_back))."&nbsp;";*/
                    $add_pesan = array(
                        'name' => 'submit',
                        'class' => 'submit-wrc',
                        'content' => 'Simpan',
                        'type' => 'submit',
                        'value' => 'Simpan'
                    );
                    echo form_submit($add_pesan);
                    echo "<span></span>";
                    $cancel_message = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Batal',
                        'onclick' => 'parent.location=\''. site_url('sms_interaktif') . '\''
                    );
                    echo form_button($cancel_message);
                    echo form_close();
                    ?>
                </div>
            </fieldset>
        </div>
    </div>
    <br style="clear: both;" />
</div>
