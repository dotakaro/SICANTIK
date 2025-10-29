<script>
    //edited by mucktar
    function cekstatuscheckbox(frmchk)
    {
        var chk=document.getElementById("chk"+frmchk);
        if(chk.checked == true)
        {
                document.getElementById("subchk1"+frmchk).disabled="";   
                document.getElementById("subchk2"+frmchk).disabled="";   
                document.getElementById("subchk3"+frmchk).disabled="";
                document.getElementById("subchk4"+frmchk).disabled="";
                document.getElementById("subcmb"+frmchk).disabled="";
                document.getElementById("inputtxt1"+frmchk).disabled="";
                document.getElementById("input_parent"+frmchk).disabled="";
                document.getElementById("satuan"+frmchk).disabled="";
        }
        else
        {
                document.getElementById("subchk1"+frmchk).disabled="disabled";   
                document.getElementById("subchk2"+frmchk).disabled="disabled";   
                document.getElementById("subchk3"+frmchk).disabled="disabled";
                document.getElementById("subchk4"+frmchk).disabled="disabled";
                document.getElementById("subcmb"+frmchk).disabled="disabled";
                document.getElementById("inputtxt1"+frmchk).disabled="disabled";
                document.getElementById("subchk1"+frmchk).checked=false;   
                document.getElementById("subchk2"+frmchk).checked=false;   
                document.getElementById("subchk3"+frmchk).checked=false;
                document.getElementById("subchk4"+frmchk).checked=false;
                document.getElementById("subcmb"+frmchk).value="";
                document.getElementById("inputtxt1"+frmchk).value="";
                document.getElementById("input_parent"+frmchk).disabled="disabled";
                document.getElementById("satuan"+frmchk).disabled="disabled";
        }
    }
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">

        <?php
            if ($method !== 'editing') {
        ?>

            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Tambah Property dari Database</a></li>
                    <li><a href="#tabs-2">Tambah Property Baru</a></li>
                </ul>

                <div id="tabs-1">
                    <ul id="data_list">
                    <?php
                              
                            echo form_open('property/master/savelist');
                            echo form_hidden('id_izin', $id_izin);
                            echo form_hidden('id_property', $id_property);
                    ?>
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="property">
                        <thead>
                            <tr>
                                <th>Nama Property</th>
                                <th>Urutan Property</th>
                                <th>Kategori Parent</th>
                                <th>Urutan Parent</th>
                                <th>Tampil Kode Retribusi</th>
                                <th>Tampil di Surat Izin</th>
                                <th>Tampil di SKRD</th>
                                <th>Tampil di Tinj. Lapangan/BAP</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                                foreach ($property_list as $property) {
                                    /*edited 08-04-2013*/
                                    echo "<tr>";
                                    $set = array(
                                        'name' => 'property[]',
                                        'value' => $property->id,
                                        //'id' => 'prop-'.$property->id,
                                        "id"=>"chk".$i,
                                        "onclick"=>"cekstatuscheckbox('".$i."');"
                                    );
                                    echo "<td>" . form_checkbox($set) . " <b>".$property->n_property."</b>" . "</td>";
                                    
                                    $order_input = array(
                                        'name' => 'c_order_new-'.$property->id,
                                        'value' => $c_order,
                                        'class' => 'small-wrc', //edited 08-04-2013
                                        //'id' => 'c_order-'.$property->id
                                        "disabled"=>"disabled",
                                        "id"=>"inputtxt1".$i
                                    );

                                    echo "<td class='small-input'>" . form_input($order_input) . "</td>";
                                    
                                    echo "<td><select name='c_parent-".$property->id."' class='small-input-select-wrc'  disabled='disabled' id='subcmb".$i."' >";
                                            $parent_prop = new trproperty();
                                            $list_parent_prop = $parent_prop->where('c_type',2)->order_by('n_property', 'asc')->get();
                                    echo "<option value='' selected='selected' >----Pilih salah satu----</option>";
                                            foreach ($list_parent_prop as $all) {
                                                echo "<option value=\"" . $all->id . "\">". $all->n_property . "</option>\n" ;
                                            }
                                    echo "</select></td>";
                                    
                                    
                                    $tipe = array(
                                        'name'=>'parent-'.$property->id,
                                        'value'=>'',
                                        'class' => 'small-wrc',
                                        "disabled"=>"disabled",
                                        'id'=> 'input_parent'.$i
                                    );
                                    
                                    echo "<td class='small-input'>". form_input($tipe) . "</td>";
                                    
                                    $is_has_retribution = array(
                                        'name' => 'retribution[]',
                                        'value' => $property->id,
                                        //'id' => 'ret-'.$property->id
                                        "disabled"=>"disabled",
                                        "id"=>"subchk1".$i
                                    );
                                    echo "<td>" . form_checkbox($is_has_retribution) . "</td>";

                                    $is_showed_in_sk = array(
                                        'name' => 'sk_status[]',
                                        'value' => $property->id,
                                        //'id' => 'ck-'.$property->id
                                        "disabled"=>"disabled",
                                        "id"=>"subchk2".$i
                                    );
                                    echo "<td>" . form_checkbox($is_showed_in_sk) . "</td>";
                                    
                                    
                                    $is_showed_in_skrd = array(
                                        'name' => 'skrd_status[]',
                                        'value' => $property->id,
                                        //'id' => 'ck-'.$property->id
                                        "disabled"=>"disabled",
                                        "id"=>"subchk4".$i
                                    );
                                    echo "<td>" . form_checkbox($is_showed_in_skrd) . "</td>";
                                    
                                    $is_showed_in_tl = array(
                                        'name' => 'tl_status[]',
                                        'value' => $property->id,
                                        //'id' => 'tl-'.$property->id
                                        "disabled"=>"disabled",
                                        "id"=>"subchk3".$i
                                    );
                                    echo "<td>" . form_checkbox($is_showed_in_tl) . "</td>";                                   

                                    foreach ($satuan as $row_satuan){
                                        $arr_satuan[$row_satuan] = $row_satuan;
                                    } 
                                    
                                    echo "<td class='small-input'>". form_dropdown('c_satuan-'.$property->id, $arr_satuan,'','class= "small-input-select-wrc" id="satuan'.$i.'" disabled="disabled"') . "</td>";
                                    
                                    /*end*/
                                    echo "</tr>";
                                    $i++;
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                            $add_property_list = array(
                                'name' => 'submit',
                                'class' => 'submit-wrc',
                                'content' => 'Simpan',
                                'type' => 'submit',
                                'value' => 'Simpan'
                            );
                            echo form_submit($add_property_list);
                            echo "<span></span>";
                            $cancel_list = array(
                                'name' => 'button',
                                'class' => 'button-wrc',
                                'content' => 'Batal',
                                'onclick' => 'parent.location=\''. site_url('property/master/detail') . "/" . $id_izin . '\''
                            );
                            echo form_button($cancel_list);
                            echo form_close();
                    ?>
                    </ul>
                </div>
                <div id="tabs-2">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('property/master/save',$attr);
                    echo form_hidden('id_izin', $id_izin);
                    echo form_hidden('id_property', $id_property);
                    ?>
                <fieldset>
                    <div id="statusMain">
                        <div id="leftMain">
                            <label class="label-wrc">Nama Property</label>
                        </div>
                        <div id="rightMain">
                            <?php
                                $user_name_input = array(
                                    'name' => 'n_property',
                                    'value' => $n_property,
                                    'class' => 'input-wrc required',
                                    'id' => 'property_name',                                
                                    'style' => 'min-width:400pt'
                                );
                                echo form_input($user_name_input);
                            ?>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain" class="bg-grid" style="min-height: 27pt;">
                            <label class="label-wrc">Kategori</label>
                        </div>
                        <div id="rightMain" class="bg-grid">
                            <select name="c_parent" class="input-select-wrc required">
                                <option value="" selected="selected">---------Pilih salah satu---------</option>
                                <?php
                                    $selected = NULL;
                                    foreach ($property_list2 as $property_data) {
                                        //edited 08-04-2013
                                        /*
                                        if (strval($property_data->id) === strval($c_parent)) {
                                            $selected = ' selected="selected" ';
                                        } else {
                                            $selected = null;
                                        }*/

                                        echo "<option value=\"" . $property_data->id . "\"" . $selected . ">"
                                            . $property_data->n_property . "</option>\n" ;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain">
                            <label class="label-wrc">Urutan Parent</label>
                        </div>
                        <div id="rightMain">
                            <?php
                                $parent_order_input = array(
                                    'name' => 'c_parent_order',
                                    'value' => $c_parent_order,
                                    'class' => 'input-wrc required digits',
                                    'id' => 'c_parent_order'
                                );
                                echo form_input($parent_order_input);
                            ?>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain" class="bg-grid" style="min-height: 22pt">
                            <label class="label-wrc">Urutan</label>
                        </div>
                        <div id="rightMain" class="bg-grid">
                            <?php
                                $order_input = array(
                                    'name' => 'c_order',
                                    'value' => $c_order,
                                    'class' => 'input-wrc required digits',
                                    'id' => 'c_order'
                                );
                                echo form_input($order_input);
                            ?>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain">
                            <label class="label-wrc">Kode Retribusi</label>
                        </div>
                        <div id="rightMain">
                            <?php
                                $data_0 = array(
                                    'name' => 'c_retribusi',
                                    'type' => 'radio',
                                    'checked'=>TRUE,
                                    'value'=> '0'
                                );
                                $data_1 = array(
                                    'name' => 'c_retribusi',
                                    'type' => 'radio',
                                    'value'=> '1'
                                );
                                echo form_checkbox($data_0);
                                echo "Tidak Ada ";
                                echo form_checkbox($data_1);
                                echo "Ada";
                            ?>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain" class="bg-grid">
                            <label class="label-wrc">Tampilkan di Surat Izin</label>
                        </div>
                        <div id="rightMain" class="bg-grid">
                            <?php
                                    $data_0a = array(
                                        'name' => 'c_sk_id',
                                        'type' => 'radio',
                                        'checked'=>TRUE,
                                        'value'=> '0'
                                    );
                                    $data_1a = array(
                                        'name' => 'c_sk_id',
                                        'type' => 'radio',
                                        'value'=> '1'
                                    );
                                    echo form_checkbox($data_0a);
                                    echo "Tidak Ada ";
                                    echo form_checkbox($data_1a);
                                    echo "Ada";
                            ?>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain">
                            <label class="label-wrc">Tampilkan di SKRD</label>
                        </div>
                        <div id="rightMain">
                            <?php

                                    $data_0ab = array(
                                        'name' => 'c_skrd_id',
                                        'type' => 'radio',
                                        'checked'=>TRUE,
                                        'value'=> '0'
                                    );
                                    $data_1ab = array(
                                        'name' => 'c_skrd_id',
                                        'type' => 'radio',
                                        'value'=> '1'
                                    );
                                    echo form_checkbox($data_0ab);
                                    echo "Tidak Ada ";
                                    echo form_checkbox($data_1ab);
                                    echo "Ada";
                            ?>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain" class="bg-grid">
                        <b>Tampilkan di Tinjauan Lapangan/BAP</b>
                        </div>
                        <div id="rightMain" class="bg-grid">
                            <?php

                                $data_0b = array(
                                    'name' => 'c_tl_id',
                                    'type' => 'radio',
                                    'checked'=>TRUE,
                                    'value' => '0'
                                );
                                $data_1b = array(
                                    'name' => 'c_tl_id',
                                    'type' => 'radio',
                                    'value' => '1'
                                );
                                echo form_checkbox($data_0b);
                                echo "Tidak Ada ";
                                echo form_checkbox($data_1b);
                                echo "Ada";
                            ?>

                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain">
                            <label class="label-wrc">Tipe Property</label>
                        </div>
                        <div id="rightMain">
                            <?php
                                $arr = array(
                                    //'3'=>'----------Pilih salah satu----------',
                                    '0' => 'TextBox',
                                    '4' =>'Tanggal',
                                    '1' => 'ComboBox'
//                                    '2' => 'Tanggal',
//                                    '3' => 'Boolean'
                                );

                                echo form_dropdown('c_type', $arr,'0', 'class="input-select-wrc"');
                            ?>
                        </div>
                    </div>
                    <div id="statusMain">
                        <div id="leftMain" class="bg-grid">
                            <label class="label-wrc">Satuan</label>
                        </div>
                        <div id="rightMain" class="bg-grid">
                            <select class="input-select-wrc" name="satuan">
                                <option value="" selected="selected">-----------Pilih salah satu-----------</option>
                                <?php
                                foreach ($satuan as $row) {
                                    echo "<option value=" . $row . ">" . $row . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- added 08-04-2013 -->
                    <div id="statusMain">
                    <div id="leftMain" >
                        <label class="label-wrc">Panjang Property</label>
                    </div>
                    <div id="rightMain">
                           <?php
                            $order_input = array(
                                'name' => 'property_length',
                                'value' => '',
                                'class' => 'input-wrc digits',
                            );
                            echo form_input($order_input);

                           ?>
                    </div>
                </div>
                    
                    
                    <div id="statusMain">
                        <div id="leftMain">
                        </div>
                        <div id="rightMain">
                            <?php
                                $add_property = array(
                                    'name' => 'submit',
                                    'class' => 'submit-wrc',
                                    'content' => 'Simpan',
                                    'type' => 'submit',
                                    'value' => 'Simpan'
                                );
                                echo form_submit($add_property);
                                echo "<span></span>";
                                $cancel = array(
                                    'name' => 'button',
                                    'class' => 'button-wrc',
                                    'content' => 'Batal',
                                    'onclick' => 'parent.location=\''. site_url('property/master/detail') . "/" . $id_izin . '\''
                                );
                                echo form_button($cancel);
                                echo form_close();
                            ?>
                        </div>
                    </div>

                </fieldset>
                </div>
            </div>
            <?php } else {
                $attr = array('id' => 'form');
                echo form_open('property/master/update',$attr);
                echo form_hidden('id_izin', $id_izin);
                echo form_hidden('id_property', $id_property);
                echo form_hidden('c_parent', $c_parent);
                ?>

            <fieldset>
                <div id="statusMain">
                    <div id="leftMain">
                        <label class="label-wrc">Nama Property</label>
                    </div>
                    <div id="rightMain">
                        <?php
                            // edited 11-04-2013
                            // by mucktar                  
                            if(!$disable_field){
                                $user_name_input = array(
                                    'name' => 'n_property',
                                    'value' => $n_property,
                                    'class' => 'input-wrc required',
                                    'id' => 'property_name',
                                    'style' => 'min-width:400pt'
                                );
                            }else{
                                $user_name_input = array(
                                    'name' => 'n_property',
                                    'value' => $n_property,
                                    'class' => 'input-wrc required',
                                    'id' => 'property_name',
                                    'style' => 'min-width:400pt',
                                    "readonly" => "true"
                                );
                            }
                            //end edit
                            echo form_input($user_name_input);
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid" style="min-height: 27pt;">
                        <label class="label-wrc">Kategori</label>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <select name="c_parent" class="input-select-wrc required">
                             <option value="" selected="selected">---------Pilih salah satu---------</option>
                              <?php
                                $selected = NULL;
                                foreach ($property_list2 as $property_data) {
                                    if (strval($property_data->id) === strval($c_parent)) {
                                        $selected = ' selected="selected" ';
                                    } else {
                                        $selected = NULL;
                                    }

                                    echo "<option value=\"" . $property_data->id . "\"" . $selected . ">"
                                        . $property_data->n_property . "</option>\n" ;
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <label class="label-wrc">Urutan Parent</label>
                    </div>
                    <div id="rightMain">
                        <?php
                            $parent_order_input = array(
                                'name' => 'c_parent_order',
                                'value' => $c_parent_order,
                                'class' => 'input-wrc required digits',
                                'id' => 'c_parent_order'
                            );
                            echo form_input($parent_order_input);
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid" style="min-height: 22pt">
                        <label class="label-wrc">Urutan</label>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php
                            $order_input = array(
                                'name' => 'c_order',
                                'value' => $c_order,
                                'class' => 'input-wrc required digits',
                                'id' => 'c_order'
                            );
                            echo form_input($order_input);
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <label class="label-wrc">Kode Retribusi</label>
                    </div>
                    <div id="rightMain">
                        <?php
                            $code_choise_0 = NULL;
                            $code_choise_1 = NULL;
                            if($ret_choise === "0" || $ret_choise === NULL) {
                                $code_choise_0 = 'checked';
                                $code_choise_1 = '';
                            } else {
                                $code_choise_1 = 'checked';
                                $code_choise_0 = '';
                            }

                            $data_0 = array(
                                'name' => 'c_retribusi',
                                'type' => 'radio',
                                'checked' => $code_choise_0,
                                'value'=> '0'
                            );
                            $data_1 = array(
                                'name' => 'c_retribusi',
                                'type' => 'radio',
                                'checked' => $code_choise_1,
                                'value'=> '1'
                            );
                            echo form_checkbox($data_0);
                            echo "Tidak Ada ";
                            echo form_checkbox($data_1);
                            echo "Ada";
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <label class="label-wrc">Tampilkan di Surat Izin</label>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php
                            $code_choise_0a = NULL;
                            $code_choise_1a = NULL;
                            if($c_sk_id === "0" || $c_sk_id === NULL) {
                                $code_choise_0a = 'checked';
                                $code_choise_1a = '';
                            } else {
                                $code_choise_1a = 'checked';
                                $code_choise_0a = '';
                            }

                                $data_0a = array(
                                    'name' => 'c_sk_id',
                                    'type' => 'radio',
                                    'checked' => $code_choise_0a,
                                    'value'=> '0'
                                );
                                $data_1a = array(
                                    'name' => 'c_sk_id',
                                    'type' => 'radio',
                                    'checked' => $code_choise_1a,
                                    'value'=> '1'
                                );
                                echo form_checkbox($data_0a);
                                echo "Tidak Ada ";
                                echo form_checkbox($data_1a);
                                echo "Ada";
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <label class="label-wrc">Tampilkan di SKRD</label>
                    </div>
                    <div id="rightMain">
                        <?php
                            $code_choise_0 = NULL;
                            $code_choise_1 = NULL;
                            if($c_skrd_id === "0" || $c_skrd_id === NULL) {
                                $code_choise_0 = 'checked';
                                $code_choise_1 = '';
                            } else {
                                $code_choise_1 = 'checked';
                                $code_choise_0 = '';
                            }

                                $data_0ab = array(
                                    'name' => 'c_skrd_id',
                                    'type' => 'radio',
                                    'checked' => $code_choise_0,
                                    'value'=> '0'
                                );
                                $data_1ab = array(
                                    'name' => 'c_skrd_id',
                                    'type' => 'radio',
                                    'checked' => $code_choise_1,
                                    'value'=> '1'
                                );
                                echo form_checkbox($data_0ab);
                                echo "Tidak Ada ";
                                echo form_checkbox($data_1ab);
                                echo "Ada";
                        ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                    <b>Tampilkan di Tinjauan Lapangan/BAP</b>
                    </div>
                    <div id="rightMain" class="bg-grid">
                        <?php
                            $code_choise_0 = NULL;
                            $code_choise_1 = NULL;
                            if ($c_tl_id === "0" || $c_tl_id === NULL) {
                                $code_choise_0 = 'checked';
                                $code_choise_1 = '';
                            } else {
                                $code_choise_1 = 'checked';
                                $code_choise_0 = '';
                            }

                            $data_0b = array(
                                'name' => 'c_tl_id',
                                'type' => 'radio',
                                'checked' => $code_choise_0,
                                'value' => '0'
                            );
                            $data_1b = array(
                                'name' => 'c_tl_id',
                                'type' => 'radio',
                                'checked' => $code_choise_1,
                                'value' => '1'
                            );
                            echo form_checkbox($data_0b);
                            echo "Tidak Ada ";
                            echo form_checkbox($data_1b);
                            echo "Ada";
                        ?>

                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain">
                        <label class="label-wrc">Tipe Property</label>
                    </div>
                    <div id="rightMain">
                        <?php
                            $arr = array(
                                    '3'=>'----------Pilih salah satu----------',
                                    '0' => 'TextBox',
                                    '1' => 'ComboBox',
                                    '4' => 'Tanggal'
//    '2' => 'Tanggal',
//    '3' => 'Boolean'
                            );
                  if($c_type == 1 | $c_type == 0 | $c_type == 4 )
                  {
                      echo form_dropdown('c_type', $arr, $c_type,'class="input-select-wrc"');
                  }
                  else
                  {
                            echo form_dropdown('c_type', $arr,'3','class="input-select-wrc"');
                  }
                       ?>
                    </div>
                </div>
                <div id="statusMain">
                    <div id="leftMain" class="bg-grid">
                        <label class="label-wrc">Satuan</label>
                    </div>
                    <div id="rightMain" class="bg-grid">
                            <select class="input-select-wrc" name="satuan">
                                <option value="" selected="selected">-----------Pilih salah satu-----------</option>
                                <?php
                        $sel = null;
                            foreach ($satuan as $row){
                                if($row === $satuan_key){
                                    $sel ="selected='selected'";
                                }else{
                                    $sel = " ";
                                }
                                echo "<option value='".$row."'".$sel.">".$row."</option>";
                            }
                                ?>
                            </select>
                    </div>
                </div>

                <div id="statusMain">
                    <div id="leftMain" >
                        <label class="label-wrc">Panjang Property</label>
                    </div>
                    <div id="rightMain">
                           <?php
                            $order_input = array(
                                'name' => 'length',
                                'value' => $length,
                                'class' => 'input-wrc required digits',
                                
                            );
                            echo form_input($order_input);

                           ?>
                    </div>
                </div>


                <div id="statusMain">
                    <div id="leftMain">
                    </div>
                    <div id="rightMain">
                        <?php
                            $add_property = array(
                                'name' => 'submit',
                                'class' => 'submit-wrc',
                                'content' => 'Simpan',
                                'type' => 'submit',
                                'value' => 'Simpan'
                            );
                            echo form_submit($add_property);
                            echo "<span></span>";
                            $cancel = array(
                                'name' => 'button',
                                'class' => 'button-wrc',
                                'content' => 'Batal',
                                'onclick' => 'parent.location=\''. site_url('property/master/detail') . "/" . $id_izin . '\''
                            );
                            echo form_button($cancel);
                            echo form_close();
                        }
                        ?>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <br style="clear: both;" />
</div>
