<?php
	$jenis_ijin = array();
	foreach($perizinan as $data)
	{
		$jenis_ijin[$data->id] = $data->n_perizinan;
	}
?>
<div id="content">
	<div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
		<div>
			<br>
			Tambah parameter {KDPER} pada bagian penomoran di Report Componen untuk menambahkan kode untuk jenis permohonan tertentu<br>
		</div>
		<div class="entry">
            <?php
                $add_role = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Kode Format Penomoran',
                    'onclick' => 'parent.location=\''. site_url('penomoran/formatpenomoran/create') . '\''
                );
                echo form_button($add_role);

                if($ket_exist){
                    echo "<div class='entry' align=center><b style='color: #FF0000;'>Kode Format Penomoran \"".$ket_exist."\" sudah digunakan !!</b></div>";
                }
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="user">
                <thead>
                    <tr>
                        <th>Jenis Ijin</th>
                        <th>Permohonan</th>
                        <th>Kode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                ?>
                    <tr>
                        <td><?php echo $jenis_ijin[$data->id_perizinan]; ?></td>
                        <td><?php echo $jenis_permohonan[$data->id_jenis]; ?></td>
                        <td><?php echo $data->format; ?></td>
                        <td>
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapus '.$data->username.'?';
                                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('penomoran/formatpenomoran/edit'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <a class="page-help" href="<?php echo site_url('penomoran/formatpenomoran/delete'."/".$data->id) ?>"
                                ><?php echo img($img_delete); ?></a>
                            </center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Jenis Izin</th>
                        <th>Permohonan</th>
                        <th>Kode</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
	</div>
	<br style="clear: both;" />
</div>