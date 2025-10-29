<style type="text/css">
    .combogrid input[type="text"]{
        width:10px;
    }
    .cg-searchButton{
        margin-top:0px !important;
    }
</style>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>

        <fieldset id="half">
            <?php
            $attr = array(
                'class' => 'searchForm',
                'id' => 'searchForm'
            );
            echo form_open("notification_setting", $attr);
            $id_report = array(
                'name' => 'setting_id',
                'id'=>'setting_id',
                'type'=>'hidden'
            );

            $setting_code = array(
                'name' => 'setting_code',
                'id' => 'setting_code'
            );
            $create = array(
                'name' => 'btn_create',
                'id' => 'btn_create',
                'class' => 'button-wrc',
                'value'=>'Buat Baru',
                'type' => 'submit'
            );
            $open = array(
                'name' => 'btn_open',
                'id' => 'btn_open',
                'class' => 'button-wrc',
                'value'=>'Buka',
                'type' => 'submit',
                'disabled'=>true
            );
            $copy = array(
                'name' => 'btn_copy',
                'id' => 'btn_copy',
                'class' => 'button-wrc',
                'value'=>'Gandakan',
                'type' => 'submit',
                'disabled'=>true
            );
            $delete = array(
                'name' => 'btn_delete',
                'id' => 'btn_delete',
                'class' => 'button-wrc',
                'type' => 'submit',
                'disabled'=>true
            );
            ?>
            <table id="t_cari" width="100%">
                <tr>
                    <td width="15%"> <?php echo form_label('ID Setting Notifikasi'); ?> </td>
                    <td width="85%"> <?php echo form_input($setting_code); ?> </td>
                </tr>
                <tr>
                    <td width="15%">&nbsp;</td>
                    <td width="85%">
                        <?php
                        echo form_input($id_report);
                        echo form_submit($create);
                        echo form_submit($open);
                        echo form_submit($copy);
                        echo form_button($delete,'Hapus');
                        echo form_hidden('clicked_button');
                        echo form_close();
                        ?>
                    </td>
                </tr>
            </table>

        </fieldset>

        <div class="entry">

        </div>
    </div>
    <br style="clear: both;" />
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var webroot='<?php echo base_url();?>';

        $( "#setting_code" ).live('keyup', function(e){
            //Improved with keycode checking to prevent extra typing after select
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            var keyCode = $.ui.keyCode;
//		if(key != keyCode.ENTER && key != keyCode.LEFT && key != keyCode.RIGHT && key != keyCode.DOWN) {
//			$('#searchPerson').val("");
//		}
            $("#btn_create").removeAttr('disabled');
            $('#btn_open').attr('disabled','disabled');
            $('#btn_copy').attr('disabled','disabled');
        });

        $( "#setting_code" ).combogrid({
            searchButton:false,
            okIcon:true,
            width:'600px',
            colModel: [{'columnName':'id','hidden':true,'label':'ID'},
                {'columnName':'report_component_code','width':'35','label':'ID <i>Report Component</i>','align':'left'},
                {'columnName':'short_desc','width':'65','label':'Deskripsi Singkat','align':'left'}],
            url:webroot+'report_component/combo_grid_report_component',
            //"select item" event handler to set input fields
            select: function( event, ui ) {
                $( "#setting_code" ).val( ui.item.report_component_code);
                $( "#setting_id" ).val( ui.item.id);
                $("#btn_open").removeAttr('disabled');
                $("#btn_copy").removeAttr('disabled');
                $("#btn_delete").removeAttr('disabled');
                $('#btn_create').attr('disabled','disabled');
                return false;
            }
        });
        $("input[type=submit]").click(function(){
            var id=$(this).attr("name");
            $("input[name=clicked_button]").val(id);
            if(id=='btn_copy'){
                var konfirmasi = window.confirm("Anda yakin ingin menggandakan Komponen Setting Notifikasi ini?");
                if(konfirmasi==false){
                    return false;
                }
            }
        });
        $("#btn_delete").click(function(){
            var report_component_code = $( "#report_component_code" ).val();
            var report_component_id = $( "#report_id" ).val();
            var konfirmasi = window.confirm("Anda yakin ingin menghapus setting \""+report_component_code+"\"?");
            if(konfirmasi==true){
                $.ajax({
                    url:webroot+'report_component/delete',
                    type:'POST',
                    data:{
                        'report_component_id':report_component_id
                    },
                    beforeSend:function(){
                        $("#content").loadOverStart();
                    },
                    complete:function(){
                        $("#content").loadOverStop();
                    },
                    success:function(ret){
                        if(ret == 1){
                            alert('Setting '+report_component_code+' berhasil dihapus');
                            $("#report_component_code, #report_id" ).val('');
                            $("#btn_open").attr('disabled','disabled');
                            $("#btn_copy").attr('disabled','disabled');
                            $("#btn_delete").attr('disabled','disabled');
                            $('#btn_create').removeAttr('disabled');
                        }else{
                            alert('Setting '+report_component_code+' tidak berhasil dihapus. Silahkan coba lagi.');
                        }
                    }
                });
            }
            return false;
        });
    });
</script>	