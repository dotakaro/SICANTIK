<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php $page_name; ?></h2>
        </div>
        <div class="entry">

            <?php
            //echo form_open('permohonan/update');
            echo form_hidden('id', $id);
            ?>
            <label>No Pendaftaran  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;</label>

             <?php echo $nopendaftaran;?>
             <?php echo form_hidden('nopendaftaran', $nopendaftaran);?>

            <br><br><br>

            <label>Jenis Layanan   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label>
                  <?php echo $jenislayanan;

            ?>
            <br><br>

            <label>Nama Pemohon  &nbsp; &nbsp;&nbsp; &nbsp;   &nbsp;  &nbsp;</label>

             <?php echo $namapemohon; ?>
            <br><br><br>

            <label>Alamat Pemohon  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp;  &nbsp;</label>

             <?php echo $alamatpemohon;
            ?>
            <br><br><br>


            <br /><br>
               <label>Property : &nbsp;&nbsp;  &nbsp;  &nbsp;</label>

              <?php
          echo $v_property;
            ?>
            <br />
            <label>&nbsp;</label>
            <div class="spacer"></div>

          
        </div>
    </div>
    <br style="clear: both;" />
</div>
