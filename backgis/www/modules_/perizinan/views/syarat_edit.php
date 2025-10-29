<script>
    function cekstatuscheckbox(frmchk)
    {
        var chk=document.getElementById("chk"+frmchk);
        if(chk.checked == true)
        {
                document.getElementById("subchk1"+frmchk).disabled="";   
                document.getElementById("subchk2"+frmchk).disabled="";   
                document.getElementById("subchk3"+frmchk).disabled="";
                document.getElementById("subcmb"+frmchk).disabled="";
        }
        else
        {
                document.getElementById("subchk1"+frmchk).disabled="disabled";   
                document.getElementById("subchk2"+frmchk).disabled="disabled";   
                document.getElementById("subchk3"+frmchk).disabled="disabled";
                document.getElementById("subcmb"+frmchk).disabled="disabled";
                document.getElementById("subchk1"+frmchk).checked=false;   
                document.getElementById("subchk2"+frmchk).checked=false;   
                document.getElementById("subchk3"+frmchk).checked=false;
                document.getElementById("subcmb"+frmchk).value="2";
        }
    }
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">

            <div id="tabs">
                <ul>
                    <?php
                        $open = FALSE;
                        $cancel_syarat = array(
                            'name' => 'button',
                            'class' => 'button-wrc',
                            'content' => 'Batal',
                            'onclick' => 'parent.location=\''. site_url('perizinan/persyaratanizin/detail') . "/" . $perizinan_id  . '\''
                        );
                        if($save_method=='save'){
                            $open = TRUE;
                    ?>
                    <li><a href="#tabs-1">Tambah Syarat Izin dari Database</a></li>
                    <?php
                        }
                    ?>
                    <li><a href="#tabs-2">Tambah Syarat Izin Baru</a></li>
                </ul>
                <?php
                    if($open) {
                        ?>
                <div id="tabs-1">
                    <ul id="data_list">
                        <?php
                        if($save_method=='save'){
                            echo form_open('perizinan/persyaratanizin/' . $save_method. '_list');
                            echo form_hidden('id', $id);
                            echo form_hidden('perizinan_id', $perizinan_id);
                        ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="syarat">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Nama Syarat</th>
                                <th>Status</th>
                                <th>Izin Baru/Daftar Ulang</th>
                                <th>Perpanjangan Izin</th>
                                <th>Perubahan Izin</th>
                            </tr>
                        </thead>
                        <tbody> 
                        <?php
                        $i=1;
                            foreach ($syarat_list as $syarat) {
                                $showed = TRUE;
                                foreach ($perizinan_syarat as $izin_syarat) {
                                    if($izin_syarat->id === $syarat->id) {
                                        $showed = FALSE;
                                        break;
                                    }
                                }

                                if($showed) {
                                    echo "<tr>";
                                    $set = array(
                                        'name' => 'syarat[]',
                                        'value' => $syarat->id,
                                        "id"=>"chk".$i,
                                        "onclick"=>"cekstatuscheckbox('".$i."');"
                                    );
                                    echo "<td>".form_checkbox($set)."</td>";
                                    echo "<td>".$syarat->v_syarat."</td>";
                                    $set_baru = array(
                                        'name' => 'c_baru[]',
                                        'value' => $syarat->id,
                                        "disabled"=>"disabled",
                                        "id"=>"subchk1".$i
                                    );
                                    $opsi_status = array('2'  => 'Tidak Wajib','1'  => 'Wajib');                            
                                   echo "<td>".form_dropdown('status[]', $opsi_status,'2',' disabled="disabled" class = "input-select-all" id="subcmb'.$i.'"')."</td>";
                                    
                                    echo "<td align='center'>".form_checkbox($set_baru)."</td>";
                                    $set_tambah = array(
                                        'name' => 'c_tambah[]',
                                        'value' => $syarat->id,
                                        "disabled"=>"disabled",
                                        "id"=>"subchk2".$i
                                    );
                                    echo "<td align='center'>".form_checkbox($set_tambah)."</td>";
                                    $set_ubah = array(
                                        'name' => 'c_ubah[]',
                                        'value' => $syarat->id,
                                        "disabled"=>"disabled",
                                        "id"=>"subchk3".$i
                                    );
                                    echo "<td align='center'>".form_checkbox($set_ubah)."</td>";
                                }
                                $i++;
                            }
                        ?>
                        </tbody>
                    </table>
                        <div class="contentForm" style="text-align: center; margin-top: 10px;">
                            <?php
                            $add_syarat = array(
                                'name' => 'submit',
                                'class' => 'submit-wrc',
                                'content' => 'Simpan',
                                'type' => 'submit',
                                'value' => 'Simpan'
                            );
                            echo form_submit($add_syarat);
                            echo "<span></span>";
                            echo form_button($cancel_syarat);
                            echo form_close();
                        }
                            ?>
                        </div>
                    </ul>
                </div>
                        <?php
                    }
                ?>
                <div id="tabs-2">
                    <?php
                        $attr = array('id' => 'form');
                        echo form_open('perizinan/persyaratanizin/' . $save_method,$attr);
                        echo form_hidden('id', $id);
                        echo form_hidden('si', $si);
                        echo form_hidden('si2', $si2);
                        echo form_hidden('perizinan_id', $perizinan_id);
                    ?>
                    <div class="contentForm">
                        <?php
                            $v_syarat_input = array(
                                'name' => 'v_syarat',
                                'value' => $v_syarat,
                                'class' => 'input-area-wrc required',
                                'style' => 'min-width:400pt'
                            );
                            echo form_label('Nama Syarat');
                            echo form_textarea($v_syarat_input);
                        ?>
                    </div>
                    <div class="contentForm">
                        <?php
                            $opsi_status = array(
                              //'3'  => '-------------',
                              '2'  => 'Tidak Wajib',
                              '1'  => 'Wajib'
                            );
                            
                            echo form_label('Status');
                            if ($status=="ok")
                            {
                             echo form_dropdown('status', $opsi_status,'2','class = "input-select-all"');
                            }
                            else
                            {
                            echo form_dropdown('status', $opsi_status,$status,'class = "input-select-all"');
                            }
                            ?>
                        
                    </div>
                    <div style="clear: both"/>
                    <div class="contentForm">
                        <?php
                            $opsi_status2 = array(
                              //'2'  => '------',
                              '1'  => 'Ya',
                              '0'  => 'Tidak'
                            );
                            echo form_label('Pendaftaran Baru/ Daftar Ulang');
                            if($c_baru=="ok")
                            {
                                echo form_dropdown('c_baru', $opsi_status2,'1','class = "input-select-all"');
                            }
                            else
                            {
                                echo form_dropdown('c_baru', $opsi_status2,$c_baru,'class = "input-select-all"');
                            }

                            
                        ?>
                        <div style="clear: both"/>
                    </div>
                    <div class="contentForm">
                        <?php
                            $opsi_statusa = array(
                              //'2'  => '------',
                              '1'  => 'Ya',
                              '0'  => 'Tidak'
                            );
                            echo form_label('Perpanjangan Izin');
                            if($c_perpanjangan=="ok")
                            {
                                 echo form_dropdown('c_perpanjangan', $opsi_statusa,'1','class = "input-select-all"');
                            }
                            else
                            {
                                echo form_dropdown('c_perpanjangan', $opsi_statusa,$c_perpanjangan,'class = "input-select-all"');
                            }
                           
                        ?>
                        <div style="clear: both"/>
                    </div>
                    <div class="contentForm">
                        <?php
                            $opsi_statusb = array(
                              //'2'  => '------',
                              '1'  => 'Ya',
                              '0'  => 'Tidak'
                            );
                            echo form_label('Perubahan Izin');
                            if($c_ubah=="ok")
                            {
                               echo form_dropdown('c_ubah', $opsi_statusb,'1','class = "input-select-all"');
                            }
                            else
                            {
                                echo form_dropdown('c_ubah', $opsi_statusb,$c_ubah,'class = "input-select-all"');
                            }
                            
                        ?>
                        <div style="clear: both"/>
                    </div>
<!--                    <div class="contentForm">
                        <?php
                            $opsi_statusc = array(
                             // '2'  => '------',
                              '1'  => 'Ya',
                              '0'  => 'Tidak'
                            );
                            echo form_label('Daftar Ulang');
                            echo form_dropdown('c_daftar_ulang', $opsi_statusc,'1','class = "input-select-all"');
                        ?>                        
                    </div>-->
<div style="clear: both"/>
                    <div class="contentForm" style="text-align: center; margin-top: 10px;">
                    <?php
                        $add_syarat2 = array(
                            'name' => 'submit',
                            'class' => 'submit-wrc',
                            'content' => 'Simpan',
                            'type' => 'submit',
                            'value' => 'Simpan'
                        );
                        echo form_submit($add_syarat2);
                        echo "<span></span>";
                        echo form_button($cancel_syarat);
                        echo form_close();
                    ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <br style="clear: both;" />
</div>