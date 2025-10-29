<div id="content">
<?php
echo br(5);
      $attr = array('id' => 'form');
      echo form_open('wilayah/procedure/save', $attr);
      $add_daftar = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
      
      echo form_label('nama lengkap');
      echo form_input('nama');
      echo br(2);
      echo form_label('No telp');
      echo form_input('telp');
      echo br(2);
      echo form_label('Alamat');
      echo form_input('alamat');
      echo br(2);

      echo form_submit($add_daftar);
echo br(15);
?>
</div>