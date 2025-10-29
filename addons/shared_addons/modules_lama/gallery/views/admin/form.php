<section class="title">
	<!-- We'll use $this->method to switch between gallery.create & gallery.edit -->
	<h4><?php echo lang('gallery:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

		<ul class="fields">
			<li>
			<label for="gallery_file">Gallery File
				<?php if (isset($gallery_file)): ?>
				<small>Current File: <?php echo img('files/thumb/'.$gallery_file.'/100x100/fit');?></small>
				<?php endif; ?>
				</label>
			<div class="input"><?php echo form_upload('gallery_file', NULL, 'class="width-15"'); ?></div>
			</li>
			<li>
		<label for="gallery_desc">Gallery_desc</label>
		<div class="input">
		<?php echo form_textarea("gallery_desc", set_value("gallery_desc", $gallery_desc)); ?>
		</div>
		</li><li>
		<label for="published">Published</label>
		<div class="input">
		<?php echo form_dropdown("published", $list_published, set_value("published", $published)); ?>
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