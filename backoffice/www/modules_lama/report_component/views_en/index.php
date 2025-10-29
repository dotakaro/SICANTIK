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
            echo form_open("report_component", $attr);
			$id_report = array(
                'name' => 'report_id',
                'id'=>'report_id',
				'type'=>'hidden'
            );
				
            $report_component_code = array(
                'name' => 'report_component_code',
                'id' => 'report_component_code'
            );
            $create = array(
                'name' => 'btn_create',
                'id' => 'btn_create',
                'class' => 'button-wrc',
                'value'=>'Create',
                'type' => 'submit'
            );
            $open = array(
                'name' => 'btn_open',
                'id' => 'btn_open',
                'class' => 'button-wrc',
                'value'=>'Open',
                'type' => 'submit',
				'disabled'=>true
            );
			$copy = array(
                'name' => 'btn_copy',
                'id' => 'btn_copy',
                'class' => 'button-wrc',
                'value'=>'Copy',
                'type' => 'submit',
				'disabled'=>true
            );			
            ?>
            <table id="t_cari" width="100%">               
                <tr>
                    <td width="15%"> <?php echo form_label('Report Component ID'); ?> </td>
                    <td width="85%"> <?php echo form_input($report_component_code); ?> </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
						<?php 
						echo form_input($id_report);
						echo form_submit($create);
						echo form_submit($open);
						echo form_submit($copy);
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
	
	$( "#report_component_code" ).live('keyup', function(e){
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
	
	$( "#report_component_code" ).combogrid({
		debug:true,
	    searchButton:true,           
		colModel: [{'columnName':'id','hidden':true,'width':'10','label':'ID'},
		 	{'columnName':'report_component_code','width':'45','label':'Report Component ID','align':'left'},
			{'columnName':'short_desc','width':'45','label':'Short Description','align':'left'}],
		url:webroot+'report_component/combo_grid_report_component',
		//"select item" event handler to set input fields
		select: function( event, ui ) {
			$( "#report_component_code" ).val( ui.item.report_component_code);
			$( "#report_id" ).val( ui.item.id);
	  		$("#btn_open").removeAttr('disabled');
	  		$("#btn_copy").removeAttr('disabled');
	        $('#btn_create').attr('disabled','disabled');
			return false;
		}
	});
	$("input[type=submit]").click(function(){
	    var id=$(this).attr("name");
	    $("input[name=clicked_button]").val(id);
		if(id=='btn_copy'){
			var konfirmasi = window.confirm("Anda yakin ingin mengcopy Komponen Report ini?");
			if(konfirmasi==false){
				return false;
			}
		}
	});
});
</script>	