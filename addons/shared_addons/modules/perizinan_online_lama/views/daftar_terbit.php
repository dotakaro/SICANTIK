<div class="block">
    <div class="block-title">
        <a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
        <h2>Daftar Permohonan yang Sudah Diterbitkan</h2>
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
                echo '<td>'.$permohonan['no_pendaftaran'].'</td>';
                echo '<td>'.$permohonan['nama_perizinan'].'</td>';
                echo '<td>'.$permohonan['nama_pemohon'].'</td>';
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