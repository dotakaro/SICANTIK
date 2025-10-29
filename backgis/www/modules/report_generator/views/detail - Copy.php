<style type="text/css">
	textarea{
		width:500px;
		height:50px;
		resize:none;
	}
	textarea#query_text{
		width:100%;
		height:100px;
	}
	#tbl_group{
		width:800px;
		margin-left:0;
	}
</style>
<link href="<?php echo base_url();?>/assets/css/facebox.css" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url();?>/assets/css/Generic/admin.css" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url();?>/assets/css/Generic/form.css" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url();?>/assets/css/Generic/default.css" type="text/css" rel="stylesheet" />
<link href="<?php echo base_url();?>/assets/css/Generic/dropdown.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url();?>/assets/css/Generic/demo_table.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url();?>/assets/css/Generic/demo_table_jui.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url();?>/assets/css/Generic/themes/smoothness/jquery-ui-1.8.2.custom.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url();?>/assets/css/global.css" type="text/css" rel="stylesheet"/>
<script src="<?php echo base_url();?>/assets/js/base_url.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jsonp.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jquery-ui-1.8.2.custom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jquery.validate.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
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
	
	$("#detailform").validate({
		submitHandler: function(form){
	   		// some other code
	   		// maybe disabling submit button
	   		// then:
	   		saveDetailForm();
		}
	});	
	
	var numRowDet = <?php echo isset($report_tables['num_rows']) ? $report_tables['num_rows'] : 0;?>;	
	var joinTypeOption='<option value="-1">-please select-</option>';
	var list1=<?php echo $join_type_options;?>;
	$.each( list1, function( key, value ){
		joinTypeOption += '<option value ="'+key+'">'+value+'</option>';
	});
	$('.join_type').live('change',function(){
		if( $(this).val()==0||$(this).val()==-1){
			$(this).parent().next().find('.btn_detail_condition').attr('disabled',true);
		}else{
			$(this).parent().next().find('.btn_detail_condition').removeAttr('disabled');
		}
	});
	
	function deleteRow(buttonElement){
		$(buttonElement).parent().parent().remove();
	}
		
	$(".btn_delete_detail").live('click', function(event){
		deleteRow(this);
	});

	function addNewRowDetail(){
		if(numRowDet!='0'){
			var row='<tr>';
			
			row+='<td><input type="hidden" name="data[ReportTable][' + numRowDet + '][id]" class="report_table_id"/>';
			row+='<input name="data[ReportTable][' + numRowDet + '][table_name]" class="required"/></td>';
			row+='<td><select name="data[ReportTable][' + numRowDet + '][join_type]" class="join_type required-option">'+joinTypeOption+'</select></td>';
			row+='<td><input type="button" class="btn_detail_condition ui-widget-content" value="Conditions" disabled="true"/></td>';
			row+='<td><input type="button" class="btn_delete_detail ui-widget-content" value="Delete"/></td>';
			row+='</tr>';
			$("#tbl_table_detail tbody").append(row);
			numRowDet++;
		}else{
			var row='<tr>';
			row+='<td><input type="hidden" name="data[ReportTable][' + numRowDet + '][id]" class="report_table_id"/>';
			row+='<input name="data[ReportTable][' + numRowDet + '][table_name]" class="required"/></td>';
			row+='<td><select name="data[ReportTable][' + numRowDet + '][join_type]" class="join_type required-option">'+joinTypeOption+'</select></td>';
			row+='<td>&nbsp;</td>';
			row+='<td>&nbsp;</td>';
			row+='</tr>';
			$("#tbl_table_detail tbody").append(row);
			numRowDet++;
		}
	}

	var numRowFld = <?php echo isset($report_fields['num_rows']) ? $report_fields['num_rows'] : 0;?>;	
	function addNewRowField(){
		var row='<tr>';
		
		row+='<td><input type="hidden" name="data[ReportField][' + numRowFld + '][id]" class="report_field_id"/>';
		row+='<input name="data[ReportField][' + numRowFld + '][table_name]" class="required"/></td>';
		row+='<td><input name="data[ReportField][' + numRowFld + '][field]" class="required"/></td>';
		row+='<td><input name="data[ReportField][' + numRowFld + '][field_alias]" class="required"/></td>';
		row+='<td><input type="button" class="btn_delete_detail ui-widget-content" value="Delete"/></td>';
		row+='</tr>';
		$("#tbl_field tbody").append(row);
		numRowFld++;
	}

	var relationTypeOption='<option value="0">-please select-</option>';
	var list2=<?php echo $relation_type;?>;
	$.each( list2, function( key, value ){
		relationTypeOption += '<option value ="'+key+'">'+value+'</option>';
	});
	var conTypeOption='<option value="0">-please select-</option>';
	var list3=<?php echo $conditional_type;?>;
	$.each( list3, function( key, value ){
		conTypeOption += '<option value ="'+key+'">'+value+'</option>';
	});
	
	var numRowCon = <?php echo isset($report_conditions['num_rows']) ? $report_conditions['num_rows'] : 0;?>;	
	function addNewRowCon(){
		var row='<tr>';
		
		row+='<td><input name="data[ReportCondition][' + numRowCon + '][id]" type="hidden" class="report_condition_id"/>';
		row+='<input name="data[ReportCondition][' + numRowCon + '][report_table_field1]" class="required"/>
				  <input name="data[ReportCondition][' + numRowCon + '][report_table]" value="" type="hidden"/></td>';
		row+='<td><input name="data[ReportCondition][' + numRowCon + '][report_field1]" class="required"/></td>';
		row+='<td><select name="data[ReportCondition][' + numRowCon + '][condition_type]" class="relation_type required-option">'+conTypeOption+'</select></td>';
		row+='<td><input name="data[ReportCondition][' + numRowCon + '][report_table_field2]" class="required"/></td>';
		row+='<td><input name="data[ReportCondition][' + numRowCon + '][report_field2]" class="required"/></td>';
		row+='<td><select name="data[ReportCondition][' + numRowCon + '][relation_type]" class="relation_type required-option">'+relationTypeOption+'</select></td>';
		row+='<td><input type="button" class="btn_delete_detail ui-widget-content" value="Delete"/></td>';
		row+='</tr>';
		$("#tbl_condition tbody").append(row);
		numRowCon++;
	}
	
	$(".btn_detail_condition").live('click', function(event){
	var a = confirm('You will leave this page. All unsaved data will be erased. Continue?');
		if(a){
			window.location.href ="<?php echo site_url('report_generator/join_condition');?>";
		}
	});
	
	$("#tab_detail").tabs();
	$("#btn_add_detail").click(function(){
		addNewRowDetail();
	});
	$("#btn_add_field").click(function(){
		addNewRowField();
	});
	$("#btn_add_condition").click(function(){
		addNewRowCon();
	});
	
	function saveDetailForm(){//Fungsi untuk menyimpan data report generator dan report group data
		var url_data='<?php echo site_url("report_generator/save_detailform");?>';
		var data=$('#detailform').serialize();
		$.ajax({
			url:url_data,
			data:data,
			type:'POST',
			dataType:'json',
			success:function(response){
				if(response.success==true){
					alert('Data tersimpan');					
					var idTable=[];
					var idField=[];
					var idCondition=[];
					var x=0;
					$.each( response.report_table, function(i,row) {
						idTable.push(row.id);
					});
					$.each( response.report_field, function(i,row) {
						idField.push(row.id);
					});
					$.each( response.report_condition, function(i,row) {
						idCondition.push(row.id);
					});
					$("#tbl_table_detail > tbody > tr").each(function() {
						$this=$(this);
						$this.find('input[type="hidden"].report_table_id').val(idTable[x]);
						x++;
					});
					x=0;
					$("#tbl_field > tbody > tr").each(function() {
						$this=$(this);
						$this.find('input[type="hidden"].report_field_id').val(idField[x]);
						x++;
					});
					x=0;
					$("#tbl_condition > tbody > tr").each(function() {
						$this=$(this);
						$this.find('input[type="hidden"].report_condition_id').val(idCondition[x]);
						x++;
					});

				}else{
					alert('Data tidak tersimpan');
				}
			}
		});
	}
});



