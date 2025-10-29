<div>
	<?php if (validation_errors()): ?>
	<div class="coloralert" style="background: #ce1818;">
		<?php echo validation_errors();?>
		<a class="icon-text" href="#close-alert">✖</a>
	</div>
	<?php endif ?>

	<div class="block">
		<div class="block-title">
			<h2 class="page-title" id="page_title"><?php echo lang('user:login_header') ?></h2>
		</div>
		<div class="block-content">		
			<?php echo form_open('users/login', array('id'=>'login','class'=>'login'), array('redirect_to' => $redirect_to)) ?>
			
			email &nbsp;<input class ="textInput" type="text" id="email" name="email" maxlength="120" placeholder="{{ helper:lang line="global:email" }}" style="display:inline" />
			<br>
			<br>sandi &nbsp;<input class ="textInput" type="password" id="password" name="password" maxlength="20" placeholder="{{ helper:lang line="global:password" }}" style="display:inline"/>
			<br>
			<br>
			<input class="button" style="background: #93d65e" type="submit" value="{{ helper:lang line='user:login_btn' }}" name="btnLogin" />
			<br><br>
			<?php echo anchor('register', lang('user:register_btn'));?>
			|
			<?php echo anchor('users/reset_pass', lang('user:reset_password_link'));?>
			

			
			
			<?php echo form_close() ?>
		</div>
	</div>
</div>