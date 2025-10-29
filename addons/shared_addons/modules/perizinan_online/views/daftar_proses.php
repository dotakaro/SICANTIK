<div class="block">
    <div class="block-title">
        <a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
        <h2>Daftar Permohonan dalam Proses</h2>
    </div>
    <div class="block-content">
        <table>
            <thead>
                <th>No Pendaftaran</th>
                <th>Nama Perizinan</th>
                <th>Pemohon</th>
            </thead>
            <tbody>
            <?php
            foreach($daftar_permohonan as $key=>$permohonan):
                echo '<tr>';
                echo '<td><a href="/perizinan_online/tracking_perizinan?no_pendaftaran='.$permohonan['no_pendaftaran'].'" target="_blank">'.$permohonan['no_pendaftaran'].'</a></td>';
                echo '<td><a href="/perizinan_online/tracking_perizinan?no_pendaftaran='.$permohonan['no_pendaftaran'].'" target="_blank">'.$permohonan['nama_perizinan'].'</a></td>';
                echo '<td><a href="/perizinan_online/tracking_perizinan?no_pendaftaran='.$permohonan['no_pendaftaran'].'" target="_blank">'.$permohonan['nama_pemohon'].'</a></td>';
                echo '</tr>';
            endforeach;
            ?>
            </tbody>
        </table>

        <?php if(strlen($pagination)):?>
            <?php echo $pagination;?>
        <?php endif;?>
    </div>
</div>