</script>
<div id="content">
    <div class="post">
	<?php
        $attr = array('id' => 'detailform');//Indra
		$id_report_group = array(
            'name' => 'data[ReportGroupData][id]',
            'id'=>'ReportGroupDataId',
			'value'=>$report_group_data->id,
			'readonly'=>true,
			'type'=>'hidden'
        );
		$report_generator_id= array(
            'name' => 'data[ReportGroupData][report_generator_id]',
            'id'=>'ReportGroupReportGeneratorId',
			'value'=>$report_group_data->report_generator_id,
			'readonly'=>true,
			'type'=>'hidden'
        );				
        $report_group_code = array(
            'name' => 'data[ReportGroupData][report_code]',
            'value' => $report_group_data->report_group_code,
            'class' => 'input-wrc',
			'id'=>'ReportGroupDataReportCode',
			'readonly'=>true
        );	
        $short_desc = array(
            'name' => 'data[ReportGroupData][short_desc]',
            'value' => $report_group_data->short_desc,
            'class' => 'input-wrc',
			'id'=>'ReportGroupDataShortDesc',
			'readonly'=>true
        );	
        $group_query = array(
            'name' => 'data[ReportGroupData][group_query]',
            'value' => $report_group_data->group_query,
            'class' => 'input-area-wrc',
			'id'=>'ReportGroupDataGroupQuery',
			'readonly'=>true
        );	
		
        echo form_open('report_generator/add_detail/', $attr);
	?>
		<div class="entry">
            <?php
				echo form_input($id_report_group);
				echo form_input($report_generator_id);
				echo form_label('Report Group ID');
				echo form_input($report_group_code);
			?>
			<br style="clear: both" />
			<?php
				echo form_label('Short Description');
				echo form_input($short_desc);
			?>
			<br style="clear: both" />
			<?php
				echo form_label('Group Query');
				echo form_input($group_query);
			?>
			<br style="clear: both" />
	        <?php
            $add_koefisien = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_koefisien);
            ?>    
			<br style="clear: both" />
	        <br style="clear: both" />
	        
			<div id="tab_detail">
                <ul>
                    <li><a href="#tabdetail-1"><b>Table</b></a></li>
                    <li><a href="#tabdetail-2"><b>Field</b></a></li>
                    <li><a href="#tabdetail-3"><b>Condition</b></a></li>
                </ul>
                <div id="tabdetail-1">
					<?php 
						$add_group = array(
			                'name' => 'btn_add_detail',
							'id'=>'btn_add_detail',
			                'class' => 'button-wrc',
			                'content' => 'Add');
						echo form_button($add_group);
					?>
					<table id="tbl_table_detail" class="display" cellspacing="0" cellpadding="0" border="1">
						<thead>
							<tr>
							<th width="150px">Report Table</th>
							<th width="250px">Report Join Type</th>
							<th width="250px">Condition</th>
							<th width="250px">Actions</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$x=0;foreach($report_tables['rows'] as $report_table):
							$join_type_options=json_decode($join_type_options);
							$joinTypeList='<option value="-1">-please select-</option>';
							foreach($join_type_options as $key=>$value){
								if($report_table->join_type == $key){
									$selected=' selected="true" ';	
								}else{
									$selected="";
								}
								$joinTypeList .= '<option value ="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						?>
						<tr>
						<td>
							<input type="hidden" name="data[ReportTable][<?php echo $x;?>][id]" value="<?php echo $report_table->id;?>" class="report_table_id"/>
							<input class="required" name="data[ReportTable][<?php echo $x;?>][table_name]" value="<?php echo $report_table->table_name;?>"/></td>
						<td><select class="join_type required-option" name="data[ReportTable][<?php echo $x;?>][join_type]"><?php echo $joinTypeList;?></select></td>
						<td><input type="button" disabled="true" value="Conditions" class="btn_detail_condition ui-widget-content"/></td><td><input type="button" value="Delete" class="btn_delete_detail ui-widget-content"/></td></tr>
						<?php $x++;endforeach;?>
						</tbody>
					</table>	
				</div>
				<div id="tabdetail-2">	
					<?php 
						$add_group = array(
			                'name' => 'btn_add_field',
							'id'=>'btn_add_field',
			                'class' => 'button-wrc',
			                'content' => 'Add');
						echo form_button($add_group);
					?>
					<table id="tbl_field" class="display" cellspacing="0" cellpadding="0" border="1">
						<thead>
							<tr>
							<th width="150px">Report Table</th>
							<th width="250px">Report Field</th>
							<th width="250px">Report Field Alias</th>
							<th width="250px">Actions</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$x=0;foreach($report_fields['rows'] as $report_field):
						?>						
						<tr>
						<td>
							<input type="hidden" class="report_field_id" name="data[ReportField][<?php echo $x;?>][id]" value="<?php echo $report_field->id;?>"/>
							<input class="required" name="data[ReportField][<?php echo $x;?>][table_name]" value="<?php echo $report_field->table_name;?>"/></td>
						<td><input class="required" name="data[ReportField][<?php echo $x;?>][field]" value="<?php echo $report_field->field;?>"/></td>
						<td><input class="required" name="data[ReportField][<?php echo $x;?>][field_alias]" value="<?php echo $report_field->field_alias;?>"/></td><td><input type="button" value="Delete" class="btn_delete_detail ui-widget-content"></td>
						</tr>						
						<?php $x++;endforeach;?>
						</tbody>
					</table>
				</div>
				<div id="tabdetail-3">	
					<?php 
						$add_group = array(
			                'name' => 'btn_add_condition',
							'id'=>'btn_add_condition',
			                'class' => 'button-wrc',
			                'content' => 'Add');
						echo form_button($add_group);
					?>
					<table id="tbl_condition" class="display" cellspacing="0" cellpadding="0" border="1">
						<thead>
							<tr>
							<th width="150px">Table 1</th>
							<th width="250px">Field 1</th>
							<th width="250px">Condition Type</th>
							<th width="150px">Table 2</th>
							<th width="250px">Field 2</th>
							<th width="250px">Relation Type</th>
							<th width="250px">Actions</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$conditional_type=json_decode($conditional_type);
						$relation_type=json_decode($relation_type);
						$x=0;foreach($report_conditions['rows'] as $report_condition):
							
							$conditionList='<option value="-1">-please select-</option>';
							foreach($conditional_type as $key=>$value){
								if($report_condition->condition_type == $key){
									$selected=' selected="true" ';	
								}else{
									$selected="";
								}
								$conditionList .= '<option value ="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
							
							
							$relationList='<option value="-1">-please select-</option>';
							foreach($relation_type as $key=>$value){
								if($report_condition->relation_type == $key){
									$selected=' selected="true" ';	
								}else{
									$selected="";
								}
								$relationList.= '<option value ="'.$key.'" '.$selected.'>'.$value.'</option>';
							}														
						?>
						<tr>
						<td>
							<input type="hidden" class="report_condition_id" name="data[ReportCondition][<?php echo $x;?>][id]" value="<?php echo $report_condition->id;?>"/>				  				
							<input class="required" name="data[ReportCondition][<?php echo $x;?>][report_table_field1]" value="<?php echo $report_condition->report_table_field1;?>"/>				  				
							<input type="hidden" value="" name="data[ReportCondition][<?php echo $x;?>][report_table]" value="<?php echo $report_condition->report_table;?>"/>
						</td>
						<td><input class="required" name="data[ReportCondition][<?php echo $x;?>][report_field1]" value="<?php echo $report_condition->report_field1;?>"/></td>
						<td>
							<select class="relation_type required-option" name="data[ReportCondition][<?php echo $x;?>][condition_type]"><?php echo $conditionList;?></select></td>
						<td><input class="required" name="data[ReportCondition][<?php echo $x;?>][report_table_field2]" value="<?php echo $report_condition->report_table_field2;?>"/></td>
						<td><input class="required" name="data[ReportCondition][<?php echo $x;?>][report_field2]" value="<?php echo $report_condition->report_field2;?>"/></td>
						<td><select class="relation_type required-option" name="data[ReportCondition][2][relation_type]"><?php echo $relationList;?></select></td>
						<td><input type="button" value="Delete" class="btn_delete_detail ui-widget-content"/></td></tr>										
						<?php $x++;endforeach;?>
						</tbody>
					</table>
				</div>
				
            </div>
            
        </div>
	<?php echo form_close();?>
    </div>
	<!--Dialog Popup-->
	<div id="query_form" style="display:none ;">
        <div class="input text">
			<?php echo form_textarea(array('name'=>'query_text','id'=>'query_text'));?>
        </div>
 	</div>
	
	<!---->
    <br style="clear: both;" />
</div>