<?php echo $this->load->view('edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'form');
            echo form_open('setting_menu/' . $save_method, $attr);
            $hiddenId = array(
                'name' => 'id',
                'id' => 'id',
                'value' => $data->id,
                'type'=>'hidden'
            );
            echo form_input($hiddenId);
            ?>
            <label>Nama Menu</label>
            <?php
            $txtNamaMenu = array(
                'name' => 'title',
                'id' => 'title',
                'class' => 'input-wrc required',
                'style'=>'width:600px',
                'value'=>$data->title
            );
            echo form_input($txtNamaMenu);?>
            <br style="clear: both" />

            <label>Link</label>
            <?php
            $txtLink = array(
                'name' => 'link',
                'id' => 'link',
                'class' => 'input-wrc required',
                'style'=>'width:600px',
                'value'=>$data->link
            );
            echo form_input($txtLink);?>
            <br style="clear: both" />

            <label>Parent Menu</label>
            <?php
            echo form_dropdown($name = 'parent', $listMenu, $data->parent, $extra = 'id="parent" class="input-select-wrc"');
            ?>
            <br style="clear: both" />

            <label>Urutan</label>
            <?php
            $txtMenuOrder = array(
                'name' => 'menu_order',
                'id' => 'menu_order',
                'class' => 'input-wrc required',
                'style'=>'width:50px',
                'value'=>$data->menu_order
            );
            echo form_input($txtMenuOrder);?>
            <br style="clear: both" />

            <?php
            $btnSubmit = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($btnSubmit);

            echo "<span></span>";
            /*$btnCancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('setting_menu') . '\''
            );*/
//            echo form_button($btnCancel);
            echo anchor('setting_menu', 'Batal', 'class="button-wrc" name="button" style="text-decoration:none;"');
            echo form_close();
            ?>
        </div>
    </div>
</div>

