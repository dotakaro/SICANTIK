<div>
	<div class="block">
		<div class="block-title">
			<h2 class="page-title"><?php echo lang('user:reset_password_title');?></h2>
		</div>
		<div class="block-content">		
		
		<?php if(!empty($error_string)):?>
			<div class="coloralert" style="background: #ce1818;">
				<p><?php echo $error_string;?></p>
				<a class="icon-text" href="#close-alert">✖</a>
			</div>
		<?php endif;?>
		
		<?php if(!empty($success_string)): ?>
			<div class="coloralert" style="background: #61aa21;">
				<p><?php echo $success_string ?></p>
				<a class="icon-text" href="#close-alert">✖</a>
			</div>
		<?php else: ?>
			
			<?php echo form_open('users/reset_pass', array('id'=>'reset-pass')) ?>

			<label for="email"><?php echo lang('user:reset_instructions') ?></label>
			<input type="text" name="email" maxlength="100" value="<?php echo set_value('email') ?>" />
			<br>
			<input class="button" style="background: #93d65e" type="submit" value="{{ helper:lang line='user:login_btn' }}" name="btnLogin" />			
			<?php echo form_close() ?>		
		<?php endif ?>
		</div>
	</div>	
		
</div>