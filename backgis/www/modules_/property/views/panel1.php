<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            echo form_open('property/property/hitung');
            echo form_hidden('retribusi_terhitung', $retribusi);
            echo form_hidden('id', $id);
            echo form_hidden('jenis_izin',$jenis_izin);
            echo $id;
           
            ?>
             &nbsp;&nbsp;
            <label class="label-wrc">Kategori  <?php echo $jenis_izin;?></label>
            <?php echo $namaproperty;?>
            <br />

            <label class="label-wrc"></label>
            <?php


                   foreach ($list_kategori as $row){

                        $opsi_kategori[$row->kategori] = $row->kategori;

                   }

                    echo form_dropdown('kategori', $opsi_kategori, '','class = "input-select-wrc"');

                    ?>
            <br />

          
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
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
