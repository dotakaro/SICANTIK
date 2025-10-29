<script type="text/javascript">
    var site = "<?php echo base_url();?>";
</script>
<section class="title">
	<!-- We'll use $this->method to switch between perizinan_online.create & perizinan_online.edit -->
	<h4><?php echo lang('perizinan_online:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
				<li>
		<label for="id_pemohon"><?php echo lang('perizinan_online:id_pemohon');?></label>
		<div class="input">
		<?php echo form_input("id_pemohon", set_value("id_pemohon", $id_pemohon)); ?>
		</div>
		</li><li>
		<label for="jenis_identitas"><?php echo lang('perizinan_online:jenis_identitas');?></label>
		<div class="input">
		<?php echo form_dropdown("jenis_identitas", $list_jenis_identitas,set_value("jenis_identitas", $jenis_identitas)); ?>
		</div>
		</li><li>
		<label for="telp_pemohon"><?php echo lang('perizinan_online:telp_pemohon');?></label>
		<div class="input">
		<?php echo form_input("telp_pemohon", set_value("telp_pemohon", $telp_pemohon)); ?>
		</div>
		</li><li>
		<label for="alamat_pemohon"><?php echo lang('perizinan_online:alamat_pemohon');?></label>
		<div class="input">
		<?php echo form_textarea("alamat_pemohon", set_value("alamat_pemohon", $alamat_pemohon)); ?>
		</div>
		</li><li>
		<label for="provinsi_pemohon"><?php echo lang('perizinan_online:provinsi_pemohon');?></label>
		<div class="input">
		<?php echo form_dropdown("provinsi_pemohon", $list_provinsi, set_value("provinsi_pemohon", $provinsi_pemohon), 'id="provinsi_pemohon"'); ?>
		</div>
		</li><li>
		<label for="kabupaten_pemohon"><?php echo lang('perizinan_online:kabupaten_pemohon');?></label>
		<div class="input">
		<?php echo form_dropdown("kabupaten_pemohon", $list_kabupaten, set_value("kabupaten_pemohon", $kabupaten_pemohon), 'id="kabupaten_pemohon"'); ?>
		</div>
		</li><li>
		<label for="kecamatan_pemohon"><?php echo lang('perizinan_online:kecamatan_pemohon');?></label>
		<div class="input">
		<?php echo form_dropdown("kecamatan_pemohon", $list_kecamatan, set_value("kecamatan_pemohon", $kecamatan_pemohon), 'id="kecamatan_pemohon"'); ?>
		</div>
		</li><li>
		<label for="kelurahan_pemohon"><?php echo lang('perizinan_online:kelurahan_pemohon');?></label>
		<div class="input">
		<?php echo form_dropdown("kelurahan_pemohon", $list_kelurahan, set_value("kelurahan_pemohon", $kelurahan_pemohon), 'id="kelurahan_pemohon"'); ?>
		</div>
		</li><li>
		<label for="npwp_perusahaan"><?php echo lang('perizinan_online:npwp_perusahaan');?></label>
		<div class="input">
		<?php echo form_input("npwp_perusahaan", set_value("npwp_perusahaan", $npwp_perusahaan)); ?>
		</div>
		</li><li>
		<label for="no_register_perusahaan"><?php echo lang('perizinan_online:no_register_perusahaan');?></label>
		<div class="input">
		<?php echo form_input("no_register_perusahaan", set_value("no_register_perusahaan", $no_register_perusahaan)); ?>
		</div>
		</li><li>
		<label for="nama_perusahaan"><?php echo lang('perizinan_online:nama_perusahaan');?></label>
		<div class="input">
		<?php echo form_input("nama_perusahaan", set_value("nama_perusahaan", $nama_perusahaan)); ?>
		</div>
		</li><li>
		<label for="alamat_perusahaan"><?php echo lang('perizinan_online:alamat_perusahaan');?></label>
		<div class="input">
		<?php echo form_textarea("alamat_perusahaan", set_value("alamat_perusahaan", $alamat_perusahaan)); ?>
		</div>
		</li><li>
		<label for="telepon_perusahaan"><?php echo lang('perizinan_online:telepon_perusahaan');?></label>
		<div class="input">
		<?php echo form_input("telepon_perusahaan", set_value("telepon_perusahaan", $telepon_perusahaan)); ?>
		</div>
		</li><li>
		<label for="provinsi_perusahaan"><?php echo lang('perizinan_online:provinsi_perusahaan');?></label>
		<div class="input">
		<?php echo form_dropdown("provinsi_perusahaan", $list_provinsi, set_value("provinsi_perusahaan", $provinsi_perusahaan), 'id="provinsi_perusahaan"'); ?>
		</div>
		</li><li>
		<label for="kabupaten_perusahaan"><?php echo lang('perizinan_online:kabupaten_perusahaan');?></label>
		<div class="input">
		<?php echo form_dropdown("kabupaten_perusahaan", $list_kabupaten, set_value("kabupaten_perusahaan", $kabupaten_perusahaan), 'id="kabupaten_perusahaan"'); ?>
		</div>
		</li><li>
		<label for="kecamatan_perusahaan"><?php echo lang('perizinan_online:kecamatan_perusahaan');?></label>
		<div class="input">
		<?php echo form_dropdown("kecamatan_perusahaan", $list_kecamatan, set_value("kecamatan_perusahaan", $kecamatan_perusahaan), 'id="kecamatan_perusahaan"'); ?>
		</div>
		</li><li>
		<label for="kelurahan_perusahaan"><?php echo lang('perizinan_online:kelurahan_perusahaan');?></label>
		<div class="input">
		<?php echo form_dropdown("kelurahan_perusahaan", $list_kelurahan, set_value("kelurahan_perusahaan", $kelurahan_perusahaan), 'id="kelurahan_perusahaan"'); ?>
		</div>
		</li><li>
		<label for="lampiran"><?php echo lang('perizinan_online:lampiran');?></label>
		<div class="input">
		<?php echo form_input("lampiran", set_value("lampiran", $lampiran)); ?>
		</div>
		</li><li>
		<label for="jenis_izin"><?php echo lang('perizinan_online:jenis_izin');?></label>
		<div class="input">
		<?php echo form_dropdown("jenis_izin", $list_izin, set_value("jenis_izin", $jenis_izin)); ?>
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