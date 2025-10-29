<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>


       
       <div class="entry">
            <?php
            $attr = array('id' => 'form');
                        echo form_open('settings/satuan/' . $save_method, $attr);
                        echo form_hidden('id', $id);
                        ?>


             <div id="statusRail">
              <div id="leftRail">
             <label class="label-wrc">Satuan</label>
              </div>
              <div id="rightRail">
              
               <?php
                        $satuan_input = array(
                            'name' => 'satuan',
                            'value' => $satuan,
                            'class' => 'input-wrc required',
                            'id' => 'satuan'
                        );
                        echo form_input($satuan_input);
                        ?>
              </div>
      </div>

           <div id="statusRail">
              <div id="leftRail">
              </div>
              <div id="rightRail">

                <?php
            $add_satuan = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_satuan);
            echo "<span></span>";
            $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . site_url('settings/satuan') . '\''
            );
            echo form_button($cancel);
            echo form_close();
            ?>
              </div>
      </div>
                       
            
        </div>
    </div>
    <br style="clear: both;" />
</div>