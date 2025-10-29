<div class="block">
    <div class="block-title">
        <a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
        <h2>Daftar Download</h2>
    </div>
    <div class="block-content">
        <?php
        $list_header = array();
        foreach($daftar_layanan as $key=>$layanan):
            if(!in_array($layanan->nama_perizinan,$list_header)):
                $list_header[] = $layanan->nama_perizinan;
                $new_table = true;
            else:
                $new_table = false;
            endif;

            if($new_table):
                ?>
                <table>
                <thead>
                <th><?php echo $layanan->nama_perizinan;?></th>
                </thead>
                <tbody>
            <?php
            endif;
            ?>
            <tr>
                <td>
                    <?php echo anchor('files/download/'.$layanan->file_download,$layanan->file_desc);?>
                </td>
            </tr>
            <?php if(!empty($list_header) && $daftar_layanan[($key+1)]->nama_perizinan != $layanan->nama_perizinan):?>
            </tbody>
            </table>
            <br>
        <?php endif; ?>
        <?php
        endforeach;
        ?>

        <?php if(strlen($pagination)):?>
            <?php echo $pagination;?>
        <?php endif;?>
    </div>
</div>