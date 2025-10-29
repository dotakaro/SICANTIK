<div class="block">
	<div class="block-title">
		<a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
		<h2>Pendaftaran Online</h2>
	</div>

    <?php if (validation_errors()): ?>
        <div class="error-box">
            <?php echo validation_errors();?>
        </div>
    <?php endif ?>

    <div class="block-content">
		<?php echo $this->session->flashdata('success');?>
		<?php echo $this->session->flashdata('error');?>
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud" id="form_pendaftaran"'); ?>

		<div class="form_inputs">

		<ul class="fields">
		<li class="header">Data Pemohon</li>
        <li>
            <label for="jenis_identitas"><?php echo lang('perizinan_online:jenis_identitas');?><span class="mandatory">*</span></label>
            <div class="input">
                <?php echo form_dropdown("jenis_identitas", $list_jenis_identitas,set_value("jenis_identitas", $jenis_identitas),'required'); ?>
            </div>
        </li>
        <li>
		<label for="id_pemohon"><?php echo lang('perizinan_online:id_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_input("id_pemohon", set_value("id_pemohon", $id_pemohon), 'required'); ?>
		</div>
		</li>
        <li>
		<label for="nama_pemohon"><?php echo lang('perizinan_online:nama_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_input("nama_pemohon", set_value("nama_pemohon", $nama_pemohon),'required'); ?>
		</div>
		</li><li>
		<label for="telp_pemohon"><?php echo lang('perizinan_online:telp_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_input("telp_pemohon", set_value("telp_pemohon", $telp_pemohon),'required'); ?>
		</div>
		</li><li>
		<label for="alamat_pemohon"><?php echo lang('perizinan_online:alamat_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_textarea("alamat_pemohon", set_value("alamat_pemohon", $alamat_pemohon), 'required'); ?>
		</div>
		</li><li>
		<label for="provinsi_pemohon"><?php echo lang('perizinan_online:provinsi_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_dropdown("provinsi_pemohon", $list_provinsi, set_value("provinsi_pemohon", 3), 'id="provinsi_pemohon" required'); ?>
        <?php echo form_hidden("provinsi_pemohon_text", null); ?>
        </div>
		</li><li>
		<label for="kabupaten_pemohon"><?php echo lang('perizinan_online:kabupaten_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_dropdown("kabupaten_pemohon", $list_kabupaten, set_value("kabupaten_pemohon", 43), 'id="kabupaten_pemohon" required'); ?>
        <?php echo form_hidden("kabupaten_pemohon_text", null); ?>
        </div>
		</li><li>
		<label for="kecamatan_pemohon"><?php echo lang('perizinan_online:kecamatan_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_dropdown("kecamatan_pemohon", $list_kecamatan, set_value("kecamatan_pemohon", $kecamatan_pemohon), 'id="kecamatan_pemohon" required'); ?>
        <?php echo form_hidden("kecamatan_pemohon_text", null); ?>
        </div>
		</li><li>
		<label for="kelurahan_pemohon"><?php echo lang('perizinan_online:kelurahan_pemohon');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php echo form_dropdown("kelurahan_pemohon", $list_kelurahan, set_value("kelurahan_pemohon", $kelurahan_pemohon), 'id="kelurahan_pemohon" required'); ?>
        <?php echo form_hidden("kelurahan_pemohon_text", null); ?>
        </div>
		</li>
		<li class="header">Data Perusahaan</li>
		<li>
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
		<?php echo form_dropdown("provinsi_perusahaan", $list_provinsi, set_value("provinsi_perusahaan", 3), 'id="provinsi_perusahaan"'); ?>
        <?php echo form_hidden("provinsi_perusahaan_text", null); ?>
        </div>
		</li><li>
		<label for="kabupaten_perusahaan"><?php echo lang('perizinan_online:kabupaten_perusahaan');?></label>
		<div class="input">
		<?php echo form_dropdown("kabupaten_perusahaan", $list_kabupaten, set_value("kabupaten_perusahaan", 43), 'id="kabupaten_perusahaan"'); ?>
        <?php echo form_hidden("kabupaten_perusahaan_text", null); ?>
        </div>
		</li><li>
		<label for="kecamatan_perusahaan"><?php echo lang('perizinan_online:kecamatan_perusahaan');?></label>
		<div class="input">
		<?php echo form_dropdown("kecamatan_perusahaan", $list_kecamatan, set_value("kecamatan_perusahaan", $kecamatan_perusahaan), 'id="kecamatan_perusahaan"'); ?>
        <?php echo form_hidden("kecamatan_perusahaan_text", null); ?>
        </div>
		</li><li>
		<label for="kelurahan_perusahaan"><?php echo lang('perizinan_online:kelurahan_perusahaan');?></label>
		<div class="input">
		<?php echo form_dropdown("kelurahan_perusahaan", $list_kelurahan, set_value("kelurahan_perusahaan", $kelurahan_perusahaan), 'id="kelurahan_perusahaan"'); ?>
        <?php echo form_hidden("kelurahan_perusahaan_text", null); ?>
        </div>
		</li>
		<li class="header">Data Perizinan</li>
		<li>
            <label for="jenis_izin"><?php echo lang('perizinan_online:jenis_izin');?><span class="mandatory">*</span></label>
            <div class="input">
            <?php echo form_dropdown("jenis_izin", $list_izin, set_value("jenis_izin", $jenis_izin),'id="jenis_izin" required style="width:100%"'); ?>
            <?php echo form_hidden("nama_perizinan", null); ?>
            </div>
		</li>
        <li>
            <label for="unit_kerja_id"><?php echo lang('perizinan_online:daerah');?><span class="mandatory">*</span></label>
            <div class="input">
                <?php echo form_dropdown("unit_kerja_id", $list_unit_kerja, set_value("unit_kerja_id", $unit_kerja_id), 'id="unit_kerja_id" required'); ?>
                <?php echo form_hidden("unit_kerja_text", null); ?>
            </div>
        </li>
        <li>
           <br><br><span id="capcha">
           </span>
           <img src="<?php echo base_url() . '/assets/css/default/icon/reset.png' ?>" title="Reload" class="klic" onclick="reload_get()">
            <br><em>isi textbox sesuai dengan captcha yang anda lihat pada gambar diatas </em>
            &nbsp;&nbsp;<input type="text" id="isi_capca2" name="isi_capca2" style="width: 200px">
            <span id="isi_capca_error" style="color: red;"> </span>
        </li>
        <!--<li>
		<label for="lampiran"><?php echo lang('perizinan_online:lampiran');?><span class="mandatory">*</span></label>
		<div class="input">
		<?php //echo form_upload("lampiran", null, 'required'); ?>
		</div>
		</li>-->
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
    var captcha_error = 0;
    function reload_get(){
        $("#capcha").load(site+'perizinan_online/get_capcha');
    }
    $(document).ready(function() {
        //ini config tiny
        $("#form_pendaftaran").submit(function(e){
            e.preventDefault();
            captcha_error = 0;
            if ($("#isi_capca").val() != $("#isi_capca2").val()){
                $("#isi_capca_error").html("<p>Data Tidak Sama Dengan Gambar</p>");
                $("#capcha").load(site+'perizinan_online/get_capcha');
                captcha_error = 1;
            }
            if (captcha_error == 0){
                $("#form_pendaftaran").unbind('submit').submit();
                return true;
            }
            return false;
        });
        $("#capcha").load(site+'perizinan_online/get_capcha');
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>addons/shared_addons/modules/perizinan_online/js/perizinan_online.js"></script>
