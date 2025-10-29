$(document).ready(function() {
    $('#selector').change(function(){
        var value = $('#selector').val();
        oTable = $('#monitoring').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "bRetrieve" : true,
            "sAjaxSource": "/monitoring/selector/" + value,
            "fnServerData": function ( sSource, aoData, fnCallback ) {
                    $.ajax( {
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource,
                            "data": aoData,
                            "success": fnCallback
                    } );
            }

        });
    });
});