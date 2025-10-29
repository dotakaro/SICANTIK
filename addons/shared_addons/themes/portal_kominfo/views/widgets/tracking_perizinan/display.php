<form method="get" action="<?php echo site_url('perizinan_online/tracking_perizinan'); ?>">
    <fieldset>
		<h2 style="color: #c42b20;border-bottom: 2px solid #c42b20;" class="list-title">Status Permohonan</h2>
        <label for="no_pendaftaran">Untuk mengetahui status permohonan yang telah anda lakukan, masukan Nomor Pendaftaran anda : </label>
        <input type="text" name="no_pendaftaran" id="no_pendaftaran" placeholder="No Pendaftaran">
		<input type="submit" class="button" value="Lihat" style="margin-top:3px;">
    <fieldset>
</form>