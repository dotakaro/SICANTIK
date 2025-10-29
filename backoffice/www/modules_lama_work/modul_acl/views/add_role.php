<style type="text/css">
    .hidden{
        display: none;
    }
</style>
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
        );/*
        $('#tree').dynatree({
            minExpandLevel: 4,
            checkbox:true,
            selectMode: 3
        });*/
        $("#tree").dynatree({
//        Tree parameters
//            persist: true,
            minExpandLevel: 4,
            checkbox: true,
            selectMode: 3,
            activeVisible: true,
//            Un/check real checkboxes recursively after selection
            onSelect: function(select, dtnode) {
                dtnode.visit(function(dtnode){
                    $("#permission_"+dtnode.data.key).attr("checked",select);
                },null,true);
                //Check/uncheck checkbox di sebelahnya
                $("#permission_"+dtnode.data.key).attr("checked",select);
            },
//            Prevent reappearing of checkbox when node is collapse
            onExpand: function(select, dtnode) {
                $("#permission_"+dtnode.data.key).attr("checked", dtnode.isSelected()).addClass("hidden");
            }
        });

        //Hide real checkboxes
        $("#tree :checkbox").addClass("hidden");
        //Update real checkboxes according to selections
        $.map($("#tree").dynatree("getTree").getSelectedNodes(),
            function(dtnode){
                $("#permission_"+dtnode.data.key).attr("checked",true);
                dtnode.activate();
            });

        $('#form').validate();
        $("#tabs").tabs();
    });
</script>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>

        <div class="entry">
            <?php $attr = array('id' => 'form');
            echo form_open('modul_acl/' . $save_method, $attr);
            ?>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Data Peran</b></a></li>
                    <li><a href="#tabs-2"><b>Hak Akses</b></a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $hiddenId = array(
                        'name' => 'id',
                        'id' => 'role_id',
                        'type'=>'hidden'
                    );
                    echo form_input($hiddenId);
                    ?>

                    <label>Deskripsi Peran</label>
                    <?php
                    $txtDescription = array(
                        'name' => 'description',
                        'id' => 'description',
                        'class' => 'input-wrc required',
                        'style'=>'width:600px'
                    );
                    echo form_input($txtDescription);?>
                    <br style="clear: both" />
                </div>
                <div id="tabs-2">
                    <div id="tree"><?php echo $permissionTree;?></div>
                    <br style="clear: both" />
                </div>
            </div>
            <br style="clear: both;" />
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
                'onclick' => 'parent.location=\''. site_url('modul_acl/list_role') . '\''
            );
            echo form_button($btnCancel);
            echo form_close();
            ?>
        </div>
    </div>
</div>

