<h2 class="page-title" id="page_title"><?php echo lang('user:register_header') ?></h2>

<?php if ( ! empty($error_string)):?>
<!-- Woops... -->
<div class="coloralert" style="background: #ce1818;">	
	<?php echo $error_string;?>
	<a class="icon-text" href="#close-alert">✖</a>
</div>
<?php endif;?>


<div>
	<div class="block">
		<div class="block-title">
			<span id="active_step"><?php echo lang('user:register_step1') ?></span> -&gt;
			<span><?php echo lang('user:register_step2') ?></span>
		</div>
	
		<div class="block-content">
			<?php echo form_open('register', array('id' => 'register')) ?>
			
			
			<?php if ( ! Settings::get('auto_username')): ?>
				<label for="username"><?php echo lang('user:username') ?></label>
				<div class="input">
					<input type="text" name="username" maxlength="100" value="<?php echo $_user->username ?>" />
				</div>
				<br>
			<?php endif ?>
				<label for="email"><?php echo lang('global:email') ?></label>
				<div class="input">
					<input type="text" name="email" maxlength="100" value="<?php echo $_user->email ?>" />
				</div>
				<div class="input">
					<?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"') ?>
				</div>
				<br>
				<label for="password"><?php echo lang('global:password') ?></label>
				<div class="input">
					<input class="textInput" type="password" name="password" maxlength="100" />
				</div>
				<br>
				<?php foreach($profile_fields as $field) { if($field['required'] and $field['field_slug'] != 'display_name') { ?>
				<label for="<?php echo $field['field_slug'] ?>"><?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?></label>
				<div class="input"><?php echo $field['input'] ?></div>
				<br>
				<?php } } ?>
				<?php echo form_submit('', lang('profile_save_btn'),"class='button'") ?>
	
			<?php echo form_close() ?>
		</div>
	</div>	
</div>