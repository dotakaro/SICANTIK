<section class="title">
	<!-- We'll use $this->method to switch between discussion.create & discussion.edit -->
	<h4><?php echo lang('discussion:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
				<li>
		<label for="topic">Topic</label>
		<div class="input">
		<?php echo form_textarea("topic", set_value("topic", $topic)); ?>
		</div>
		</li>
        <li>
            <label for="message_to">Message To</label>
            <div class="input">
            <?php echo form_dropdown("message_to", $list_users, set_value("message_to", $message_to)); ?>
            </div>
            <?php echo form_hidden("created_by", set_value("created_by", $current_user_id)); ?>
        </li>
			<!-- <li>
				<label for="fileinput">Fileinput
					<?php if (isset($fileinput->data)): ?>
					<small>Current File: <?php echo $fileinput->data->filename; ?></small>
					<?php endif; ?>
					</label>
				<div class="input"><?php echo form_upload('fileinput', NULL, 'class="width-15"'); ?></div>
			</li> -->
		</ul>

	</div>

	<div class="buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

	<?php echo form_close(); ?>
</div>
</section>