<script type="text/javascript">
    $(document).ready(function() {
        var webroot='<?php echo base_url();?>';
        $.validator.addMethod("required-option",
            function(value, element) {
                switch( element.nodeName.toLowerCase() ) {
                    case 'select':
                        // could be an array for select-multiple or a string, both are fine this way
                        var val = $(element).val();
                        return val && val.length > 0 && val!=-1;
                }
            },
            "Mohon pilih"
        );
        $('#form').validate();
    });
</script>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'form');
            echo form_open('modul_acl/' . $save_method, $attr);
            $hiddenId = array(
                'name' => 'id',
                'id' => 'aco_id',
                'value' => $data->id,
                'type'=>'hidden'
            );
            echo form_input($hiddenId);
            ?>

            <label>Nama Modul Utama</label>
            <?php
            echo form_dropdown('main_module_name',$listDir, $data->main_module_name, 'class="input-select-wrc required-option"');?>
            <br style="clear: both" />

            <label>Key Permission</label>
            <?php
            $txtPermKey = array(
                'name' => 'perm_key',
                'id' => 'perm_key',
                'class' => 'input-wrc required',
                'value' => $data->perm_key,
//                'readonly'=>'readonly',
                'style'=>'width:600px'
            );
            echo form_input($txtPermKey);?>
            <br style="clear: both" />

            <label>Nama Permission</label>
            <?php
            $txtPermName = array(
                'name' => 'perm_name',
                'id' => 'perm_name',
                'class' => 'input-wrc required',
                'value' => $data->perm_name,
                'style'=>'width:600px'
            );
            echo form_input($txtPermName);?>
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
            $btnCancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('modul_acl/list_aco') . '\''
            );
            echo form_button($btnCancel);
            echo form_close();
            ?>
        </div>
    </div>
</div>

