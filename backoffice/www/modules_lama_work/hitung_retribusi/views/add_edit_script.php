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

    function init_validation(){
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
    }

    function unbind_validation(){
        $("#form").data('validator', null);
    }

    init_validation();

    function calculate_total(){
        var formula_total = 0;
        /*$('[id*="subtotal"]').each(function(key, value){
            total += parseFloat($(value).val());
        });*/
        <?php echo $formula_retribusi;?>
        if(isNaN(formula_total) || formula_total<0){
            formula_total = 0;
        }
        $('#nilai_retribusi').val(formula_total);
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

    $('input[name^="jenis_perhitungan"]').change(function(){
        var jenis_perhitungan = $(this).val();
        var txt_nilai_retribusi = $('#nilai_retribusi');
        var row_item_tarif = $('tbody tr.item-tarif');
        var input_item_tarif = $('tbody tr.item-tarif td select, tbody tr.item-tarif td input');
        switch(jenis_perhitungan){
            case 'manual':
                txt_nilai_retribusi.removeAttr('readonly');
                input_item_tarif.attr('disabled',true);
                row_item_tarif.hide();
                unbind_validation();
                break;
            default:
                txt_nilai_retribusi.attr('readonly',true);
                input_item_tarif.removeAttr('disabled');
                row_item_tarif.show();
                init_validation();
                break;
        }
    });
});
</script>

<?php
function remove_whitespace($string){
    return preg_replace("/\s+/", "",$string );
}
?>