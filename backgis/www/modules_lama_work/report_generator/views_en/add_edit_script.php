<style type="text/css">
	textarea{
		width:500px;
		height:50px;
		resize:none;
	}
	textarea#query_text{
		width:100%;
		height:100%;
	}
	#tbl_group{
		width:900px;
		margin-left:0;
	}
	.subreport-block{
		margin-top:5px;
		margin-bottom:5px;
		border:1px solid black;
		width:400px;
		padding:2px;
		margin-left:15em;
	}
	.btn-remove-subreport{
		float:right;
	}
</style>
<link href="<?php echo base_url();?>assets/css/ajaxfileupload/ajaxfileupload.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/ajaxfileupload.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	var webroot='<?php echo base_url();?>';
	var numRow=<?php echo isset($report_group_datas['num_rows']) ? $report_group_datas['num_rows'] : 0;?>;
	var numSubreport=<?php echo isset($report_subreports['num_rows']) ? $report_subreports['num_rows'] : 0;?>;

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
	
	$("#mainform").validate({
		submitHandler: function(form){
	   		// some other code
	   		// maybe disabling submit button
	   		// then:
	   		saveMainform();
		}
	});
	
    $("#tabs").tabs();
	
	var typeList='<option value="-1">-mohon pilih-</option>';
	var types=<?php echo $group_types;?>;
	$.each( types, function( key, value ) {
		typeList += '<option value ="'+key+'">'+value+'</option>';
	});
	
	var directQueryList='<option value="-1">-mohon pilih-</option>';
	var list=<?php echo $direct_query_list;?>;
	$.each( list, function( key, value ){
		directQueryList += '<option value ="'+key+'">'+value+'</option>';
	});
	
	$('.use_direct_query').live('change',function(){
		if( $(this).val()==0){
			$(this).next().attr('disabled',true);
			$(this).next().next();
			$(this).parent().next().find('.btn_detail').removeAttr('disabled');
		}else if( $(this).val()==1){
			$(this).parent().next().find('.btn_detail').attr('disabled',true);
			$(this).next().removeAttr('disabled');
		}else{
			$(this).next().attr('disabled',true);
			$(this).parent().next().find('.btn_detail').attr('disabled',true);
		}
	});
	
	var obj_direct_query;
	$("input[type=button].btn_query").live('click',function(){
		obj_direct_query=$(this).next();
		var query=obj_direct_query.val();
		var url_data='<?php echo site_url("report_generator/get_property_query");?>';
		$('#query_form textarea').val(query);
		$( "#query_form" ).dialog({
	        show : "scale",
	        hide : "scale",
	        autoOpen : false,
	        overlay : { opacity : 0.2 , background : "blue"},
	        height: 300,
	        width : 500,
	        modal: true ,
	        title : 'Direct Query',
	        closeOnEscape: true,
	        close : function(){
				var query = $('textarea[name="query_text"]').val();
				obj_direct_query.val(query);
			},
			buttons : {
				"Load from Property":function(){
					
					$.ajax({
						url:url_data,
						type:'POST',
						beforeSend:function(){
							$("#query_form").loadOverStart();
						},
						complete:function(){
							$("#query_form").loadOverStop();
						},			
						success:function(property_query){
							$("#query_form textarea").val(property_query);
						}
					});	
				},
				"OK":function(){
					$("#query_form").dialog("close");		
				}
			}			
		});
		$("#query_form").dialog("open");
	    return false;
	});	
	
