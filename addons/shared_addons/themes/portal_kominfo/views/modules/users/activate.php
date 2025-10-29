<div>
	<div class="block">
		
		<h2 class="page-title" id="page_title"><?php echo lang('user:register_header') ?></h2>
		<div class="block-title">
			<span id="active_step"><?php echo lang('user:register_step1') ?></span> &gt;
			<span><?php echo lang('user:register_step2') ?></span>
		</div>
		<div class="block-content">		
			
			<?php if(!empty($error_string)):?>
				<div class="coloralert" style="background: #ce1818;">
					<p><?php echo $error_string;?></p>
					<a class="icon-text" href="#close-alert">✖</a>
				</div>
			<?php endif;?>
						
			<?php echo form_open('users/activate', 'id="activate-user"') ?>
				<label for="email"><?php echo lang('global:email') ?></label>
				<?php echo form_input('email', isset($_user['email']) ? $_user['email'] : '', 'maxlength="40"');?>
				<br>
				<label for="activation_code"><?php echo lang('user:activation_code') ?></label>
				<?php echo form_input('activation_code', '', 'maxlength="40"');?>
				<br>
				<input class="button" style="background: #93d65e" type="submit" value="{{ helper:lang line='user:login_btn' }}" name="btnLogin" />			
			
			<?php echo form_close() ?>
		</div>
	</div>			
</div>