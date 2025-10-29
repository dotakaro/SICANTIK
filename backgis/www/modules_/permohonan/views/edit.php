<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php $page_name; ?></h2>
        </div>
        <div class="entry">
      
            <?php
            echo form_open('perizinan/' . $save_method);
            echo form_hidden('id', $id);
            ?>
            <label>Nama Pemilik  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; : &nbsp; &nbsp;</label>

             <?php
            $namapemilik_input = array(
                'name' => 'namapemilik',
                'value' => $namapemilik,
                'class' => 'input-wrc'
            );
            echo form_input($namapemilik_input);
            ?>

            <br>
           
            <label>Alamat Pemilik   &nbsp; &nbsp; &nbsp; &nbsp; : &nbsp; &nbsp;</label>
                  <?php
            $alamatpemilik_input = array(
                'name' => 'alamatpemilik',
                'value' => $alamatpemilik,
                'class' => 'input-wrc'
            );
            echo form_input($alamatpemilik_input);
            ?>
            <br>
               
            <label>Luas Tanah  &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : &nbsp;  &nbsp;</label>
           
             <?php
            $luastanah_input = array(
                'name' => 'real_name',
                'value' => $luastanah,
                'class' => 'input-wrc'
            );
            echo form_input($luastanah_input);
            ?>
            <br>
               
            <label>Lokasi Tanah  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; : &nbsp;  &nbsp;</label>
           
             <?php
            $lokasitanah_input = array(
                'name' => 'real_name',
                'value' => $lokasitanah,
                'class' => 'input-wrc'
            );
            echo form_input($lokasitanah_input);
            ?>
            <br>
              
            <label>Luas Bangunan   &nbsp; &nbsp; &nbsp; &nbsp; : &nbsp;  &nbsp;</label>
            
              <?php
            $luasbangunan_input = array(
                'name' => 'real_name',
                'value' => $luasbangunan,
                'class' => 'input-wrc'
            );
            echo form_input($luasbangunan_input);
            ?>
            <br>
              
            <label>Fungsi Bangunan   &nbsp; &nbsp; &nbsp; : &nbsp;  &nbsp;</label>
           
              <?php
            $fungsibangunan_input = array(
                'name' => 'real_name',
                'value' => $fungsibangunan,
                'class' => 'input-wrc'
            );
            echo form_input($fungsibangunan_input);
            ?>
            <br>
              
            <label>Struktur Bangunan  &nbsp;&nbsp; : &nbsp;  &nbsp;</label>
         
              <?php
            $strukturbangunan_input = array(
                'name' => 'real_name',
                'value' => $strukturbangunan,
                'class' => 'input-wrc'
            );
            echo form_input($strukturbangunan_input);
            ?>
            <br />

            <label>&nbsp;</label>
            <div class="spacer"></div>

            <?php
            echo form_submit('submit', 'Save');
            echo form_reset('reset', 'Reset');
            echo form_close();
            ?>
        
        </div>
    </div>
    <br style="clear: both;" />
</div>
