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

});
</script>