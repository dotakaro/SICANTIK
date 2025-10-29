<style type="text/css">
	textarea{
		width:490px !important;
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
		
</style>

<script type="text/javascript">
$(document).ready(function() {
    var numRow = 0;
    numRow = $('#tbl_group tbody tr').length;
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

    function addNewRow(){
            var row='<tr>';
            row+='<td><input name="SettingTarifHarga[' + numRow + '][kategori]" class="required" style="width:100%"/></td>';
            row+='<td><input name="SettingTarifHarga[' + numRow + '][harga]" class="required" style="width:100%"/></td>';
            row+='<td><input type="button" class="btn_delete ui-widget-content" value="Hapus"/>';
            row+='<input type="hidden" name="SettingTarifHarga[' + numRow + '][id]" class="setting_tarif_harga_id"/>';
            row+='</td>'+'</tr>';
            $("#tbl_group tbody").append(row);
            numRow++;
    }

    $("#btn_add").click(function(){
            addNewRow();
    });
	
    $("#tabs").tabs();
    
    function removeRow(buttonElement){
        $(buttonElement).parent().parent().remove();
    }
    
    function delete_kategori(setting_tarif_harga_id, buttonElement){
        $.ajax({
           url : webroot + 'setting_tarif/delete_kategori',
           type: 'POST',
           dataType:'json',
           data:{id_kategori : setting_tarif_harga_id},
           success:function(r){
               if(r.success != true){
                   alert(r.message);
               }else{
                   removeRow(buttonElement);
               }
           } 
        });
    }
	
    $(".btn_delete").live('click', function(){
        var setting_tarif_harga_id=$(this).parent().parent().find('td input[type="hidden"].setting_tarif_harga_id').val();

        if(setting_tarif_harga_id!=''){
                var confirm=window.confirm('Apakah anda yakin ingin menghapus data ini?')	
                if(confirm==true){
                    delete_kategori(setting_tarif_harga_id,this);				
                }
        }else{
                removeRow(this);
        }
    });
    
    $('#trperizinan_id').multiselect({
        show:'blind',
        hide:'blind',
        multiple: false,
        header: 'Pilih salah satu',
        noneSelectedText: 'Pilih salah satu',
        selectedList: 1
    }).multiselectfilter();
	
});
</script>