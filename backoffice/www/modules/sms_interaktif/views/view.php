<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
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
                <div style="text-align:right">
                    <?php
                    $img_back = array(
                        'src' => 'assets/images/icon/back_alt.png',
                        'alt' => 'Back',
                        'title' => 'Back',
                        'border' => '0',
                    );
                    echo anchor(site_url('sms_interaktif/index'), img($img_back))."&nbsp;";
                    ?>
                </div>
            </fieldset>
        </div>
    </div>
    <br style="clear: both;" />
</div>
