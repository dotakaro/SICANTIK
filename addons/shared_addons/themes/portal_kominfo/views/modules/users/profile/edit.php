<h2 id="page_title" class="page-title">
	<?php echo ($this->current_user->id !== $_user->id) ?
					sprintf(lang('user:edit_title'), $_user->display_name) :
					lang('profile_edit') ?>
</h2>
<div>
	<?php if (validation_errors()):?>
	<div class="coloralert" style="background: #ce1818;">
		<?php echo validation_errors();?>
		<a class="icon-text" href="#close-alert">✖</a>
	</div>
	<?php endif;?>

	<?php echo form_open_multipart('', array('id'=>'user_edit'));?>
	
	
	<div class="block">
		<div class="block-title">
			<h2><?php echo lang('user:details_section') ?></h2>
		</div>
		<div class="block-content">
			<label for="display_name"><?php echo lang('profile_display_name') ?></label>
			<div class="input">
				<?php echo form_input(array('name' => 'display_name', 'id' => 'display_name', 'value' => set_value('display_name', $display_name))) ?>
			</div>
			<?php foreach($profile_fields as $field): ?>
				<?php if($field['input']): ?>
					<br>
					<label for="<?php echo $field['field_slug'] ?>">
						<?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?>
						<?php if ($field['required']) echo '<span>*</span>' ?>
					</label>
					<?php if($field['instructions']) echo '<p class="instructions">'.$field['instructions'].'</p>' ?>
					<div class="input">
						<?php echo $field['input'] ?>
					</div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	</div>

	<div class="block">
		<div class="block-title">
			<h2><?php echo lang('global:email') ?></h2>
		</div>
		<div class="block-content">
			<label for="email"><?php echo lang('global:email') ?></label>
			<div class="input">
				<?php echo form_input('email', $_user->email) ?>
			</div>
		</div>
	</div>
	
	<div class="block">
		<div class="block-title">
			<h2><?php echo lang('user:password_section') ?></h2>
		</div>
		<div class="block-content">
			<label for="password"><?php echo lang('global:password') ?></label><br/>
			<?php echo form_password('password', '', 'autocomplete="off"') ?>			
		</div>
	</div>
	
	<?php if (Settings::get('api_enabled') and Settings::get('api_user_keys')): ?>
		
	<script>
	jQuery(function($) {
		
		$('input#generate_api_key').click(function(){
			
			var url = "<?php echo site_url('api/ajax/generate_key') ?>",
				$button = $(this);
			
			$.post(url, function(data) {
				$button.prop('disabled', true);
				$('span#api_key').text(data.api_key).parent('li').show();
			}, 'json');
			
		});
		
	});
	</script>
	
	<div class="block">
		<div class="block-title">
			<h2><?php echo lang('profile_api_section') ?></h2>
		</div>
		<div class="block-content">
			<?php $api_key or print('style="display:none"') ?>><?php echo sprintf(lang('api:key_message'), '<span id="api_key">'.$api_key.'</span>') ?>
			<br>
			<input class="button" type="button" id="generate_api_key" value="<?php echo lang('api:generate_key') ?>" />
		</div>
	</div>
	<?php endif ?>

	<?php echo form_submit('', lang('profile_save_btn'),"class='button'") ?>
	<?php echo form_close() ?>
</div>