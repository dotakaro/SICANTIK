<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'form');
            echo form_open('perizinan/' . $save_method, $attr);
            echo form_hidden('id', $id);
            ?>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Data Jenis Perizinan</b></a></li>
                    <li><a href="#tabs-2"><b>Unit Akses</b></a></li>
                </ul>
                <div id="tabs-1">
                    <label>Jenis perizinan</label>
                    <?php

                    $n_ijin_input = NULL;

                    if($save_method === 'save') {
                        $n_ijin_input = array(
                            'name' => 'n_perizinan',
                            'value' => $n_perizinan,
                            'class' => 'input-wrc required'
                        );
                    } else {
                        $n_ijin_input = array(
                            'name' => 'n_perizinan',
                            'value' => $n_perizinan,
                            'class' => 'input-wrc required'
        //                    ,'disabled' => 'disabled'
                        );
                    }
                    echo form_input($n_ijin_input);
                    ?><br style="clear: both" />

                    <label>Durasi Lama Pengerjaan (Hari)</label>
                    <?php
                        $v_hari_input = array(
                            'name' => 'v_hari',
                            'value' => $v_hari,
                            'class' => 'input-wrc required digits'
                        );
                        echo form_input($v_hari_input);
                    ?><br style="clear: both" />


                    <label>Lama Berlaku Izin</label>
                    <?php
                        if($v_berlaku_satuan!='selamanya'){
                            $v_berlaku_tahun_input = array(
                                'name' => 'v_berlaku_tahun',
                                'value' => $v_berlaku_tahun,
                                'class' => 'input-wrc required-indra digits',
                                'id'=>'v_berlaku_tahun',
                                'style'=>'margin-right:5px;'
                            );
                        }else{
                            $v_berlaku_tahun_input = array(
                                'name' => 'v_berlaku_tahun',
                                'class' => 'input-wrc required-indra digits',
                                'id'=>'v_berlaku_tahun',
                                'style'=>'margin-right:5px;display:none;'
                            );
                        }
                        echo form_input($v_berlaku_tahun_input);
                        echo form_dropdown('v_berlaku_satuan',$list_berlaku_satuan,$v_berlaku_satuan,'class="input-select-wrc" id="v_berlaku_satuan"');
                    ?><br style="clear: both" />

                    <label>Target Anggaran</label>
                    <?php
                        $v_perizinan_input = array(
                            'name' => 'v_perizinan',
                            'value' => $v_perizinan,
                            'class' => 'input-wrc required digits'
                        );
                        echo form_input($v_perizinan_input);
                    ?><br style="clear: both" />

                    <label>Kelompok</label>
                    <select name="opsi_klp" class="input-select-wrc">
                        <!--option value="xx" selected="selected"> ------Pilih salah satu------ </option-->
                        <?php
                            $selected = NULL;
                            foreach ($list_klp as $data) {
                                if ($data->id === $kelompok_id) {
                                    $selected = ' selected="selected" ';

                                } else {
                                    $selected = NULL;
                                }

                               echo "<option value=\"" . $data->id . "\"" . $selected . ">". $data->n_kelompok . "</option>\n" ;
                           }

                        ?>
                    </select>

                    <br style="clear: both" />

<!--                    <label>Unit Kerja</label>-->
<!--                    <select name="opsi_uk" class="input-select-wrc">-->
                        <?php
                            /*$selected = NULL;
                            foreach ($list_uk as $dtunitkerja) {
                              if ($dtunitkerja->id === $unitkerja_id) {
                                    $selected = ' selected="selected" ';
                                } else {
                                    $selected = null;
                                }

                             echo "<option value=\"" . $dtunitkerja->id . "\"" . $selected . ">"
                                    . $dtunitkerja->n_unitkerja . "</option>\n" ;

                            }*/
                        ?>
