<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Pegawai</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <?php
                        $cek = "checked";
                        $attr = array('id' => 'form');
                        echo form_open('petugas/' . $save_method, $attr);
                        echo form_hidden('id', $id);
                        ?>
                        <div class="contentForm">
                            <label class="label-wrc">Nama Pegawai</label>
                            <?php
                            $n_petugas_input = array(
                                'name' => 'n_pegawai',
                                'value' => $n_pegawai,
                                'class' => 'input-wrc required'
                            );
                            echo form_input($n_petugas_input);
                            ?>

                        </div>
                        <div class="contentForm">
                            <label class="label-wrc">NIP</label>
                            <?php
                            $nip_input = array(
                                'name' => 'nip',
                                'value' => $nip,
                                'class' => 'input-wrc required'
                            );
                            echo form_input($nip_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <label class="label-wrc">Jabatan</label>
                            <?php
                            $n_jabatan_input = array(
                                'name' => 'n_jabatan',
                                'value' => $n_jabatan,
                                'class' => 'input-wrc required'
                            );
                            echo form_input($n_jabatan_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <label class="label-wrc">Satuan Kerja</label>
                            
                             <select name="unitkerja" class="input-select-wrc">
                                <!--option selected="selected">---------Pilih salah satu---------</option-->
                                <?php
                                $selected = NULL;
                                foreach ($unit_kerja as $unit_kerja_data) {
                                    if ($unit_kerja_data->id === $unit_kerja_id) {
                                        $selected = ' selected="selected" ';
                                    } else {
                                        $selected = 'ok';
                                    }

                                    echo "<option value=\"" . $unit_kerja_data->id . "\"" . $selected . ">"
                                    . $unit_kerja_data->n_unitkerja . "</option>\n";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="contentForm">
                            <label class="label-wrc">Email</label>
                            <?php
                            $email_input = array(
                                'name' => 'email',
                                'value' => $email,
                                'class' => 'input-wrc required email'
                            );
                            echo form_input($email_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <label class="label-wrc">No. HP</label>
                            <?php
                            $telp_input = array(
                                'name' => 'no_telp',
                                'value' => $no_telp,
                                'class' => 'input-wrc required number'
                            );
                            echo form_input($telp_input);
                            ?>
                        </div>
                            <div>
                                <label class="label-wrc">Penanda Tangan</label>
                                <label>
                                    <input type="radio" name="ststtd" value="1" <?php if($status_cont=="1") echo "checked"; ?>/>
                                    Ya</label>

                                <label>
                                    <input type="radio" name="ststtd" value="0" <?php if($status_cont=="0") echo "checked"; ?>/>
                                    Tidak</label>
                            </div>
						<div>
                                <label class="label-wrc">Urutan</label>
                                <label>
                                    <input type="radio" name="urutan" value="0" <?php if($urutan=="0") echo "checked"; ?>/>
                                    Awal</label>

                                <label>
                                    <input type="radio" name="urutan" value="1" <?php if($urutan=="0") echo "checked"; ?>/>
                                    Akhir</label>
                            </div>
                    </div>

                    <br />
                    <br style="clear: both;" />
                </div>
            </div>
        </div>
        <div class="entry">
<?php
$add_petugas = array(
    'name' => 'submit',
    'class' => 'submit-wrc',
    'content' => 'Simpan',
    'type' => 'submit',
    'value' => 'Simpan'
);
echo form_submit($add_petugas);
echo "<span></span>";
$cancel_petugas = array(
    'name' => 'button',
    'class' => 'button-wrc',
    'content' => 'Batal',
    'onclick' => 'parent.location=\'' . site_url('petugas') . '\''
);
echo form_button($cancel_petugas);
echo form_close();
?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
