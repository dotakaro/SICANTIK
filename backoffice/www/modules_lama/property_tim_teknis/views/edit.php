<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('property_tim_teknis/'. $save_method, $attr);
        ?>
        <div class="entry">
            <?php
            $item_id = array(
                'name' => 'id',
                'id'=>'property_teknis_header',
                'type'=>'hidden',
                'value'=>$id
            );
            echo form_input($item_id);

            echo "<label class='label-wrc'>Jenis Izin</label>";
            //echo form_dropdown('trperizinan_id', $option_izin,set_value('trperizinan_id',$trperizinan_id),'id="trperizinan_id" class="input-select-wrc required-option"');
            $trperizinan_id = array(
                'name' => 'trperizinan_id',
                'id'=>'trperizinan_id',
                'type'=>'hidden',
                'value'=>$perizinan->id
            );
            echo form_input($trperizinan_id);
            echo $perizinan->n_perizinan;
            echo "<br style='clear:both' />";

            echo "<label class='label-wrc'>Unit Kerja</label>";
            $hiddenUnitKerja = array(
                'name' => 'trunitkerja_id',
                'id'=>'trunitkerja_id',
                'type'=>'hidden',
                'value'=>$unit_kerja->id
            );
            echo form_input($hiddenUnitKerja);
            echo $unit_kerja->n_unitkerja;
            echo "<br style='clear:both' />";

            ?>

            <table id="tbl_group" class="display" cellspacing="0" cellpadding="0" border="1">
                <thead>
                <tr>
                    <th width="200px">Group Property</th>
                    <th width="400px">Unit Kerja</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($list_parent_property as $key=>$property){
                    if(array_key_exists($property->id,$existing_detail)){//Jika sudah ada data sebelumnya
                        $trunitkerja_id = $existing_detail[$property->id]['trunitkerja_id'];
                ?>
                    <tr>
                        <td>
                            <?php
                            //Primary key dari Property Teknis Detail
                            $detail_id = array(
                            'name' => 'PropertyTeknisDetail['.$key.'][id]',
                            'type'=>'hidden',
                            'value'=>$existing_detail[$property->id]['id']
                            );
                            echo form_input($detail_id);

                            echo $property->n_property;
                            $trproperty_id = array(
                                'name' => 'PropertyTeknisDetail['.$key.'][trproperty_id]',
                                'type'=>'hidden',
                                'value'=>$property->id
                            );
                            echo form_input($trproperty_id);
                            ?>
                        </td>
                        <td>
                            <?php echo form_dropdown('PropertyTeknisDetail['.$key.'][trunitkerja_id]', $list_unit_kerja,set_value('trunitkerja_id',$trunitkerja_id),' class="input-select-wrc unit-kerja"');?>
                        </td>
                    </tr>
                <?php
                    }else{
                ?>
                    <tr>
                        <td>
                            <?php echo $property->n_property;
                            $trproperty_id = array(
                                'name' => 'PropertyTeknisDetail['.$key.'][trproperty_id]',
                                'type'=>'hidden',
                                'value'=>$property->id
                            );
                            echo form_input($trproperty_id);
                            ?>
                        </td>
                        <td>
                            <?php echo form_dropdown('PropertyTeknisDetail['.$key.'][trunitkerja_id]', $list_unit_kerja,null,' class="input-select-wrc unit-kerja"');?>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <?php
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
                'onclick' => 'parent.location=\''. site_url('property_tim_teknis') . '\''
            );
            echo form_button($cancel);
            ?>
        </div>
        <?php echo form_close();?>
    </div>
    <br style="clear: both;" />
</div>