/*	function loadDetailForm(id){
		var url_detail='<?php echo site_url('report_generator/detail');?>';
		$("#detail_form .layout").load(url_detail);
		$("#tab_detail").tabs();
	}*/

	function detailPopUp(id){
		var url="<?php echo site_url('report_generator/detail');?>"+'/'+id;
		var browserWidth=$(window).width();
		var browserHeight=$(window).height();
		var width=1024;
		var height=500;
		var screenX=50;
		var screenY=20;
		var left=(browserWidth-width)/2;
		var top=(browserHeight-height)/2;
		var w = (window.open(url, "", "status=no,width="+width+",height="+height+",screenX="+screenX+",screenY="+screenY+",top="+top+",left="+left+",resizable=no,scrollbars=yes", false));
		return false;
	}
	
	$("input[type=button].btn_detail").live('click',function(){
		var report_group_id = $(this).parent().parent().find('td input[type="hidden"].report_group_data_id').val();
		if(report_group_id==''){
			alert("Mohon simpan Report Group Data dahulu sebelum membuka Detail Form");
		}else{
			detailPopUp(report_group_id);
		}
		
//		detailPopUp();
		//$("#detail_form").dialog("open");
	    return false;
	});	
	
	function addNewRow(){
		var row='<tr>';
		row+='<td><input name="data[ReportGroupData][' + numRow + '][report_group_code]" class="required"/></td>';
		row+='<td><input name="data[ReportGroupData][' + numRow + '][short_desc]" style="width:100%"/></td>';
		row+='<td><select name="data[ReportGroupData][' + numRow + '][type]" class="required-option">'+typeList+'</select></td>';
		row+='<td><select name="data[ReportGroupData][' + numRow + '][use_direct_query]" class="use_direct_query required-option">'+directQueryList+'</select>';
		row+='<input type="button" class="btn_query ui-widget-content" value="Query" disabled="true"/>';
		row+='<input type="hidden" name="data[ReportGroupData][' + numRow + '][direct_query]" value=""/></td>';
		row+='<td><input type="button" class="btn_detail ui-widget-content" value="Detail" disabled="true"/>';
		row+='<input type="button" class="btn_delete ui-widget-content" value="Hapus"/>';
		row+='<input type="hidden" name="data[ReportGroupData][' + numRow + '][id]" class="report_group_data_id"/>';
		row+='</td>'+'</tr>';
		$("#tbl_group tbody").append(row);
		numRow++;
	}
	
	$("#btn_add").click(function(){
		addNewRow();
	});
	
	function deleteGroup(report_group_data_id,row){
		var url_data='<?php echo site_url("report_generator/delete_group");?>';
		var data={
			id:report_group_data_id
		};
		$.ajax({
			url:url_data,
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
	
    $(".btn_delete").live('click', function(){
		var report_group_data_id=$(this).parent().parent().find('td input[type="hidden"].report_group_data_id').val();

		if(report_group_data_id!=''){
			var confirm=window.confirm('Apakah anda yakin ingin menghapus data ini?')	
			if(confirm==true){
				deleteGroup(report_group_data_id,this);				
			}
		}else{
			removeRow(this);
		}
		
	});	
	
	function checkDirectQuery(){
		var result=true;
		var param_pattern=/^[^{}]+$/;
		$("#tbl_group > tbody > tr").each(function() {
			$this=$(this);
			var use_direct_query=$this.find('select[name*="use_direct_query"]').val();
			if(use_direct_query==1){
				var direct_query=$this.find('input[name*="direct_query"]').val();
				result = (param_pattern.test(direct_query));
			}
		});
		return result;
	}

	function saveMainform(){//Fungsi untuk menyimpan data report generator dan report group data
		if(checkDirectQuery()){//Jika sudah lolos pengecekan Direct Query 
			var url_data='<?php echo site_url("report_generator/save_mainform");?>';
			var data=$('#mainform').serialize();
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
						$("#mainform #ReportGeneratorId").val(response.id);
						var idGroupData=[];
						var x=0;
						if(typeof response.group_data!='undefined'){
							$.each( response.group_data, function(i,data_group) {
								idGroupData.push(data_group.id);
							});
							$("#tbl_group > tbody > tr").each(function() {
								$this=$(this);
								$this.find('input[type="hidden"].report_group_data_id').val(idGroupData[x]);
								x++;
							});
						}
					}else{
						alert('Data tidak tersimpan');
					}
				}
			});
		}else{
			alert('Mohon ganti {param} pada direct query dengan nilai atau variabel PHP');
		}
	}
	
	
	$("#upload_layout").click(function(){
		ajaxFileUpload();
	});
	
	$('[name^="btn_upload_subreport"]').live('click',function(e){
		e.preventDefault();
		ajaxFileUpload2($(this));
	});
	
	$("#download_layout").click(function(){
		var nama_layout=$(this).prev().val();
		if(nama_layout!=''){
			window.open(webroot +'report_generator/download_layout/'+nama_layout,'_blank');
		}else{
			alert('Mohon isi nama file layout.');
		}
	});
	
	$('[name^="btn_download_subreport"]').click(function(e){
		e.preventDefault();
		var nama_layout=$(this).prev().val();
		if(nama_layout!=''){
			window.open(webroot +'report_generator/download_layout/'+nama_layout,'_blank');
		}else{
			alert('Mohon isi nama file subreport.');
		}
	});
	
	function ajaxFileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
			$('input[type="submit"]').attr('disabled',true);
		})
		.ajaxComplete(function(){
			$(this).hide();
			$('input[type="submit"]').removeAttr('disabled');
		});

		$.ajaxFileUpload
		(
			{
				url:webroot + 'report_generator/upload_layout',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							alert(data.msg);
							$("#ReportGeneratorLayout").val(data.file);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}

	function ajaxFileUpload2(btn)
	{
		$(btn).next()
		.ajaxStart(function(){
			$(this).show();
			$('input[type="submit"]').attr('disabled',true);
		})
		.ajaxComplete(function(){
			$(this).hide();
			$('input[type="submit"]').removeAttr('disabled');
		});
		var tf_elem=$(btn).parent().find('input[type="text"]');
		var file_field_id=$(btn).prev().attr("id");
		var file_field_name=$(btn).prev().attr("name");
		$.ajaxFileUpload
		(
			{
				url:webroot + 'report_generator/upload_subreport_layout',
				secureuri:false,
				fileElementId:file_field_id,
				dataType: 'json',
				data:{name:'logan', id:'id',file_field:file_field_name},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							alert(data.msg);
							$(tf_elem).val(data.file);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}

	
	$('#btn_add_subreport').click(function(){	
		addSubreport();
	});
	
	$('input[name^="btn_remove"]').live('click',function(e){
		e.preventDefault();
		$(this).parent().fadeOut(500).remove();
	});
	
	function addSubreport(){
		var new_subreport='';
			new_subreport+='<div class="subreport-block">';
			new_subreport+='<input type="text" name="data[ReportSubreport]['+numSubreport+'][subreport_layout]" id="ReportSubreportLayout_'+numSubreport+'" class="input-wrc required"/>';
			new_subreport+='<input type="image" src="'+webroot+'assets/images/icon/navigation-down.png" id="btn_download_subreport_'+numSubreport+'" name="btn_download_subreport_'+numSubreport+'">';
			new_subreport+='<input type="image" src="'+webroot+'assets/images/icon/cross.png" id="btn_remove_'+numSubreport+'" name="btn_remove_'+numSubreport+'" class="btn-remove-subreport">';
			new_subreport+='<br style="clear: both" />';
			new_subreport+='<input id="fileSubreport'+numSubreport+'" type="file" name="fileSubreport'+numSubreport+'">';
			new_subreport+='<input id="btn_upload_subreport_'+numSubreport+'" type="image" name="btn_upload_subreport_'+numSubreport+'" src="'+webroot+'assets/images/icon/navigation.png">';
			
			new_subreport+='<img id="loading_subreport_'+numSubreport+'" style="display: none;" src="'+webroot+'assets/css/ajaxfileupload/loading.gif"></div>';
		$("#container_subreport").append(new_subreport);
		numSubreport++;
	}
});
</script>