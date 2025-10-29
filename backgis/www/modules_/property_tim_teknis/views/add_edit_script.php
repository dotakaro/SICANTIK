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
    .unit-kerja{
        width:400px;
    }
    label {
        width: auto;
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
    
    $('.unit-kerja').multiselect({
        show:'blind',
        hide:'blind',
        multiple: false,
        header: 'Pilih salah satu',
        noneSelectedText: 'Pilih salah satu',
        selectedList: 1
    }).multiselectfilter();
	
});
</script>