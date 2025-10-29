<section class="title">
	<!-- We'll use $this->method to switch between daftar_layanan.create & daftar_layanan.edit -->
	<h4><?php echo lang('daftar_layanan:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
		<div class="form_inputs">
			<ul class="fields">
			<li>
				<label for="jenis_izin"><?php echo lang('daftar_layanan:jenis_izin');?></label>
				<div class="input">
				<?php echo form_dropdown("jenis_izin", $list_izin, set_value("jenis_izin", $jenis_izin),'id="jenis_izin"'); ?>
				<?php echo form_hidden("nama_perizinan", set_value("nama_perizinan",$nama_perizinan)); ?>
				</div>
			</li>
			<li>
				<label for="file_download">File Download
				<?php if (isset($file_download)):
                    $file = Files::get_file($file_download);
                ?>
				<small>Current File:
                    <?php echo anchor('files/download/'.$file_download, $file['data']->name, '');?></small>
				<?php endif; ?>
				</label>
				<div class="input"><?php echo form_upload('file_download', NULL, 'class="width-15"'); ?></div>
			</li>
            <li>
                <label for="published">Type</label>
                <div class="input">
                    <?php echo form_dropdown("jenis_file", $list_jenis, set_value("jenis_file", $jenis_file)); ?>
                </div>
            </li>
			<li>
				<label for="file_desc">Description</label>
				<div class="input">
				<?php echo form_input("file_desc", set_value("file_desc", $file_desc)); ?>
				</div>
			</li>
			<li>
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