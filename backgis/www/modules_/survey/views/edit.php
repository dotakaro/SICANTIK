<style type="text/css">
    label[class*="ui-corner-all"]{
        width:auto;
    }
</style>
<script>
function validasi()
{
    var survey = document.forms[0].survey.value;
     var berkas = document.getElementById('berkas').value;
     var survey2 = document.getElementById('survey').value;
    var petugas = document.forms[0].petugas.value;
    var pemeriksa = document.forms[0].listizin.value;
    //var unit_kerja_pemeriksa = document.forms[0].list_unit_kerja.value;


    if (survey=='0000-00-00')
   {
    document.getElementById('erorSurvey').innerHTML = 'Field ini wajib diisi';
	document.getElementById('erorSurvey').style.visibility = "visible"; 
    document.getElementById('erorSurvey').style.color = "#FF2F2F"; 
    return false;
    }
    //chairina
      else if(survey2<berkas && survey2!=='0000-00-00')
     {
         document.getElementById('erorSurvey').innerHTML = ' * Tanggal peninjauan tidak boleh lebih kecil dari tgl berkas';
         document.getElementById('erorSurvey').style.visibility = "visible"; 
         document.getElementById('erorSurvey').style.color = "#FF2F2F"; 
         return false;
     }

    else if (petugas=="")
    {
        document.getElementById('erorPetugas').innerHTML = 'Field ini wajib diisi';
    	document.getElementById('erorPetugas').style.visibility = "visible"; 
        document.getElementById('erorPetugas').style.color = "#FF2F2F"; 
        return false;
    }
    else if (pemeriksa=="")
    {
        document.getElementById('erorPeriksa').innerHTML = 'Field ini wajib diisi';
        document.getElementById('erorPeriksa').style.visibility = "visible";
        document.getElementById('erorPeriksa').style.color = "#FF2F2F";
        return false;
    }

    else if (unit_kerja_pemeriksa=="")
    {
        document.getElementById('erorUnitKerja').innerHTML = 'Field ini wajib diisi';
    	document.getElementById('erorUnitKerja').style.visibility = "visible"; 
        document.getElementById('erorUnitKerja').style.color = "#FF2F2F"; 
        return false;
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
            $attr = array('name' => 'form', 'id' => 'form','onsubmit' => 'return validasi()'); 
            echo form_open('survey/survey/' . $save_method,$attr);
            echo form_hidden('id_permohonan', $id);
            echo form_hidden('id_survey', $id_survey);
            
            ?>
            <label class="label-wrc">ID Pendaftaran</label>
            <?php
                echo $pendaftaran_id;
            ?><br style="clear: both;" />
            <label class="label-wrc">Nama</label>
            <?php
                echo $nama_pendaftar;
            ?><br style="clear: both;" />
            <label class="label-wrc">Nama Perizinan</label>
            <?php
                echo $nama_perizinan;
            ?>
            <table>
             <tr>
            <td><label class="label-wrc">Tanggal Terima Berkas</label> </td>
            <td>
            <?php
             $berkas_input = array(
                'name' => 'berkas',
                'value' => $berkas,
                'id' => 'berkas'
            );
            
            echo form_input($berkas_input);
            ?>
            </td>
            </tr>
            <tr>
            <td><label class="label-wrc">Tanggal Peninjauan</label> </td>
            <td>
            <?php
            $survey_input = array(
                'name' => 'survey',
                'value' => $survey,
                'readOnly'=>TRUE,
                'class' => 'input-wrc required',
                'id' => 'survey'
            );
        
            
            echo form_input($survey_input);
            ?>
            <span id="erorSurvey" style=" clear: both; visibility: hidden;"></span>
            </td>
            </tr>
            <tr>
            <td><label class="label-wrc">No Surat</label></td>
            <td>
            <?php
            $no_surat_input = array(
                'name' => 'no_surat',
                'value' => $no_surat,
                'class' => 'input-wrc',
                'readOnly'=>TRUE
            );
            echo form_input($no_surat_input);
            ?></td>
            </tr>
            <br style="clear: both;" />
            <tr>
            <td><label class="label-wrc">Penanda tangan Surat</label></td>
            <td>

            <select name="petugas[]" id="petugas" class="input-wrc required" style="width:300px;" multiple="multiple">
                <?php
                    $selected = NULL;
                    foreach ($petugas as $petugas_data) {
//                        if ($petugas_data->id === $petugas_id) {
                        if (in_array($petugas_data->id, $listSelectedPenandatangan)) {
                            $selected = ' selected="selected" ';
                        } else {
                            $selected = NULL;
                        }
                                
                        echo "<option value=\"" . $petugas_data->id . "\"" . $selected . ">"
                            . $petugas_data->n_pegawai . " | "
                            . $petugas_data->nip . "</option>\n" ;
                    }
                ?>
            </select>
            <?php
            /*echo form_dropdown(
                    'jenis_investasi[]',
                    $opsi_investasi,
                    $jenis_investasi,
                    'id="jenis_investasi" class = "input-select-wrc notSelect" style="width:300px" multiple="multiple"'
            );*/
            ?>
            <span id="erorPetugas" style=" clear: both; visibility: hidden;"></span>
            </td>
            </tr>
            <br style="clear: both;" />
            <tr>
            <td><label class="label-wrc">Unit Kerja</label></td>
            <td>        
            <select id="list_unit_kerja" name="list_unit_kerja[]" multiple="multiple">
               <?php
                if($save_method === "update") {
                    if(count($list_unit_kerja)>0):
                        foreach ($list_unit_kerja as $data) {
                            $selected = '';    
                            if(count($list_scheduled_unit)>0):
                                foreach($list_scheduled_unit as $scheduled_unit){
                                    if($data->id == $scheduled_unit->trunitkerja_id){
                                        $selected = 'selected';break;
                                    }
                                }
                            endif;
                            echo '<option value="'.$data->id.'" '.$selected.'>'.$data->n_unitkerja.'</option>';
                        }
                    endif;
                }else{
                    if(count($list_unit_kerja)>0):
                        foreach ($list_unit_kerja as $data) {
                            echo '<option value="'.$data->id.'">'.$data->n_unitkerja.'</option>';
                        }
                    endif;
                }
                ?>
            </select>
            <span id="erorUnitKerja" style=" clear: both; visibility: hidden;"></span>
            </td>
            </tr>

            <br style="clear: both;" />
            <tr>
                <td><label class="label-wrc">Tim Pemeriksa</label></td>
                <td>
                    <select id="listizin" name="listizin[]" multiple="multiple">
                        <?php
                        if($save_method === "update") {
                            foreach ($list as $data) {
                                foreach ($idp as $data_p)
                                {
                                    if ($data_p->id == $data->id) {
                                        $selected= 'selected'; break;

                                    } else{
                                        $selected= 'testing';
                                    }
                                }

                                if ($selected=="selected")
                                {
                                    $data->trunitkerja->get();
                                    echo "<option value='".$data->id."'" . $selected . ">".$data->n_pegawai." | ".$data->nip." | ".$data->trunitkerja->n_unitkerja."</option>";
                                }
                                else
                                {
                                    $data->trunitkerja->get();
                                    echo "<option value='".$data->id."'>".$data->n_pegawai." | ".$data->nip." | ".$data->trunitkerja->n_unitkerja."</option>";

                                }
                            }
                        }else {
                            foreach ($list as $data) {
                                echo "<option value='".$data->id."'>".$data->n_pegawai." | ".$data->nip." | ".$data->trunitkerja->n_unitkerja."</option>";
                            }
                        }?>
                    </select>
                    <span id="erorPeriksa" style=" clear: both; visibility: hidden;"></span>
                </td>
            </tr>

            </table>
            <br style="clear: both;" />
            <label>&nbsp;</label>
            <div class="spacer"></div>
            
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
                'onclick' => 'parent.location=\''. site_url('survey/survey') . '\''
            );
            echo form_button($cancel_role);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
