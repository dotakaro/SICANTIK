<h2>Data Hasil Pencarian</h2>
<?php
if(!empty($list)){
?>
<p>Berikut ini status terakhir permohonan izin yang Anda ajukan. Untuk keterangan lebih lengkap silakan datang ke Kantor Layanan Perizinan.</p>
<table width="100%">
    <tbody><tr>
        <td width="20%">No Pendaftaran </td>
        <td>:</td>
        <td><b><?php echo $list[0]['no_pendaftaran'];?></b></td>
    </tr>
    <tr>
        <td>Nama Pemohon  </td>
        <td>:</td>
        <td><b><?php echo $list[0]['nama_pemohon'];?></b></td>
    </tr>
    <tr>
        <td>Nama Perizinan  </td>
        <td>:</td>
        <td><b><?php echo $list[0]['nama_perizinan'];?></b></td>
    </tr>
    <tr>
        <td>Proses Perizinan  </td>
        <td>:</td>
        <td>
            <?php
            $total = count($list);
            foreach($list as $index=>$proses){
                if($proses['current']==1):
                    echo '<b>'.$proses['sts_permohonan'].'</b>'.'<br>';
                else:
                    echo $proses['sts_permohonan'].'<br>';
                endif;
            }
            ?>
        </td>
    </tr>
</table>
<?php }?>