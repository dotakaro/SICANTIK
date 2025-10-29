<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
         <?php
        $attr = array('id' => 'form');
        echo form_open('setting_tarif/'. $save_method, $attr);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b><i>Item</i></b></a></li>
                    <li><a href="#tabs-2"><b><i>Kategori Harga</i></b></a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $item_id = array(
                        'name' => 'id',
                        'id'=>'setting_tarif_id',
                        'type'=>'hidden',
                        'value'=>$id
                    );
                    echo form_input($item_id);

                    echo "<label class='label-wrc'>Nama Item</label>";
                    $nama_item_input = array(
                        'name' => 'nama_item',
                        'class' => 'input-wrc'
                    );
                    echo form_input($nama_item_input,set_value('nama_item',$nama_item));
                    echo "<br style='clear:both' />";

                    echo "<label class='label-wrc'>Satuan</label>";
                    $satuan_input = array(
                        'name' => 'satuan',
                        'class' => 'input-wrc'
                    );
                    echo form_input($satuan_input,set_value('satuan',$satuan));
                    echo "<br style='clear:both' />";

                    echo "<label class='label-wrc'>Jenis Izin</label>";
                    echo form_dropdown('trperizinan_id', $option_izin,set_value('trperizinan_id',$trperizinan_id),'id="trperizinan_id" class="input-select-wrc required-option"');

                    echo "<br style='clear:both' />";
                    $save_form = array(
                        'name' => 'submit',
                        'class' => 'submit-wrc',
                        'content' => 'Simpan',
                        'type' => 'submit',
                        'value' => 'Simpan'
                    );
                    echo form_submit($save_form);
                    echo "<span></span>";
                    $cancel = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Batal',
                        'onclick' => 'parent.location=\''. site_url('setting_tarif') . '\''
                    );
                    echo form_button($cancel);
                    
                    ?>	
                </div>
                <div id="tabs-2">
                    <?php 
                    $add_kategori = array(
                    'name' => 'btn_add',
                    'id'=>'btn_add',
                    'class' => 'button-wrc',
                    'content' => 'Tambah');
                    echo form_button($add_kategori);
                    ?>
                    <table id="tbl_group" class="display" cellspacing="0" cellpadding="0" border="1">
                        <thead>
                            <tr>
                            <th width="100px">Kategori</th>
                            <th width="100px">Harga</th>
                            <th width="80px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
        <?php echo form_close();?>
    </div>
    <br style="clear: both;" />
</div>
