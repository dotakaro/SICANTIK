<section class="title">
	<!-- We'll use $this->method to switch between download_list.create & download_list.edit -->
	<h4><?php echo lang('download_list:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
				<li>
				<label for="file_download">File Download
					<?php if (isset($file_download->data)): ?>
					<small>Current File: <?php echo $file_download->data->filename; ?></small>
					<?php endif; ?>
					</label>
				<div class="input"><?php echo form_upload('file_download', NULL, 'class="width-15"'); ?></div>
			</li><li>
		<label for="file_desc">File_desc</label>
		<div class="input">
		<?php echo form_input("file_desc", set_value("file_desc", $file_desc)); ?>
		</div>
		</li><li>
		<label for="published">Published</label>
		<div class="input">
		<?php echo form_dropdown("published", $list_published, set_value("published", $published)); ?>
		</div>
		</li>
		</ul>

	</div>

	<div class="buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

	<?php echo form_close(); ?>
</div>
</section>