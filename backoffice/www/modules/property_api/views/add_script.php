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
        $("#tabs").tabs();
    });
</script>