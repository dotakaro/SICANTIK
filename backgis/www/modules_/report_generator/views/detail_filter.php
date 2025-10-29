<style type="text/css">
	textarea.input-area-wrc{
		width:100%;
		height:auto;
		resize:none;
	}
    textarea#query_text{
        width:100%;
        height:100%;
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
		width:950px;
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

    textarea.query-filter{
        height:100%;
        width:100%;
    }

    .filter-no{
        width:50px !important;
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

<script>
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
	
	$("#detailform").validate({
		submitHandler: function(form){
	   		// some other code
	   		// maybe disabling submit button
	   		// then:
	   		saveDetailForm();
		}
	});	

	var filterTypeOption='<option value="-1">-select-</option>';
	var list1=<?php echo $filter_type;?>;
	$.each( list1, function( key, value ){
        filterTypeOption += '<option value ="'+key+'">'+value+'</option>';
	});
	
	var numRowFilter = $('#tbl_filter tbody tr').length;
	function addNewRowFilter(){
		var row='<tr>';
		var filterTypeId = 'ReportFilterFilterType'+numRowFilter;
		var dialogQueryId = 'ReportFilterFilterType'+numRowFilter;
        row+='<td><input id="ReportFilterFilterNo_' + numRowFilter + '" name="data[ReportFilter][' + numRowFilter + '][filter_no]" type="number" class="filter-no"/>';
        row+='<td><input name="data[ReportFilter][' + numRowFilter + '][id]" type="hidden" class="report_detail_id"/>';
		row+='<input type="text" id="ReportFilterFilterName_' + numRowFilter + '" name="data[ReportFilter][' + numRowFilter + '][filter_name]" class="required filter-name"/></td>';
		row+='<td><input type="text" readonly="readonly" id="ReportFilterFilterVariable_' + numRowFilter + '" name="data[ReportFilter][' + numRowFilter + '][filter_variable]" class="required filter-variable"/></td>';
		row+='<td>';
        row+='<select name="data[ReportFilter][' + numRowFilter + '][filter_type]" id="'+filterTypeId+'" class="required-option filter-type">'+filterTypeOption+'</select>';
        row+='&nbsp;<button type="button" class="btn-dialog-query" style="dislay:none;">Query</button>';
//        row+='<div id="'+dialogQueryId+'" class="dialog-query"><textarea name="data[ReportFilter][' + numRowFilter + '][query_filter]" class="query-filter"></textarea></div>';
        row+='<input type="hidden" name="data[ReportFilter][' + numRowFilter + '][query_filter]" class="query-filter"/>';
        row+='</td>';
		row+='<td><input type="button" class="btn_delete_detail ui-widget-content" value="Delete"/></td>';
		row+='</tr>';
		$("#tbl_filter tbody").append(row);
//        initDialog(filterTypeId);
        checkFilterType(filterTypeId);
        $('#'+filterTypeId).change(function(){
            checkFilterType(filterTypeId);
        });
        numRowFilter++;
	}

    function checkFilterType(filterTypeId){
        var filterTypeElem = $('#'+filterTypeId);
        var filterType = filterTypeElem.val();
        console.log(filterType);
        switch(filterType){
            case 'dropdown':
            case 'single_dropdown':
            case 'multi_dropdown':
                filterTypeElem.parent().find('.btn-dialog-query').show();
                break;
            default:
                filterTypeElem.parent().find('.btn-dialog-query').hide();
                break;
        }
    }

    //BEGIN - Create Dialog
    /*function initDialog(filterTypeId){
        var dialogQueryElem = $('#'+filterTypeId).parent().find('.dialog-query');
        var detailDialog = dialogQueryElem
            .dialog({
                autoOpen:false,
                modal: true,
                show:'blind',
                hide:'blind',
                title: 'Query Dropdown',
                width: 500,
                height: 300,
                buttons: {
                    'Tutup': function() {
                        $(this).dialog('close');
                    }
                }
            });
        detailDialog.parent().appendTo($("#detailform"));//Agar data di dialog dapat dipost juga

        $('#'+filterTypeId).parent().find('.btn-dialog-query').click(function(){
            detailDialog.dialog('open');
        });
    }*/
    //END - Create Dialog

	$("#tab_detail").tabs();
	$("#btn_add_filter").click(function(){
		addNewRowFilter();
	});

    $('.filter-name').live('keyup', function(){
        var text = $(this).val();
        text = text.toLowerCase();
        text = '$'+text.replace(/[^a-zA-Z0-9]+/g,'_');
        $(this).parent().parent().find('.filter-variable').val(text);
    });

    $(".btn-dialog-query").live('click',function(){
        var obj_direct_query=$(this).next();
        var query=obj_direct_query.val();
        $('#query_form textarea').val(query);
        $( "#query_form" ).dialog({
            show : "scale",
            hide : "scale",
            autoOpen : false,
            overlay : { opacity : 0.2 , background : "blue"},
            height: 300,
            width : 500,
            modal: true ,
            title : '<i>Query</i> Langsung',
            closeOnEscape: true,
            close : function(){
                var query = $('textarea[name="query_text"]').val();
                obj_direct_query.val(query);
            },
            buttons : {
                "OK":function(){
                    $("#query_form").dialog("close");
                }
            }
        });
        $("#query_form").dialog("open");
        return false;
    });

	function saveDetailForm(){//Fungsi untuk menyimpan data report filter
		var url_data='<?php echo site_url("report_generator/save_filterform");?>';
		var data=$('#detailform').serialize();
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
					var idFilter=[];
					var x=0;
					$("#ReportGroupDataGroupQuery").val(response.group_query);
					$.each( response.report_filter, function(i,row) {
						idFilter.push(row.id);
					});
					$("#tbl_table_detail > tbody > tr").each(function() {
						$this=$(this);
						$this.find('input[type="hidden"].report_detail_id').val(idTable[x]);
						x++;
					});
					x=0;
					$("#tbl_field > tbody > tr").each(function() {
						$this=$(this);
						$this.find('input[type="hidden"].report_detail_id').val(idField[x]);
						x++;
					});
					x=0;
					$("#tbl_filter > tbody > tr").each(function() {
						$this=$(this);
						$this.find('input[type="hidden"].report_detail_id').val(idFilter[x]);
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
		var table_id=$(this).parent().parent().parent().parent().attr('id');
		var url_data='';
		switch(table_id){
			case 'tbl_filter':
				url_data='<?php echo site_url("report_generator/delete_filter");?>';
			break;
		}

		var report_detail_id=$(this).parent().parent().find('td input[type="hidden"].report_detail_id').val();

		if(report_detail_id!=''){
			var confirm=window.confirm('Anda yakin ingin menghapus data ini?');
			if(confirm==true){
				deleteData(report_detail_id,url_data,this);				
			}
		}else{
			removeRow(this);
		}
	});

    var existingFilterType = $('.filter-type');
    $.each(existingFilterType, function(key,elem){
        var filterTypeId = $(elem).attr('id');
//        initDialog(filterTypeId);
        checkFilterType(filterTypeId);
        $(elem).change(function(){
            checkFilterType(filterTypeId);
        });
    });

});
</script>
<div id="main">

