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
	body{
		background-color:#FFF;
	}

	.submit-wrc{
		width:65px;
	}
	/*Style untuk combo grid*/
	.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button{
		font-size:0.8em !important;
	} 
	#cg-navInfo{
		font-size:0.8em;
	}
	.combogrid input[type="text"]{
		width:20px;
	}
	.combogrid{
		 min-width: 400px!important;
	}
	.cg-searchButton {
	    margin: 1px 1px 1px 1px !important;
	}	
	/*************/
	
	/*Style Tabel*/
	table.report_generator th{
		font-size:0.9em;
	}
	table.report_generator{
		margin: 0 auto;
		width:1000px;
		margin-left:0;
		clear: both;
		border-collapse: collapse;
	}

	table.report_generator tfoot th {
		padding: 3px 0px 3px 10px;
		font-weight: bold;
		font-weight: normal;
	}

	table.report_generator tr.heading2 td {
		border-bottom: 1px solid #aaa;
	}

	table.report_generator td {
		padding:2px 30px 2px 1px;
		text-align:center;
	}

	table.report_generator td.center {
		text-align: center;
	}
	table.report_generator input[type="text"]{
		width:120px;
	}
	table.report_generator input[type="button"]{
		min-width:50px;
		width:auto;
	}
	/*************/
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
<link href="<?php echo base_url();?>/assets/css/jquery.ui.combogrid.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo base_url();?>/assets/css/loadover.css" type="text/css" rel="stylesheet"/>
<script src="<?php echo base_url();?>/assets/js/base_url.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jsonp.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jquery-ui-1.8.2.custom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/jquery.ui.combogrid-1.6.2.mod.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>/assets/js/loadover.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function() {
	var webroot="<?php echo base_url();?>";
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
	
	$("#joinconditionform").validate({
		submitHandler: function(form){
	   		// some other code
	   		// maybe disabling submit button
	   		// then:
	   		saveJoinform();
		}
	});
	var relationTypeOption='<option value="-1">-please select-</option>';
	var list2=<?php echo $relation_type;?>;
	$.each( list2, function( key, value ){
		relationTypeOption += '<option value ="'+key+'">'+value+'</option>';
	});
	var conTypeOption='<option value="-1">-please select-</option>';
	var list3=<?php echo $conditional_type;?>;
	$.each( list3, function( key, value ){
		conTypeOption += '<option value ="'+key+'">'+value+'</option>';
	});
	var numRowCon = <?php echo isset($report_conditions['num_rows']) ? $report_conditions['num_rows'] : 0;?>;	
	function addNewRowCon(){
		var row='<tr>';
		
		row+='<td><input name="data[ReportCondition][' + numRowCon + '][id]" type="hidden" class="report_detail_id"/>';
		row+='<input type="text" id="ReportConditionTableField1_' + numRowCon + '" name="data[ReportCondition][' + numRowCon + '][report_table_field1]" class="required combo_grid_table"/>';
		row+='<input name="data[ReportCondition][' + numRowCon + '][report_table]" value="<?php echo $report_table->table_name;?>" type="hidden"/></td>';
		row+='<td><input type="text" id="ReportConditionField1_' + numRowCon + '" name="data[ReportCondition][' + numRowCon + '][report_field1]" class="required combo_grid_field"/></td>';
		row+='<td><select name="data[ReportCondition][' + numRowCon + '][condition_type]" class="relation_type required-option">'+conTypeOption+'</select></td>';
		row+='<td><input type="text" id="ReportConditionTableField2_' + numRowCon + '" name="data[ReportCondition][' + numRowCon + '][report_table_field2]" class="required combo_grid_table"/></td>';
		row+='<td><input type="text" type="text" id="ReportConditionField2_' + numRowCon + '" name="data[ReportCondition][' + numRowCon + '][report_field2]" class="required combo_grid_field"/></td>';
		row+='<td><select name="data[ReportCondition][' + numRowCon + '][relation_type]" class="relation_type required-option">'+relationTypeOption+'</select></td>';
		row+='<td><input type="button" class="btn_delete_detail ui-widget-content" value="Delete"/></td>';
		row+='</tr>';
		$("#tbl_condition tbody").append(row);
		add_combogrid_table();
		add_combogrid_field();		
		numRowCon++;
	}

	$("#btn_add_condition").click(function(){
		addNewRowCon();
	});

	function saveJoinform(){
		var url_data='<?php echo site_url("report_generator/save_joinform");?>';
		var data=$('#joinconditionform').serialize();
		$.ajax({
			url:url_data,
			data:data,
			type:'POST',
			dataType:'json',
			beforeSend:function(){
				$("#content").loadOverStart();
			},
			complete:function(){
				$("#content").loadOverStop();
			},
			success:function(response){
				if(response.success==true){
					alert('Data tersimpan');					

					var idCondition=[];
					var x=0;
					
					$.each( response.report_condition, function(i,row) {
						idCondition.push(row.id);
					});
					
					$("#tbl_condition > tbody > tr").each(function() {
						$this=$(this);
						$this.find('input[type="hidden"].report_detail_id').val(idCondition[x]);
						x++;
					});

				}else{
					alert('Data tidak tersimpan');
				}
			}
		});
	}
	
	function deleteData(report_data_id,url_data,row){
		var url=url_data;
		var data={
			id:report_data_id
		};
	
		$.ajax({
			url:url,
			data:data,
			type:'POST',
			beforeSend:function(){
				$("#content").loadOverStart();
			},
			complete:function(){
				$("#content").loadOverStop();
			},			
			success:function(response){
				if(response==1){
					removeRow(row);	
				}else{
					alert('Can\'t delete this data. Please try again');
				}
			}
		});
	}
	
	function removeRow(buttonElement){
		$(buttonElement).parent().parent().remove();
	}
	
    $(".btn_delete_detail").live('click', function(){
		var url_data='<?php echo site_url("report_generator/delete_condition");?>';			
		var report_detail_id=$(this).parent().parent().find('td input[type="hidden"].report_detail_id').val();

		if(report_detail_id!=''){
			var confirm=window.confirm('Are you sure want to delete this data? This action can\'t be rolled back.')	
			if(confirm==true){
				deleteData(report_detail_id,url_data,this);				
			}
		}else{
			removeRow(this);
		}
		
	});
	
	function add_combogrid_table(){
		$( ".combo_grid_table").each(function(){
			
			var selector="#"+$(this).attr("id");
			var otherParameters={
				report_group_id:<?php echo $report_table->report_group_data_id;?>
			};
			$(selector).combogrid({
				debug:true,
			    searchButton:true,
				otherParam:otherParameters,           
				colModel: [{'columnName':'table_name','width':'45','label':'Table Name','align':'left'}],
				url:webroot+'report_generator/cg_reg_table',
				//"select item" event handler to set input fields
				select: function( event, ui ) {
					$( selector ).val( ui.item.table_name);
					add_combogrid_field();
					return false;
				}
			});	
		});
	}
	add_combogrid_table();	
	
	function add_combogrid_field(){
		$( ".combo_grid_field").each(function(){
			var otherParameters={
				tbl_name:$(this).parent().prev().find('.combo_grid_table').val()
			}
			var selector="#"+$(this).attr("id");
			/*alert(selector);*/
			$(selector).combogrid({
			    searchButton:true,
          		otherParam:otherParameters,
				colModel: [{'columnName':'column_name','width':'45','label':'Table Name','align':'left'}],
				url:webroot+'report_generator/cg_reg_field',
				//"select item" event handler to set input fields
				select: function( event, ui ) {
					$( selector ).val( ui.item.column_name);
					return false;
				}
			});
		});
	}

	add_combogrid_field();	
	
});
</script>
<div id="main">
<div id="header" style="height:30px;text-align:center;color:#FFF">
    <div class="instansi" style="font-size:20px;margin:0 auto">
		Form <i>JOIN Query</i>
    </div>
