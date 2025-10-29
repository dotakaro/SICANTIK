<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'mainform');//Indra
        echo form_open('notification_setting/save/', $attr);
        $hidden_setting_notifikasi_id= array(
            'name' => 'id',
            'id'=>'NotificationSettingId',
            'type'=>'hidden'
        );
        echo form_input($hidden_setting_notifikasi_id);
        $index = 0;
        ?>
        <div class="entry">
            <fieldset id="half">
                <legend>Data Perizinan</legend>
                <div id="statusRail">
                    <div class="bg-grid" id="leftRail">
                        <?php echo form_label('Nama Izin');?>
                    </div>
                    <div class="bg-grid" id="rightRail">
                        <?php
                        $trperizinan_id = array(
                            'name' => 'trperizinan_id',
                            'value' => $perizinan->id,
                            'type'=>'hidden',
                            'id'=>'SettingNotifikasiTrperizinanId'
                        );
                        echo form_input($trperizinan_id);
                        echo $perizinan->n_perizinan;
                        ?>

                    </div>
                </div>
                <!--<div id="statusRail">
                    <div id="leftRail">
                        <?php /*echo form_label('Tipe Notifikasi');*/?>
                    </div>
                    <div id="rightRail">
                        <?php /*echo form_dropdown("tipe_notifikasi", $list_tipe_notifikasi, null,' class="input-select-wrc required-option"');*/?>
                    </div>
                </div>-->
            </fieldset>
            <?php echo $this->load->view('info_variabel');?>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                <?php foreach($list_status as $key=>$status_permohonan){?>
                    <li><a href="#tabs-<?php echo $key;?>"><b><?php echo $status_permohonan->n_sts_permohonan;?></b></a></li>
                <?php }?>
                </ul>
                <?php foreach($list_status as $key=>$status_permohonan){?>
                <div id="tabs-<?php echo $key;?>">
                    <div id="tabs-horizontal-<?php echo $key;?>" class="tabs-horizontal">
                        <ul>
                            <li><a href="#tabs-horizontal-<?php echo $key;?>-1">Ke Pemohon</a></li>
                            <?php if(in_array($status_permohonan->id, $status_notif_petugas)){?>
                            <li><a href="#tabs-horizontal-<?php echo $key;?>-2">Ke Petugas</a></li>
                            <?php }?>
                        </ul>
                        <div id="tabs-horizontal-<?php echo $key;?>-1">
                            <?php
                            $status_permohonan_id = $status_permohonan->id;
                            $hidden_trstspermohonan_id = array(
                                'name' => 'SettingNotifikasiDetail['.$index.'][trstspermohonan_id]',
                                'value' => $status_permohonan_id,
                                'type'=>'hidden',
                                'id'=>'SettingNotifikasiDetail'.$index.'TrstatuspermohonanId'
                            );
                            $hidden_tujuan_notifikasi = array(
                                'name' => 'SettingNotifikasiDetail['.$index.'][tujuan_notifikasi]',
                                'value' => 1,//1  = Ke Pemohon
                                'type'=>'hidden',
                                'id'=>'SettingNotifikasiDetail'.$index.'TujuanNotifikasi'
                            );
                            $txt_message_format = array(
                                'name' => 'SettingNotifikasiDetail['.$index.'][format_pesan]',
                                'value' => null,
                                'class' => 'input-area-wrc message-format',
                                'id'=>'SettingNotifikasiDetail'.$index.'FormatPesan'
                            );
                            $txt_penerima_lain = array(
                                'name'=>'SettingNotifikasiDetail['.$index.'][penerima_lain]',
                                'type'=>'text',
                                'class'=>'penerima-lain'
                            );

                            echo form_input($hidden_trstspermohonan_id);
                            echo form_input($hidden_tujuan_notifikasi);
                            echo 'Tipe Notifikasi';
                            echo '<br style="clear: both" />';
                            echo form_dropdown("SettingNotifikasiDetail[$index][tipe_notifikasi]", $list_tipe_notifikasi, 'sms',' class="input-select-wrc required-option"');
                            echo '<br>';
                            echo 'Format Pesan';
                            echo '<br style="clear: both" />';
                            echo form_textarea($txt_message_format);
                            echo '<br style="clear: both" />';
                            echo 'Penerima Lain (selain Pemohon)';
                            echo '<br style="clear: both" />';
                            echo form_input($txt_penerima_lain);
                            $index++;
                            ?>
                            <br style="clear: both" />
                        </div>

                        <?php if(in_array($status_permohonan->id, $status_notif_petugas)){?>
                        <div id="tabs-horizontal-<?php echo $key;?>-2">
                            <?php
                            $status_permohonan_id = $status_permohonan->id;
                            $hidden_trstspermohonan_id = array(
                                'name' => 'SettingNotifikasiDetail['.$index.'][trstspermohonan_id]',
                                'value' => $status_permohonan_id,
                                'type'=>'hidden',
                                'id'=>'SettingNotifikasiDetail'.$index.'TrstatuspermohonanId'
                            );
                            $hidden_tujuan_notifikasi = array(
                                'name' => 'SettingNotifikasiDetail['.$index.'][tujuan_notifikasi]',
                                'value' => 2,//2  = Ke Petugas
                                'type'=>'hidden',
                                'id'=>'SettingNotifikasiDetail'.$index.'TujuanNotifikasi'
                            );
                            $txt_message_format = array(
                                'name' => 'SettingNotifikasiDetail['.$index.'][format_pesan]',
                                'value' => null,
                                'class' => 'input-area-wrc message-format',
                                'id'=>'SettingNotifikasiDetail'.$index.'FormatPesan'
                            );
                            $txt_penerima_lain = array(
                                'name'=>'SettingNotifikasiDetail['.$index.'][penerima_lain]',
//                                'type'=>'text',
                                'type'=>'hidden',
                                'class'=>'penerima-lain'
                            );

                            echo form_input($hidden_trstspermohonan_id);
                            echo form_input($hidden_tujuan_notifikasi);

                            echo 'Tipe Notifikasi';
                            echo '<br style="clear: both" />';
                            echo form_dropdown("SettingNotifikasiDetail[$index][tipe_notifikasi]", $list_tipe_notifikasi, 'sms',' class="input-select-wrc required-option"');
                            echo '<br>';
                            echo 'Format Pesan';
                            echo '<br style="clear: both" />';
                            echo form_textarea($txt_message_format);
                            echo '<br style="clear: both" />';
//                            echo 'Penerima Lain (selain Petugas)';
//                            echo '<br style="clear: both" />';
//                            echo form_input($txt_penerima_lain);
                            $index++;
                            ?>
                            <br style="clear: both" />
                        </div>
                        <?php }?>
                    </div>

                </div>
                <?php }?>
            </div>
            <?php
            $add_koefisien = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_koefisien);
            echo "<span></span>";
            $cancel_koefisien = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('notification_setting').'\''
            );
            echo form_button($cancel_koefisien);

            ?>
        </div>
        <?php echo form_close();?>
    </div>

    <!--Dialog Popup-->
    <div id="query_form" style="display:none ;">
        <div class="input text">
            <?php echo form_textarea(array('name'=>'query_text','id'=>'query_text'));?>
        </div>
    </div>
    <!---->

    <br style="clear: both;" />
</div>