<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <script>
            function cekNilai()
            {
                var nilai = document.form.online[0].checked;
                var nilai2 = document.form.online2[0].checked;
                if( nilai == true )
                    {
                            
                            if(document.getElementById('service').value=='')
                                {
                                   document.getElementById('service').focus();
                                   alert('Isi Alamat web service Pajak');
                                   return false;
                                }
                    }
                if( nilai2 == true )
                    {
                            if(document.getElementById('penduduk').value=='')
                                {
                                    document.getElementById('penduduk').focus();
                                       alert('Isi Alamat web service Penduduk');
                                   return false;
                                }
                    }
                return true;
            }
        </script>
        <div class="entry">
            <?php
            $cek = "checked";
            $attr = array('id' => 'form','name'=>'form','onSubmit'=>'return cekNilai();');
            echo form_open('settings/webservice/' . $save_method, $attr);
             $service_input = array(
                'name' => 'service',
                'value' => $service,
                'class' => 'input-wrc required',
                'id' => 'service'
            );
              $service_input2 = array(
                'name' => 'penduduk',
                'value' => $service2,
                'class' => 'input-wrc required',
                'id' => 'penduduk'
            );
              
               $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . site_url('') . '\''
            );
            ?>
            
            <table >
                <tr>
                  <td><label class="label-wrc">Web Service Pajak</label></td>
                  <td><?php echo form_input($service_input); ?></td>
                  <td width="120"></td>

                  <td><label class="label-wrc">Web Service Kependudukan</label></td>
                  <td><?php echo form_input($service_input2); ?></td>
                </tr>
            </table>

                <table>
                    <tr>
                    <td> <label  class="label-wrc">Status :</label> </td>
                    <td><input type="radio" name="online" id="online" value="1" <?php if($status=="1") echo "$cek"; ?> />
                Online</td>
                    <td><input type="radio" name="online" id="online" value="0" <?php if($status=="0") echo "$cek"; ?>  />
                Offline</td>
                <td width="200"></td>

                     <td> <label  class="label-wrc">Status :</label> </td>
                    <td><input type="radio" name="online2"  id="online2" value="1" <?php if($status2=="1") echo "$cek"; ?> />
                Online</td>
                    <td><input type="radio" name="online2"  id="online2" value="0" <?php if($status2=="0") echo "$cek"; ?>  />
                Offline</td>
                    
                    </tr>
                 </table>

            <br><br>
              <INPUT TYPE="submit" name="submit" class="submit-wrc" value="Simpan" > 

           <?php echo form_button($cancel); echo form_close(); ?>
              
              

        </div>

    </div>
    <br style="clear: both;" />
</div>