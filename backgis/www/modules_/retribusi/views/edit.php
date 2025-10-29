<script type="text/javascript">
function myfunction(form)
{
var val=form.manual.options[form.manual.options.selectedIndex].value;
    if(val=='0')
    {
        document.form1.v_retribusi.disabled=false
        document.form1.v_retribusi.value="<?php echo $v_retribusi; ?>"
        
    }
    else
    {
       document.form1.v_retribusi.disabled=true
       document.form1.v_retribusi.value="0"
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
                    <li><a href="#tabs-1">Data Retribusi</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <?php
                        
                        $attr = array('id' => 'form','name'=>'form1');
                        echo form_open('retribusi/' . $save_method, $attr);
                        echo form_hidden('id', $id);
                        echo "<center>".$warning."</center>"; 
                        ?>
                        <div class="contentForm">
                        <label class="label-wrc">Nama Izin</label>
                        <?php if($save_method=='save'){ ?>
                            
                             <?php
                                $selected = NULL;
                                foreach ($izin as $izin_data) {
                                    $opsi_izin[$izin_data->id] = $izin_data->n_perizinan;
                                    //if ($izin_data->id === $perizinan_id) {
//                                        $selected = ' selected="selected" ';
//                                    } else {
//                                        $selected = NULL;
//                                    }
//
  //                                  echo "<option value=\"" . $izin_data->id . "\"" . $selected . ">"
//                                        . $izin_data->n_perizinan . "</option>\n" ;
                                    }
                               echo form_dropdown('perizinan_id', $opsi_izin, $perizinan_id, 'class = "input-select-wrc notSelect"');

                            ?>
                        </select -->
                        <?php } else { 
                            foreach ($izin as $izin_data) {
                                    $opsi_izin[$izin_data->id] = $izin_data->n_perizinan;   
                                }
                                echo form_dropdown('terserah',$opsi_izin,$perizinan_id, 'class = "input-select-wrc notSelect" disabled');
                                echo form_hidden('perizinan_id',$perizinan_id);
                            } ?>
                            
                        </div>
                        <?php 
                        $terpilih = '';
                        if ($metode == '1')
                        {
                            $terpilih = ' selected="selected" ';
                        } 
                        ?>
                         <div class="contentForm">
                        <label class="label-wrc">Hitung Manual</label>
                      <select name="manual" onChange="myfunction(this.form)"
                              class = "input-select-wrc">
				<option value="0" <?php echo $terpilih; ?> >Tidak</option>
				<option value="1" <?php echo $terpilih; ?> >Ya</option>
				
			</select>
                        </div>
                        <div class="contentForm">
                        <label class="label-wrc">Nilai Retribusi Otomatis</label>
                        <?php
                        $v_retribusi_input = array(
                                'name' => 'v_retribusi',
                                'value' => $v_retribusi,
                                'class' => 'input-wrc digits'
                                );
                           
                             echo form_input($v_retribusi_input);
                        ?>
                        </div>
                        <div class="contentForm">
                        <label class="label-wrc">Biaya Formulir</label>
                        <?php
                            $v_denda_input = array(
                                'name' => 'v_denda',
                                'value' => $v_denda,
                                'class' => 'input-wrc digits'
                            );
                            echo form_input($v_denda_input);
                        ?>
                        </div>
                        <div class="contentForm">
                        <label class="label-wrc">Kode Akun</label>
                        <?php
                            $c_account_input = array(
                                'name' => 'c_account',
                                'value' => $c_account,
                                'class' => 'input-wrc required'
                            );
                            echo form_input($c_account_input);
                        ?>
                        </div>
                        <div class="contentForm">
                        <label class="label-wrc">Tanggal SK Berlaku</label>
                        <?php
                            $d_sk_terbit_input = array(
                                'name' => 'd_sk_terbit',
                                'id' => 'd_sk_terbit',
                                'value' => $d_sk_terbit,
                                'readOnly'=>TRUE,
                                'class' => 'input-wrc required date'
                            );
                            echo form_input($d_sk_terbit_input);
                        ?>
                        </div>
                        <div class="contentForm">
                        <label class="label-wrc">Tanggal SK Berakhir</label>
                        <?php
                            $d_sk_berakhir_input = array(
                                'name' => 'd_sk_berakhir',
                                'id' => 'd_sk_berakhir',
                                'value' => $d_sk_berakhir,
                                'readOnly'=>TRUE,
                                'class' => 'input-wrc required date'
                            );
                            echo form_input($d_sk_berakhir_input);
                        ?>
                        </div>
                    </div>
                    
                    <br style="clear: both;" />
                </div>
            </div>
        <br>
         <?php
            $add_role = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_role);
            echo "<span></span>";
            $cancel_role = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('retribusi') . '\''
            );
            echo form_button($cancel_role);
            echo form_close();
            ?>
          </div>
    

<!--        <div class="entry">
            
            <br style='clear:both' />
            <br style='clear:both' />           
            <br style='clear:both' />            
            <br style='clear:both' />           
            <br style='clear:both' />
            <br style='clear:both' />

            <label>&nbsp;</label>
            <div class="spacer"></div>

            
        </div>-->
    </div>
    <br style="clear: both;" />
</div>
