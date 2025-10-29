<section class="title">
	<!-- We'll use $this->method to switch between pegawai.create & pegawai.edit -->
	<h4><?php echo lang('pegawai:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
				<li>
		<label for="nama_pegawai">Nama_pegawai</label>
		<div class="input">
		<?php echo form_input("nama_pegawai", set_value("nama_pegawai", $nama_pegawai)); ?>
		</div>
		</li><li>
		<label for="nip">Nip</label>
		<div class="input">
		<?php echo form_input("nip", set_value("nip", $nip)); ?>
		</div>
		</li><li>
		<label for="jabatan">Jabatan</label>
		<div class="input">
		<?php echo form_input("jabatan", set_value("jabatan", $jabatan)); ?>
		</div>
		</li><li>
		<label for="alamat">Alamat</label>
		<div class="input">
		<?php echo form_textarea("alamat", set_value("alamat", $alamat)); ?>
		</div>
		</li><li>
		<label for="tempat_lahir">Tempat_lahir</label>
		<div class="input">
		<?php echo form_input("tempat_lahir", set_value("tempat_lahir", $tempat_lahir)); ?>
		</div>
		</li><li>
		<label for="tgl_lahir">Tgl_lahir</label>
		<div class="input">
		<?php echo form_input("tgl_lahir", set_value("tgl_lahir", $tgl_lahir),'id="tgl_lahir"'); ?>
		</div>
		</li><li>
		<label for="no_telp">No_telp</label>
		<div class="input">
		<?php echo form_input("no_telp", set_value("no_telp", $no_telp)); ?>
		</div>
		</li><li>
		<label for="pendidikan">Pendidikan</label>
		<div class="input">
		<?php echo form_input("pendidikan", set_value("pendidikan", $pendidikan)); ?>
		</div>
		</li><li>
				<label for="fileinput">Foto
					<?php if (isset($foto)): ?>
					<small>Current File: <?php echo img('files/thumb/'.$foto.'/80x120/fit');?></small>
					<?php endif; ?>
					</label>
				<div class="input"><?php echo form_upload('foto', NULL, 'class="width-15"'); ?></div>
			</li>
            <li>
                <label for="pendidikan">No Urut</label>
                <div class="input">
                    <?php echo form_input("order", set_value("order", $order)); ?>
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