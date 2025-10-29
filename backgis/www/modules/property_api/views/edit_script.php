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

<script type="text/javascript">
    var webroot='<?php echo base_url();?>';

    /*------------ BEGIN - Object Mapping ------------------*/
        var Mapping = {};//Object Mapping

        Mapping.numRowMapping = 0;
        /**
         * Fungsi untuk mendapatkan jumlah baris tabel Mapping
         */
        Mapping.getNumRowMapping = function(){
            return $('#listMapping tbody tr').length;
        };

        /**
         * Fungsi untuk menambahkan row pada tabel Mapping
         */
        Mapping.addRowMapping = function (){
            var numRowMapping = Mapping.numRowMapping;
            var hiddenTableNameId = 'mapping_'+numRowMapping+'_table_name';
            var htmlRow =
                '<tr>'+
                    '<td>'+
                        '<input type="text" required id="'+hiddenTableNameId+'" name="mapping['+numRowMapping+'][table_name]" class="combogrid-all-table">'+
                    '</td>'+
                    '<td>'+
                        '<input type="button" class="button-wrc btn-detail-mapping" value="Detail">'+
                        '<input type="button" id="mapping_'+numRowMapping+'_delete" class="button-wrc btn-del-mapping" value="Delete">'+
                        '<div class="dialog-detail">'+
                            '<input type="hidden" class="last-row-detail-mapping" value="0">'+
                            '<input type="hidden" class="detail-table-name '+hiddenTableNameId+'" value="0">'+
                            '<input type="button" id="mapping_'+numRowMapping+'_add_field" class="button-wrc" value="Tambah Field">'+
                            '<table class="list-detail-mapping" style="width:100%;">'+
                                '<thead>'+
                                    '<tr>'+
                                        '<td style="width:50%;">'+
                                            'Field Tabel'+
                                        '</td>'+
                                        '<td style="width:50%;">'+
                                            'Field API'+
                                        '</td>'+
                                        '</tr>'+
                                '</thead>'+
                                '<tbody>'+
                                '</tbody>'+
                            '</table>'+
                        '</div>'+
                    '</td>'+
                '</tr>';
            $('#listMapping').append(htmlRow);

            //Inisialisasi combo grid dan dialog untuk baris yang baru ditambahkan
            var elemCombogrid = $('#'+hiddenTableNameId);
            Mapping.initCombogridAndDialog(elemCombogrid);

            //Menghapus baris Mapping
            $('#mapping_'+numRowMapping+'_delete').click(function(){
                Mapping.deleteRowMapping($(this));
            });

            //Jika klik button add pada dialog detail, tambahkan baris detail mapping
            $('#mapping_'+numRowMapping+'_add_field').click(function(){
                Mapping._addRowFieldMapping(numRowMapping, $(this), hiddenTableNameId);
            });

            Mapping.numRowMapping++;
        }

        Mapping.deleteRowMapping = function(elemBtnDel){
            $(elemBtnDel).parent().parent().remove();
        }

        /**
         * Fungsi untuk inisialisasi combo grid dan dialog untuk satu row detail
         * @param elemCombogrid
         */
        Mapping.initCombogridAndDialog = function (elemCombogrid){
            var selector="#"+$(elemCombogrid).attr("id");
            $(selector).combogrid({
                searchButton:false,
                colModel: [{'columnName':'table_name','width':'45','label':'Nama Tabel','align':'left'}],
                url:webroot+'report_generator/combo_grid_tablename',
                width:"100px",
                //"select item" event handler to set input fields
                select: function( event, ui ) {
                    $( selector ).val( ui.item.table_name);
                    return false;
                }
            });

            //BEGIN - Create Dialog
            var detailDialog = $(elemCombogrid).parent().parent().find('.dialog-detail')
                .dialog({
                    autoOpen:false,
                    modal: true,
                    show:'blind',
                    hide:'blind',
                    title: 'Mapping Tabel ke Field API',
                    width: 500,
                    height: 300,
                    buttons: {
                        'Tutup': function() {
                            $(this).dialog('close');
                        }
                    }
                });
            detailDialog.parent().appendTo($("#formAPI"));//Agar data di dialog dapat dipost juga

            $(elemCombogrid).parent().parent().find('.btn-detail-mapping').click(function(){
                var txtTableNameId = $(elemCombogrid).attr('id');
                //isikan value ke input hidden dengan class dengan nama id textbox
                $('.'+txtTableNameId).val($(elemCombogrid).val());
                detailDialog.dialog('open');
            });
            //END - Create Dialog
        }

        /**
         * Fungsi untuk looping semua detail dan inisialisasi combo grid dan dialog modal
         */
        Mapping.initAllDetail = function (){
            $( ".combogrid-all-table").each(function(){
                var elemCombogrid = $(this);
                Mapping.initCombogridAndDialog(elemCombogrid);
            });
        }

        /**
         * Fungsi untuk inisialisasi combo grid field
         **/
        Mapping._initCombogridField = function(elemCombogrid, tableName){
            var otherParameters={
                tbl_name:tableName
            }
            $(elemCombogrid).combogrid({
                otherParam:otherParameters,
                colModel: [{'columnName':'column_name','width':'45','label':'Nama Field','align':'left'}],
                url:webroot+'report_generator/cg_reg_field',
                //"select item" event handler to set input fields
                select: function( event, ui ) {
                    $( elemCombogrid ).val( ui.item.column_name);
                    return false;
                }
            });
        }

        /**
         * Fungsi untuk inisialisasi combo grid field api
         **/
        Mapping._initCombogridFieldApi = function(elemCombogrid, apiId){
            $(elemCombogrid).combogrid({
                colModel: [{'columnName':'data_key','width':'45','label':'Nama Field API','align':'left'}],
                url:webroot+'property_api/combo_grid_field_api/'+apiId,
                //"select item" event handler to set input fields
                select: function( event, ui ) {
                    $( elemCombogrid ).val( ui.item.data_key);
                    return false;
                }
            });
        }

        /**
         * Fungsi untuk delete baris pada modal field mapping
         **/
        Mapping._deleteRowFieldMapping = function(elemBtnDel){
            $(elemBtnDel).parent().parent().remove();
        }

        Mapping._addRowFieldMapping = function(numRowMapping, elemBtnAddDetailMapping, hiddenTableNameId){
            var lastRowDetailMapping = $(elemBtnAddDetailMapping).parent().find('.last-row-detail-mapping');
            var numRowDetailMapping = lastRowDetailMapping.val();
            var txtFieldTableId = 'mapping_'+numRowMapping+'_detail_'+numRowDetailMapping+'_field_table';
            var txtFieldApiId = 'mapping_'+numRowMapping+'_detail_'+numRowDetailMapping+'_field_api';
            var tableDetailMapping = $(elemBtnAddDetailMapping).parent().find('table.list-detail-mapping');
            var tableName = $(elemBtnAddDetailMapping).parent().find('.detail-table-name').val();
            var htmlRowDetail =
                '<tr>'+
                    '<td>'+
                        '<input type="text" id="'+txtFieldTableId+'" name="mapping['+numRowMapping+'][detail]['+numRowDetailMapping+'][field_table]" class="combogrid-table-field">'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" id="'+txtFieldApiId+'" name="mapping['+numRowMapping+'][detail]['+numRowDetailMapping+'][field_api]" class="combogrid-table-field">'+
                    '</td>'+
                    '<td>'+
                        '<input type="button" id="mapping_'+numRowMapping+'_detail_'+numRowDetailMapping+'_delete" class="button-wrc btn-del-mapping" value="Delete">'+
                    '</td>'+
                '</tr>';
            tableDetailMapping.find('tbody').append(htmlRowDetail);

            //Inisialisasi combo grid field pada dialog
            var elemCombogridField = $('#'+txtFieldTableId);
            var tableName = $('#'+hiddenTableNameId).val();
            Mapping._initCombogridField(elemCombogridField, tableName);

            var apiId = $('#api_id').val();
            var elemCombogridFieldApi = $('#'+txtFieldApiId);
            Mapping._initCombogridFieldApi(elemCombogridFieldApi, apiId);

            //Menghapus baris Field Mapping
            $('#mapping_'+numRowMapping+'_detail_'+numRowDetailMapping+'_delete').click(function(){
                Mapping._deleteRowFieldMapping($(this));
            });

            numRowDetailMapping++;

            lastRowDetailMapping.val(numRowDetailMapping);
        }

    /*------------ END - Object Mapping ------------------*/

    $(document).ready(function() {
        function loadTree(apiId){
            $.ajax({
                url: webroot + 'property_api/get_html_tree/' + apiId,
                type: 'GET',
                dataType: 'html',
                success:function(htmlData){
                    $('#tree').html(htmlData);
                    generateTree($('#tree'));
                }
            });
        }

        function generateTree(elem){
            $(elem).dynatree("destroy").dynatree({
                minExpandLevel: 4
                // using default options
            });
        }

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
        $.validator.addMethod("required-hidden",
            function(value, element) {
                switch( element.nodeName.toLowerCase() ) {
                    case 'input':
                        // could be an array for select-multiple or a string, both are fine this way
                        var val = $(element).val();
                        return val && val.length > 0;
                }
            },
            "Anda belum mengambil Struktur API"
        );
        $('#form').validate();
        $("#tabs").tabs();

        <?php if($dataPropertyHierarchy->id) {?>
            loadTree(<?php echo $dataPropertyHierarchy->trapi_id; ?>);
        <?php }?>

        //BEGIN - Handle action load Struktur
        $('#btnLoad').click(function(){
            var isComplete = false;
            var apiId = $('#api_id').val();
            var urlApi = $('#api_url').val();
            var dataType = $('#data_type').val();
            var rootLevel = $('#root_level').val();
            if(urlApi == undefined || urlApi == ''){
                alert('Mohon isi URL API');
            }else if(dataType == undefined || dataType == ''){
                alert('Mohon isi Tipe Data');
            }else if(rootLevel == undefined || rootLevel == ''){
                alert('Mohon isi Level Struktur Dasar');
            }else{
                isComplete = true;
            }
            if(isComplete){//Jika URL API dan Tipe Data telah diisi
                //BEGIN - Mulai Load Data
                $.ajax({
                    url:webroot+'property_api/get_structure',
                    type:'POST',
                    data:{
                        'api_url':urlApi,
                        'data_type':dataType,
                        'api_id':apiId,
                        'root_level':rootLevel
                    },
                    dataType:'json',
                    success:function(r){
                        if(r.success != true){//Jika tidak berhasil load struktur
                            alert('Struktur Web Service tidak dapat dibaca. Mohon cek kembali URL dan Tipe Data');
                            $('#structure_loaded').val('');
                        }else{
                            $('#structure_loaded').val(1);
                            loadTree(apiId);
                        }
                    }
                });
                //END - Mulai Load Data
            }
        });
        //END - Handle action load Struktur

        $('#btnAddRowMapping').click(function(){
           Mapping.addRowMapping();
        });

        $('.btn-del-mapping').each(function(){
            var elemBtnDel = $(this);
            elemBtnDel.click(function(){
                Mapping.deleteRowMapping(elemBtnDel);
            });
        });

        Mapping.numRowMapping = Mapping.getNumRowMapping();
        Mapping.initAllDetail();
    });
</script>