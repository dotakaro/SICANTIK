<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('setting_formula/'. $save_method, $attr);
        ?>
        <div class="entry">
            <?php
            $item_id = array(
                'name' => 'id',
                'id'=>'setting_formula_retribusi_id',
                'type'=>'hidden',
                'value'=>$id
            );
            echo form_input($item_id);

            echo "<label class='label-wrc'>Jenis Izin</label>";
            $trperizinan_id = array(
                'name' => 'trperizinan_id',
                'id'=>'trperizinan_id',
                'type'=>'hidden',
                'value'=>$trperizinan_id
            );
            echo form_input($trperizinan_id);
            echo $perizinan->n_perizinan;
            echo "<br style='clear:both' />";

            echo "<label class='label-wrc'>Dihitung oleh</label>";
            echo "<br/><ul id='list_unit'>";
            foreach($setting_formula_detail as $key=>$detail){
                echo "<li>";
                $hiddenDetailId = array(
                    'name' => 'SettingFormulaDetail['.$key.'][id]',
                    'id'=>'SettingFormulaDetail'.$key,
                    'type'=>'hidden',
                    'value'=>$detail['id'],
                    'class'=>'setting-formula-detail'
                );
                echo form_input($hiddenDetailId);
//                echo form_hidden("SettingFormulaDetail[$key][id]", $detail['id']);
                echo form_dropdown("SettingFormulaDetail[$key][trunitkerja_id]", $list_unit_kerja, $detail['trunitkerja_id'],' class="input-select-wrc unit-kerja" style="width:400px;"');
                if($key != 0){
                    echo '<button class="btn-delete" type="button">Hapus</button>';
                }
                echo "</li>";
            }
            echo "</ul>";
            echo "<label class='label-wrc'>&nbsp;</label>";
            echo "<button id='btn_add_unit' class='button-wrc' type='button'>Tambah</button>";
            echo "<br style='clear:both' />";

            echo "<label class='label-wrc'>Item Retribusi</label><br/>";
            if(count($setting_tarif_item)>0){
                echo "<ul style='margin-left:220px;margin-top:-15px;'>";
                foreach($setting_tarif_item as $tarif_item){
                    echo "<li>".$tarif_item->nama_item."</li>";
                }
                echo "</ul>";
            }else{
                echo 'No Item';
                echo "<br style='clear:both' />";
            }

            echo "<label class='label-wrc'>Formula</label>";
            $formula_input = array(
                'name' => 'formula',
                'id'=>'formula',
                'cols'=>'50',
                'rows'=>'5',
                'style'=>'resize:no-resize;',
                'value'=>$formula
            );
            echo form_textarea($formula_input);
            echo "<br style='clear:both' />";

            $save_form = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($save_form);

            echo "<span></span>";
            $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('setting_formula') . '\''
            );
            echo form_button($cancel);
            ?>
        </div>
        <?php echo form_close();?>
    </div>
    <br style="clear: both;" />
</div>
