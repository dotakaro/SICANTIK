<section class="title">
	<!-- We'll use $this->method to switch between link_website.create & link_website.edit -->
	<h4><?php echo lang('link_website:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
				<li>
		<label for="nama_link">Nama_link</label>
		<div class="input">
		<?php echo form_input("nama_link", set_value("nama_link", $nama_link)); ?>
		</div>
		</li><li>
		<label for="url_link">Url_link</label>
		<div class="input">
		<?php echo form_input("url_link", set_value("url_link", $url_link)); ?>
		</div>
		</li><li>
		<label for="desc_link">Desc_link</label>
		<div class="input">
		<?php echo form_textarea("desc_link", set_value("desc_link", $desc_link)); ?>
		</div>
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