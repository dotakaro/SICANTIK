<style type="text/css">
	textarea{
		width:100% !important;
		height:50px;
		resize:none;
	}
	/*input[type="text"]{
		width:490px;
	}*/
	textarea#query_text{
		width:100%;
		height:100%;
	}
	#tbl_group{
		width:900px;
		margin-left:0;
	}

</style>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/ckeditor/adapters/jquery.js"></script>
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

	$("#mainform").validate({
		submitHandler: function(form){
	   		// some other code
	   		// maybe disabling submit button
	   		// then:
	   		saveMainform();
		}
	});

    $('.penerima-lain').inputosaurus({
        width : '550px'
    });

    $(".tabs-horizontal").tabs();
    $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );

	function saveMainform(){//Fungsi untuk menyimpan data report generator dan report group data
		var url_data='<?php echo site_url("notification_setting/save_ajax");?>';
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
					if(response.is_new==true){//Jika mode add, redirect ke edit
                        window.location = '<?php echo site_url("notification_setting/edit");?>/'+response.setting_notifikasi_id;
                    }
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
    $( 'textarea.message-format' ).ckeditor();
});
</script>

<style>
    .ui-tabs-vertical { width: 110em; }
    .ui-tabs-vertical > .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 35em; }
    .ui-tabs-vertical > .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
    .ui-tabs-vertical > .ui-tabs-nav li > a { display:block; width:100%;}
    .ui-tabs-vertical > .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; border-right-width: 1px; }
    .ui-tabs-vertical > .ui-tabs-panel { padding: 1em; float: left; width:70em;}
</style>