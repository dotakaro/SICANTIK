<div class="block">
	<div class="block-title">
		<a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
		<h2>Pengaduan Perizinan Online</h2>
	</div>
	<div class="block-content">
            <?php echo $this->session->flashdata('success');?>
            <?php echo $this->session->flashdata('error');?>
            <?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

            <div class="form_inputs">

            <ul class="fields">
            <li>
            <label for="nama"><?php echo lang('pengaduan_online:nama');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_input("nama", set_value("nama", $nama),'required'); ?>
            </div>
            </li><li>
            <label for="nohp"><?php echo lang('pengaduan_online:nohp');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_input("nohp", set_value("nohp", $nohp),'required'); ?>
            </div>
            </li><li>
            <label for="alamat"><?php echo lang('pengaduan_online:alamat');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_textarea("alamat", set_value("alamat", $alamat), 'required'); ?>
            </div>
            </li><li>
            <label for="provinsi"><?php echo lang('pengaduan_online:provinsi');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_dropdown("provinsi", $list_provinsi, set_value("provinsi", $provinsi), 'id="provinsi" required'); ?>
            <?php echo form_hidden("provinsi_text", null); ?>
            </div>
            </li><li>
            <label for="kabupaten"><?php echo lang('pengaduan_online:kabupaten');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_dropdown("kabupaten", $list_kabupaten, set_value("kabupaten", $kabupaten), 'id="kabupaten" required'); ?>
            <?php echo form_hidden("kabupaten_text", null); ?>
            </div>
            </li><li>
            <label for="kecamatan"><?php echo lang('pengaduan_online:kecamatan');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_dropdown("kecamatan", $list_kecamatan, set_value("kecamatan", $kecamatan), 'id="kecamatan" required'); ?>
            <?php echo form_hidden("kecamatan_text", null); ?>
            </div>
            </li><li>
            <label for="kelurahan"><?php echo lang('pengaduan_online:kelurahan');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_dropdown("kelurahan", $list_kelurahan, set_value("kelurahan", $kelurahan), 'id="kelurahan" required'); ?>
            <?php echo form_hidden("kelurahan_text", null); ?>
            </div>
            </li>
            <li>
            <label for="deskripsi_pengaduan"><?php echo lang('pengaduan_online:deskripsi_pengaduan');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_textarea("deskripsi_pengaduan", set_value("deskripsi_pengaduan", $deskripsi_pengaduan),'required'); ?>
            </div>
            </li>
            </ul>
        </div>

        <div class="buttons">
            <?php 
            $btn_submit = array(
                'name' => 'btn_submit',
                'id' => 'btn_submit',
                'value' => 'true',
                'type' => 'submit',
                'content' => 'Simpan',
				'class'=>'button'
            );

            echo form_button($btn_submit);
            ?>
        </div>

        <?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
    var site = "<?php echo base_url();?>";
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>addons/shared_addons/modules/pengaduan_online/js/pengaduan_online.js"></script>
