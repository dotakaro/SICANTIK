<?php
if($list[0]['no_izin'] != "Ditolak" && !empty($list[0]['no_izin'])){
?>
<div class="block">
	<div class="block-title">
<center><h2 class="verified">* IZIN TERDAFTAR *</h2></center>
	</div>
	<div class="block-content">
<p>Nomor Izin <b><?php echo $list[0]['no_izin'];?></b> telah terdaftar pada Dinas Penanaman Modal dan Pelayanan Perizinan Terpadu Satu Pintu pada tanggal <b><?php echo date('d F Y', strtotime($list[0]['tgl_surat']));?></b> dengan data sebagai berikut : </p>
<table width="100%">
    <tbody>
    <tr>
        <td>Jenis Perizinan  </td>
        <td>: </td>
        <td><b><?php echo $list[0]['nama_perizinan'];?></b></td>
    </tr>
	<tr>
        <td>Nama Pemohon  </td>
        <td>: </td>
        <td><b><?php echo $list[0]['nama_pemohon'];?></b></td>
    </tr>
	<tr>
        <td>Alamat Pemohon  </td>
        <td>: </td>
        <td><b><?php echo $list[0]['alamat_pemohon'];?></b></td>
    </tr>
	<tr>
        <td>Nama Perusahaan  </td>
        <td>: </td>
        <td><b><?php echo $list[0]['n_perusahaan'];?></b></td>
    </tr>
	<tr>
	   <td>Alamat Perusahaan  </td>
        <td>: </td>
        <td><b><?php echo $list[0]['a_perusahaan'].' '.$list[0]['n_kelurahan'].' '.$list[0]['n_kecamatan'].' '.$list[0]['n_kabupaten'];?></b></td>
    </tr>
	<tr>
        <td>NPWP  </td>
        <td>: </td>
        <td><b><?php echo $list[0]['npwp'];?></b></td>
    </tr>
    <tr>
        <td>No. Telp Perusahaan  </td>
        <td>: </td>
        <td><b><?php echo $list[0]['i_telp_perusahaan'];?></b></td>
    </tr>
	<tr>
        <td>Izin Berlaku Sampai Dengan  </td>
        <td>: </td>
        <td><b><?php if(!empty($list[0]['d_berlaku_izin'])){echo $list[0]['d_berlaku_izin'];}else{echo "Selamanya";}?></b></td>
    </tr>								
</table>
	</div>
</div>
<?php }
else {
	echo "<div class=\"block\">
	<div class=\"block-title\"><center><h2 class='error'>IZIN TIDAK TERDAFTAR!!!</h2></center></div>";
	echo "<div class=\"block-content\"><p>Mohon Maaf, Nomor Izin Yang Anda Masukkan Tidak Terdaftar Pada Dinas Penanaman Modal dan Pelayanan Perizinan Terpadu Satu Pintu</p></div></div>";
}
?>