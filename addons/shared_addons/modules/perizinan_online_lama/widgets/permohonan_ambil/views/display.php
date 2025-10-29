<ul class="article-list">
    <?php foreach($daftar_permohonan as $permohonan): ?>
        <li>
            <?php echo anchor('perizinan_online/tracking_perizinan?no_pendaftaran='.$permohonan['no_pendaftaran'], $permohonan['no_pendaftaran']) .
                ' - '.$permohonan['nama_perizinan'].' - '.$permohonan['nama_pemohon'];?>
        </li>
    <?php endforeach ?>
    <?php echo anchor('perizinan_online/daftar_ambil', 'Lihat Selengkapnya', 'class="more"');?>
</ul>