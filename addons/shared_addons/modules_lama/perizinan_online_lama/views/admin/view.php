<?php //echo "<pre>";print_r($id_pemohon);exit();?>
<script type="text/javascript">
    var site = "<?php echo base_url();?>";
</script>
<section class="title">
	<!-- We'll use $this->method to switch between perizinan_online.create & perizinan_online.edit -->
	<h4><?php echo lang('perizinan_online:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php //echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
				<li>
		<label for="id_pemohon"><?php echo lang('perizinan_online:id_pemohon');?></label>
		<div class="input">
		<?php echo $id_pemohon; ?>
		</div>
		</li><li>
		<label for="jenis_identitas"><?php echo lang('perizinan_online:jenis_identitas');?></label>
		<div class="input">
		<?php echo $jenis_identitas; ?>
		</div>
		</li><li>
		<label for="telp_pemohon"><?php echo lang('perizinan_online:telp_pemohon');?></label>
		<div class="input">
		<?php echo $telp_pemohon; ?>
		</div>
		</li><li>
		<label for="alamat_pemohon"><?php echo lang('perizinan_online:alamat_pemohon');?></label>
		<div class="input">
		<?php echo $alamat_pemohon; ?>
		</div>
		</li><li>
		<label for="provinsi_pemohon"><?php echo lang('perizinan_online:provinsi_pemohon');?></label>
		<div class="input">
		<?php echo $provinsi_pemohon_text; ?>
		</div>
		</li><li>
		<label for="kabupaten_pemohon"><?php echo lang('perizinan_online:kabupaten_pemohon');?></label>
		<div class="input">
		<?php echo $kabupaten_pemohon_text; ?>
		</div>
		</li><li>
		<label for="kecamatan_pemohon"><?php echo lang('perizinan_online:kecamatan_pemohon');?></label>
		<div class="input">
		<?php echo $kecamatan_pemohon_text; ?>
		</div>
		</li><li>
		<label for="kelurahan_pemohon"><?php echo lang('perizinan_online:kelurahan_pemohon');?></label>
		<div class="input">
		<?php echo $kelurahan_pemohon_text; ?>
		</div>
		</li><li>
		<label for="npwp_perusahaan"><?php echo lang('perizinan_online:npwp_perusahaan');?></label>
		<div class="input">
		<?php echo $npwp_perusahaan; ?>
		</div>
		</li><li>
		<label for="no_register_perusahaan"><?php echo lang('perizinan_online:no_register_perusahaan');?></label>
		<div class="input">
		<?php echo $no_register_perusahaan; ?>
		</div>
		</li><li>
		<label for="nama_perusahaan"><?php echo lang('perizinan_online:nama_perusahaan');?></label>
		<div class="input">
		<?php echo $nama_perusahaan; ?>
		</div>
		</li><li>
		<label for="alamat_perusahaan"><?php echo lang('perizinan_online:alamat_perusahaan');?></label>
		<div class="input">
		<?php echo $alamat_perusahaan; ?>
		</div>
		</li><li>
		<label for="telepon_perusahaan"><?php echo lang('perizinan_online:telepon_perusahaan');?></label>
		<div class="input">
		<?php echo $telepon_perusahaan; ?>
		</div>
		</li><li>
		<label for="provinsi_perusahaan"><?php echo lang('perizinan_online:provinsi_perusahaan');?></label>
		<div class="input">
		<?php echo $provinsi_perusahaan_text; ?>
		</div>
		</li><li>
		<label for="kabupaten_perusahaan"><?php echo lang('perizinan_online:kabupaten_perusahaan');?></label>
		<div class="input">
		<?php echo $kabupaten_perusahaan_text; ?>
		</div>
		</li><li>
		<label for="kecamatan_perusahaan"><?php echo lang('perizinan_online:kecamatan_perusahaan');?></label>
		<div class="input">
		<?php echo $kecamatan_perusahaan_text; ?>
		</div>
		</li><li>
		<label for="kelurahan_perusahaan"><?php echo lang('perizinan_online:kelurahan_perusahaan');?></label>
		<div class="input">
		<?php echo $kelurahan_perusahaan_text; ?>
		</div>
		</li>
        <!--<li>
		<label for="lampiran"><?php echo lang('perizinan_online:lampiran');?></label>
		<div class="input">
		<?php echo $lampiran; ?>
		</div>
		</li>-->
		<li>
            <label for="jenis_izin"><?php echo lang('perizinan_online:jenis_izin');?></label>
            <div class="input">
            <?php echo $nama_perizinan; ?>
            </div>
		</li>
        <li>
            <label for="jenis_izin"><?php echo lang('perizinan_online:daerah');?></label>
            <div class="input">
                <?php echo $unit_kerja_text; ?>
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
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )); ?>
	</div>

	<?php //echo form_close(); ?>
</div>
</section>