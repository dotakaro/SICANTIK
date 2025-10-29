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
    .amount{
        text-align: right;
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

    $("#form").validate();

    function calculate_total(){
        var total = 0;
        $('[id*="subtotal"]').each(function(key, value){
            total += parseFloat($(value).val());
        });
        if(isNaN(total) || total<0){
            total = 0;
        }
        $('#nilai_retribusi').val(total);
    }

    //Hitung jika ada perubahan pada item tarif (change)
    $('select[id*="tarif_kategori"]').change(function(e){ //Detect perubahan Kategori Dropdown
        var item_value = parseFloat($(this).val());
        var item_qty = parseInt($(this).parent().next().find('[id*="jumlah"]').val());
        var subtotal = item_value * item_qty;

        if(isNaN(subtotal) || subtotal < 0){
           subtotal = 0;
        }
        $(this).parent().next().next().find('[id*="subtotal"]').val(subtotal);
        calculate_total();
    });
    $('input[id*="tarif_kategori"]').keyup(function(e){ //Detect perubahan Kategori Input Manual
        var item_value = parseFloat($(this).val());
        var item_qty = parseInt($(this).parent().next().find('[id*="jumlah"]').val());
        var subtotal = item_value * item_qty;

        if(isNaN(subtotal) || subtotal < 0){
            subtotal = 0;
        }
        $(this).parent().next().next().find('[id*="subtotal"]').val(subtotal);
        calculate_total();
    });
    $('input[id*="jumlah"]').keyup(function(e){ //Detect perubahan pada jumlah
        var item_value = parseFloat($(this).parent().prev().find('[id*="tarif_kategori"]').val());
        var item_qty = parseInt($(this).val());
        var subtotal = item_value * item_qty;

        if(isNaN(subtotal) || subtotal < 0){
            subtotal = 0;
        }
        $(this).parent().next().find('[id*="subtotal"]').val(subtotal);
        calculate_total();
    });
});
</script>