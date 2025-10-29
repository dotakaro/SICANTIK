<script type="text/javascript" src="<?php echo BASE_URL?>addons/shared_addons/themes/agam_admin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL?>addons/shared_addons/themes/agam_admin/js/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript">
	var instance;

	function update_instance()
	{
		instance = CKEDITOR.currentInstance;
	}

	(function($) {
		$(function(){

			pyro.init_ckeditor = function(){
				<?php //echo $this->parser->parse_string(Settings::get('ckeditor_config'), $this, TRUE); ?>
				$('textarea.wysiwyg-simple').ckeditor({
					toolbar: [
					    ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
					    ['ShowBlocks', 'RemoveFormat', 'Source']
					],
					width: '99%',
					height: 100,
					dialog_backgroundCoverColor: '#000',
					defaultLanguage: 'en',
					language: 'en'
				});
				$('textarea.wysiwyg-advanced').ckeditor({
				    toolbar: [
				        ['Maximize'],
				        ['pyroimages', 'pyrofiles'],
				        ['CusDiv', 'TextColor', 'BGColor'],
				        ['Cut','Copy','Paste','PasteFromWord'],
				        ['Undo','Redo','-','Find','Replace'],
				        ['Link','Unlink'],
				        ['Table','HorizontalRule','SpecialChar'],
				        ['Bold','Italic','StrikeThrough'],
				        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'],
				        ['Format', 'FontSize', 'Subscript','Superscript', 'NumberedList','BulletedList','Outdent','Indent','Blockquote'],
				        ['ShowBlocks', 'RemoveFormat', 'Source']
				    ],
				    extraPlugins: 'pyroimages,pyrofiles,cusdiv,colorbutton',
				    width: '99%',
				    height: 400,
				    dialog_backgroundCoverColor: '#000',
				    removePlugins: 'elementspath',
				    defaultLanguage: 'en',
				    language: 'en'
				});
				pyro.init_ckeditor_maximize();
			};
			pyro.init_ckeditor();

		});
	})(jQuery);
</script>