<!--                    </select>-->
<!--                    <br style="clear: both" />-->
                        <?php
                        if($is_open == 1){
                            $select = "";
                            $selectx = "selected='selected'";
                            $selecty = "";
                        }
                        else if ($is_open == 0){
                             $select = "";
                            $selectx = "";
                            $selecty = "selected='selected'";
                        }
                        else if ($is_open == 2)
                        {
                            $select = "selected='selected'";
                            $selectx = "";
                            $selecty = "";

                        }

                    ?>
                    <label>Jenis Izin Terbuka</label>
                    <select name="is_open" class="input-select-wrc">
                        <!--option value="xx" <?php echo $select; ?>> ------Pilih salah satu------ </option-->
                           <option value="1" <?php echo $selectx; ?>>Ya</option>
                        <option value="0" <?php echo $selecty; ?>>Tidak</option>
                    </select>
                    <br style="clear: both" />

                   <?php
                        if($c_foto == 1){
                            $select = "";
                            $select1 = "selected='selected'";
                            $select2 = "";
                        }
                        else if ($c_foto == 0){
                            $select = "";
                            $select1 = "";
                            $select2 = "selected='selected'";
                        }
                        else if($c_foto == 2)
                        {
                            $select = "selected='selected'";
                            $select1 = "";
                            $select2 = "";

                        }
                    ?>
                    <label>Tandatangan Pemohon</label>
                    <select name="c_foto" class="input-select-wrc">
                        <!--option value="xx" <?php echo $select; ?>> ------Pilih salah satu------ </option-->
                       <option value="1" <?php echo $select1; ?>>Ya</option>
                        <option value="0" <?php echo $select2; ?>>Tidak</option>
                    </select>
                    <br style="clear: both" />
                    <?php
                        if($c_keputusan == 1){
                            $select = "";
                            $selecta = "selected='selected'";
                            $selectb = "";
                        }
                        else if ($c_keputusan == 0){
                            $select = "";
                            $selecta = "";
                            $selectb = "selected='selected'";
                        }
                        else if ($c_keputusan == 2)
                        {
                            $select = "selected='selected'";
                            $selecta = "";
                            $selectb = "";

                        }
                    ?>
                   <label>Surat Keputusan</label>
                    <select name="c_keputusan" class="input-select-wrc">
                        <!--option value="xx"  <?php echo $select; ?>> ------Pilih salah satu------ </option-->
                        <option value="1" <?php echo $selecta; ?>>Ya</option>
                        <option value="0" <?php echo $selectb; ?>>Tidak</option>
                    </select>
                    <br style="clear: both" />
                    <?php
                        if($c_berlaku == 1){
                            $select = "";
                            $selectc = "selected='selected'";
                            $selectd = "";
                        }
                        else if ($c_berlaku == 0) {
                            $select = "";
                            $selectc = "";
                            $selectd = "selected='selected'";
                        }
                        else  if ($c_berlaku == 2)
                        {
                            $select = "selected='selected'";
                            $selectc = "";
                            $selectd = "";

                        }
                    ?>

                    <label>Tampilkan Masa Berlaku</label>
                    <select name="c_berlaku" class="input-select-wrc">
                        <!--option value="xx" <?php echo $select; ?>> ------Pilih salah satu------ </option-->
                        <option value="1" <?php echo $selectc; ?>>Ya</option>
                        <option value="0" <?php echo $selectd; ?>>Tidak</option>
                    </select>
                    <br style="clear: both" />
					
					<?php
                        if($c_upload == 1){
                            $select = "";
                            $selecte = "selected='selected'";
                            $selectf = "";
                        }
                        else if ($c_upload == 0) {
                            $select = "";
                            $selecte = "";
                            $selectf = "selected='selected'";
                        }
                        else  if ($c_upload == 2)
                        {
                            $select = "selected='selected'";
                            $selecte = "";
                            $selectf = "";

                        }
					?>
					<label>Upload Berkas</label>
                    <select name="c_upload" class="input-select-wrc">
                        <!--option value="xx" <?php echo $select; ?>> ------Pilih salah satu------ </option-->
                        <option value="1" <?php echo $selecte; ?>>Ya</option>
                        <option value="0" <?php echo $selectf; ?>>Tidak</option>
                    </select>
                    <br style="clear: both" />
					
					<?php
                        if($cek_bpjs == 1){
                            $select = "";
                            $selectg = "selected='selected'";
                            $selecth = "";
                        }
                        else if ($cek_bpjs == 0) {
                            $select = "";
                            $selectg = "";
                            $selecth = "selected='selected'";
                        }
                        else  if ($cek_bpjs == 2)
                        {
                            $select = "selected='selected'";
                            $selectg = "";
                            $selecth = "";

                        }
					?>
					<label>Cek BPJS Ketenagakerjaan</label>
                    <select name="cek_bpjs" class="input-select-wrc">
                        <!--option value="xx" <?php echo $select; ?>> ------Pilih salah satu------ </option-->
                        <option value="1" <?php echo $selectg; ?>>Ya</option>
                        <option value="0" <?php echo $selecth; ?>>Tidak</option>
                    </select>
                    <br style="clear: both" />
                </div>
                <div id="tabs-2">
                    Unit yang boleh membuat izin
                    <?php
                    //Multiple Checkbox
                    /*if($getUnit->id){
                        echo '<br>';
                        foreach($getUnit as $indexUnit =>$optUnitKerja){
                            $checked = false;
                            if(in_array($optUnitKerja->id, $listHakAkses)){
                                $checked = true;
                            }
                            echo form_checkbox('unit_akses['.$indexUnit.']', $optUnitKerja->id, $checked, $extra = 'id="unit_akses_'.$indexUnit.'"');
                            echo $optUnitKerja->n_unitkerja;
                            echo '<br>';
                        }
                    }*/

                    //Multiselect kiri kanan
                    if($getUnit->id){
                        echo '<select id="unit_akses" class="michaelMultiselect" multiple="multiple" name="unit_akses[]">';
                        foreach($getUnit as $indexUnit =>$optUnitKerja){
                            $selected = '';
                            if(in_array($optUnitKerja->id, $listHakAkses)){
                                $selected = 'selected="selected"';
                            }
                            echo '<option value="'.$optUnitKerja->id.'" '.$selected.'>'.$optUnitKerja->n_unitkerja.'</option>';
                        }
                        echo '</select>';
                    }
                    ?>

                </div>
            </div>
            <br>
            <?php
            $add_ijin = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_ijin);
            echo "<span></span>";
            $cancel_ijin = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('perizinan') . '\''
            );
        echo form_button($cancel_ijin);
        echo form_close();
        ?>
    </div>
</div>
    <br style="clear: both;" />
</div>