</div>
<div id="content">
   <div class="post">
		<?php
	        $attr = array('id' => 'joinconditionform');//Indra
	        echo form_open('report_generator/add_joincondtion/', $attr);
		?>
		<div class="entry">
			<?php
				$table_name= array(
	                'name' => 'report_table',
	                'class' => 'input-wrc',
	                'type' => 'text',
					'readonly'=>true,
	                'value' => $report_table->table_name
	            );
				$report_generator_id= array(
	                'name' => 'data[ReportTable][report_generator_id]',
	                'class' => 'input-wrc',
	                'type' => 'hidden',
	                'value' => $report_table->report_generator_id
	            );
				$report_group_data_id=array(
	                'name' => 'data[ReportTable][report_group_data_id]',
	                'class' => 'input-wrc',
	                'type' => 'hidden',
	                'value' => $report_table->report_group_data_id
	            );								
				echo form_label('Table Laporan');
				echo form_input($table_name);
				echo form_input($report_generator_id);
				echo form_input($report_group_data_id);
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
			
			$cancel_koefisien = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Kembali ke Detail',
                'onclick' => 'parent.location=\''. site_url('report_generator/detail/'.$report_table->report_group_data_id).'\''
            );
            echo form_button($cancel_koefisien);
            ?>  
			
			<br/>
			<?php 
			$add_group = array(
                'name' => 'btn_add_condition',
				'id'=>'btn_add_condition',
                'class' => 'button-wrc',
                'content' => 'Add');
			echo form_button($add_group);
			?>
			<table id="tbl_condition" class="report_generator" cellspacing="0" cellpadding="0" border="1">
				<thead>
					<tr>
					<th width="140px">Tabel 1</th>
					<th width="140px">Kolom 1</th>
					<th width="100px">Tipe Kondisi</th>
					<th width="140px">Tabel 2</th>
					<th width="140px">Kolom 2</th>
					<th width="100px">Tipe Relasi</th>
					<th width="100px">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					$conditional_type=json_decode($conditional_type);
					$relation_type=json_decode($relation_type);
					$x=0;foreach($report_conditions['rows'] as $report_condition):
						
						$conditionList='<option value="-1">-pilih-</option>';
						foreach($conditional_type as $key=>$value){
							if($report_condition->condition_type == $key){
								$selected=' selected="true" ';	
							}else{
								$selected="";
							}
							$conditionList .= '<option value ="'.$key.'" '.$selected.'>'.$value.'</option>';
						}
						
						
						$relationList='<option value="-1">-pilih-</option>';
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
						<input type="hidden" class="report_detail_id" name="data[ReportCondition][<?php echo $x;?>][id]" value="<?php echo $report_condition->id;?>"/>				  				
						<input type="text" class="required combo_grid_table" id="ReportConditionTableField1_<?php echo $x;?>" name="data[ReportCondition][<?php echo $x;?>][report_table_field1]" value="<?php echo $report_condition->report_table_field1;?>"/>				  				
						<input type="hidden" name="data[ReportCondition][<?php echo $x;?>][report_table]" value="<?php echo $report_condition->report_table;?>"/>
					</td>
					<td><input type="text" id="ReportConditionField1_<?php echo $x;?>" class="required combo_grid_field" name="data[ReportCondition][<?php echo $x;?>][report_field1]" value="<?php echo $report_condition->report_field1;?>"/></td>
					<td>
						<select class="relation_type required-option" name="data[ReportCondition][<?php echo $x;?>][condition_type]"><?php echo $conditionList;?></select></td>
					<td><input type="text" class="required combo_grid_table" id="ReportConditionTableField2_<?php echo $x;?>" name="data[ReportCondition][<?php echo $x;?>][report_table_field2]" value="<?php echo $report_condition->report_table_field2;?>"/></td>
						<td><input type="text" id="ReportConditionField2_<?php echo $x;?>" class="required combo_grid_field" name="data[ReportCondition][<?php echo $x;?>][report_field2]" value="<?php echo $report_condition->report_field2;?>"/></td>
					<td><select class="relation_type required-option" name="data[ReportCondition][<?php echo $x;?>][relation_type]"><?php echo $relationList;?></select></td>
					<td><input type="button" value="Hapus" class="btn_delete_detail ui-widget-content"/></td></tr>										
					<?php $x++;endforeach;?>				
				</tbody>
			</table>  
		</div>
<?php echo form_close();?>
   </div>
</div>
</div>