<div id="header" style="height:30px;text-align:center;color:#FFF">
    <div class="instansi" style="font-size:20px;margin:0 auto">
		Form Filter <i>Report</i>
    </div>
</div>

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
		
        echo form_open('report_generator/add_detail/', $attr);
	?>
		<div class="entry">
            <?php
				echo form_input($id_report_group);
				echo form_input($report_generator_id);
				echo form_label('ID <i>Report Group</i>');
				echo form_input($report_group_code);
			?>
			<br style="clear: both" />
			<?php
				echo form_label('Deskripsi Singkat');
				echo form_input($short_desc);
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
	        
			<div id="tab_detail">
                <ul>
                    <li><a href="#tabdetail-1"><b>Filter</b></a></li>
                </ul>
				<div id="tabdetail-1">
					<?php 
						$add_group = array(
			                'name' => 'btn_add_filter',
							'id'=>'btn_add_filter',
			                'class' => 'button-wrc',
			                'content' => 'Tambahkan');
						echo form_button($add_group);
					?>
					<table id="tbl_filter" class="report_generator" cellspacing="0" cellpadding="0" border="1">
						<thead>
							<tr>
							<th width="10px">No</th>
							<th width="120px">Nama Filter</th>
							<th width="140px">Nama Variable</th>
							<th width="140px">Tipe Filter</th>
							<th width="100px">Aksi</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$filter_types=json_decode($filter_type);
						$x=0;

                        foreach($report_filters['rows'] as $report_filter):
							
							$filterList='<option value="-1">-Pilih-</option>';
							foreach($filter_types as $key=>$value){
								if($report_filter->filter_type == $key){
									$selected=' selected="true" ';	
								}else{
									$selected="";
								}
                                $filterList .= '<option value ="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
						?>
                            <tr>
                                <td>
                                    <input type="number" class="filter-no" id="ReportFilterFilterNo_<?php echo $x;?>" name="data[ReportFilter][<?php echo $x;?>][filter_no]" value="<?php echo $report_filter->filter_no;?>"/>
                                </td>
                                <td>
                                    <input type="hidden" class="report_detail_id" name="data[ReportFilter][<?php echo $x;?>][id]" value="<?php echo $report_filter->id;?>"/>
                                    <input type="text" class="required filter-name" id="ReportFilterFilterName_<?php echo $x;?>" name="data[ReportFilter][<?php echo $x;?>][filter_name]" value="<?php echo $report_filter->filter_name;?>"/>
                                </td>
                                <td><input type="text" id="ReportFilterFilterVariable_<?php echo $x;?>" class="required filter-variable" name="data[ReportFilter][<?php echo $x;?>][filter_variable]" value="<?php echo $report_filter->filter_variable;?>" readonly="readonly"/></td>
                                <td>
                                    <select class="required-option filter-type" name="data[ReportFilter][<?php echo $x;?>][filter_type]" id="ReportFilterFilterType<?php echo $x;?>"><?php echo $filterList;?></select>
                                    <button type="button" class="btn-dialog-query" style="dislay:none;">Query</button>
<!--                                    <div id="ReportFilterDialogQuery--><?php //echo $x;?><!--" class="dialog-query">-->
                                        <input type="hidden" class="query-filter" name="data[ReportFilter][<?php echo $x;?>][query_filter]" value="<?php echo $report_filter->query_filter;?>">
<!--                                    </div>-->
                                </td>
                                <td><input type="button" value="Delete" class="btn_delete_detail ui-widget-content"/></td>
                            </tr>
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

</div>