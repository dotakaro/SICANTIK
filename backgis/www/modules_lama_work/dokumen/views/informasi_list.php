<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="penyerahan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Izin</th>
			<th>Permohonan Salinan</th>
                        <th>Disetujui</th>
                        <th>Dibatalkan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i=1;
                    foreach ($list as $data){
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_perizinan;?></td>
                        <td><?php ?></td>
                        <td><?php ?></td>
                        <td><?php ?></td>
                    </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
