<section class="title">
	<!-- We'll use $this->method to switch between dasar_hukum.create & dasar_hukum.edit -->
	<h4><?php echo lang('dasar_hukum:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
				<li>
				<label for="pdf_dasar_hukum">PDF Dasar Hukum
					<?php if (isset($pdf_dasar_hukum->data)): ?>
					<small>Current File: <?php echo $pdf_dasar_hukum->data->filename; ?></small>
					<?php endif; ?>
					</label>
				<div class="input"><?php echo form_upload('pdf_dasar_hukum', NULL, 'class="width-15"'); ?></div>
			</li><li>
		<label for="nama_dasar_hukum">Nama Dasar Hukum</label>
		<div class="input">
		<?php echo form_input("nama_dasar_hukum", set_value("nama_dasar_hukum", $nama_dasar_hukum)); ?>
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