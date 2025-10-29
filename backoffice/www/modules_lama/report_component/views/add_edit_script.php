<style type="text/css">
	textarea{
		width:490px !important;
		height:50px;
		resize:none;
	}
	input[type="text"]{
		width:490px;
	}
	textarea#query_text{
		width:100%;
		height:100%;
	}
	#tbl_group{
		width:900px;
		margin-left:0;
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
	);

    $('#ReportGeneratorTrunitkerjaId').multiselect({
        show:'blind',
        hide:'blind',
        multiple: false,
        header: 'Pilih salah satu',
        noneSelectedText: 'Pilih salah satu',
        selectedList: 1
    }).multiselectfilter();

	$("#mainform").validate({
		submitHandler: function(form){
	   		// some other code
	   		// maybe disabling submit button
	   		// then:
	   		saveMainform();
		}
	});
	
    $("#tabs").tabs();

	function saveMainform(){//Fungsi untuk menyimpan data report generator dan report group data
		var url_data='<?php echo site_url("report_component/save_mainform");?>';
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
					/*$("#report_component #ReportGeneratorId").val(response.id);
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
					}*/
				}else{
					if(response.message != undefined && response.message !=''){
						alert(response.message);
					}else{
						alert('Data tidak tersimpan');
					}
				}
			}
		});
	}
	
});
</script>