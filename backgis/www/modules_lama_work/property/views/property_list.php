<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
     
        <div class="entry">
        <?php echo form_open('property/property/simulasi'); ?>

        <fieldset>
            <legend>Hitung Tarif Retribusi</legend>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Jenis Izin','luas_tanah');
                ?>
              </div>
              <div id="rightRail">

              <?php echo $jenis_izin;?>

              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Luas Tanah','luas_tanah');
                ?>
              </div>
              <div id="rightRail">
                    <?php echo $luas_tanah;?>
              </div>
            </div>

                <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Lokasi Tanah','luas_tanah');
                ?>
              </div>
              <div id="rightRail">
             <?php echo $lokasi_tanah;?>

              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Harga Retribusi','luas_tanah');
                ?>
              </div>
              <div id="rightRail">

               <?php echo $harga_retribusi;?>
              </div>
            </div>
           <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Kategori','luas_tanah');
                ?>
              </div>
              <div id="rightRail">

              <?php echo $kategori;?>


              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Index','luas_tanah');
                ?>
              </div>
              <div id="rightRail">
              <?php echo $index;?>

              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Total Tarif Retribusi','luas_tanah');
                ?>
              </div>
              <div id="rightRail">
              Rp.<?php echo $tot=$harga_retribusi*$index;?>,00

              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
                 <?php echo "<span></span>";
           $cancel_ijin = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('property/property') . '\''
            );
            echo form_button($cancel_ijin);
               ?>
              </div>
            </div>
        <? echo forn_close(); ?>
        </fieldset>
          </div>



    </div>
    <br style="clear: both;" />
</div>