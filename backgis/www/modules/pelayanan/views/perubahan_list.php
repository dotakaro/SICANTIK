<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <?php
//        if($hasUnit)://Jika tidak punya Unit, tidak bisa create Pendaftaran
			if($jenis_perubahan == 'permohonan')
				echo form_open(site_url('pelayanan/perubahan/permohonan'),'id="formSelect"');
            elseif($jenis_perubahan == 'pendataan')
				echo form_open(site_url('pelayanan/perubahan/pendataan'),'id="formSelect"');
            elseif($jenis_perubahan == 'bap')
                echo form_open(site_url('pelayanan/perubahan/bap'),'id="formSelect"');
            elseif($jenis_perubahan == 'tinjauan')
				echo form_open(site_url('pelayanan/perubahan/tinjauan'),'id="formSelect"');
            
        ?>
        <fieldset id="half">
            <legend>Filter</legend>
            <div class="contentForm bg-grid">
                <?php
                    echo form_label('No. Pendafataran', 'no_pendaftaran');
                    $no_pendaftaran = array(
                        'name' => 'no_pendaftaran',
                        'value' => $tgl_daftar_baru,
                        'class' => 'input-wrc required',
                    );
                    echo form_input($no_pendaftaran);
                ?>
            </div>            
            <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail" style="text-align: right">
                <?php
                    //echo form_submit('daftar','Pendaftaran','class="button-wrc"');
                ?>
              <input type="image" src="<?php echo base_url().'assets/images/icon/plus.png'; ?>" value="Submit" alt="Submit">
              </div>
            </div>

        </fieldset>
        </div>

        <div class="entry">
           
          
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendaftaran">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="13%">No Pendaftaran</th>
                        <th width="10%">Id pemohon</th>
                        <th width="15%">Pemohon</th>
                        <th width="15%">Jenis Izin</th>
                        <th width="15%">Jenis Premohonan</th>
                        <th width="10%">Tanggal Permohonan</th>
                        <th width="10%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;

                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
                        $i++;
                        ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows['pendaftaran_id'];?></td>
                        <td><?php echo $rows['no_referensi'];?></td>
                        <td><?php echo strip_slashes($rows['n_pemohon']);?></td>
                        <td><?php echo $rows['n_perizinan'];?></td>
                        <td><?php echo $rows['n_permohonan'];?></td>
                        <td><?php
                        if($rows['d_terima_berkas']){
                            if($rows['d_terima_berkas'] != '0000-00-00') echo $this->lib_date->mysql_to_human($rows['d_terima_berkas']);
                        } ?></td>
                        <td>
                        <?php
                        if($rows['c_paralel'] == 0) $status = "Satu Izin";
                        else $status = "Izin Paralel";
                        echo $status;
                        ?>
                        </td>
                         <td>
						 
                        <?php 
							$img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                            );
                            if($jenis_perubahan == 'permohonan')
							     echo anchor(site_url('perubahan/perubahan_permohonan/edit') .'/'. $rows['id'].'/99/2', img($img_edit))."&nbsp;";
                            elseif($jenis_perubahan == 'pendataan')
                                 echo anchor(site_url('perubahan/perubahan_pendataan/edit') .'/'. $rows['id'], img($img_edit))."&nbsp;";
                            elseif($jenis_perubahan == 'bap')
                                 echo anchor(site_url('perubahan/perubahan_bap/edit') .'/'. $rows['id'], img($img_edit))."&nbsp;";
                             elseif($jenis_perubahan == 'tinjauan')
                                 echo anchor(site_url('perubahan/perubahan_tinjauan/edit') .'/'. $rows['id'], img($img_edit))."&nbsp;";
							//99 tidak ada artinya, hanya untuk memenuhi syarat parameter, sedangkan angka 1 di belakangnya berarti form akan disabled;
							
						?>
						</td>
                    </tr>
                    <?php
                    }
//                    foreach ($list as $data){
//                        $i++;
//                        $data->trperizinan->get();
//                        $izin_kelompok = $data->trperizinan->trkelompok_perizinan->get();
//                        $data->tmpemohon->get();
                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php
                        if($data->d_terima_berkas){
                            if($data->d_terima_berkas != '0000-00-00') echo $this->lib_date->mysql_to_human($data->d_terima_berkas);
                        } ?></td>
                        <td>
                        <?php
                        if($data->c_paralel == 0) $status = "Satu Izin";
                        else $status = "Izin Paralel";
                        echo $status;
                        ?>
                        </td>
                        <td>
                            <?php
                                $img_bukti = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak Bukti',
                                    'title' => 'Cetak Bukti',
                                    'border' => '0',
                                );
                                $img_recom = array(
                                    'src' => base_url().'assets/images/icon/clipboard-doc.png',
                                    'alt' => 'Buat Permohonan Rekomendasi',
                                    'title' => 'Buat Permohonan Rekomendasi',
                                    'border' => '0',
                                );
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin permohonan izin telah selesai?';
                                $img_ok = array(
                                    'src' => base_url().'assets/images/icon/tick.png',
                                    'alt' => 'Daftar Selesai',
                                    'title' => 'Daftar Selesai',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                $img_delete = array(
                                    'src' => base_url().'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                echo anchor(site_url('pelayanan/pendaftaran/cetak_bukti') .'/'. $data->id, img($img_bukti))."&nbsp;";
                                echo anchor(site_url('pelayanan/pendaftaran/edit') .'/'. $data->id, img($img_edit))."&nbsp;";
//                                if($izin_kelompok->id == '1' || $izin_kelompok->id == '3')
//                                if($izin_kelompok->id == '1')
//                                echo anchor(site_url('pelayanan/rekomendasi/edit') .'/'. $data->id .'/1', img($img_recom))."&nbsp;";
                                echo anchor(site_url('pelayanan/pendaftaran/selesai') .'/'. $data->id, img($img_ok))."&nbsp;";
                                echo anchor(site_url('pelayanan/pendaftaran/delete') .'/'. $data->id, img($img_delete)); ?>
                        </td>
                    </tr>-->
                <?php
//                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
<link href="<?php echo base_url();?>assets/css/mod_data_table.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">
$(document).ready(function(){
	var show_all=<?php echo $sALL;?>;
	$('<input id="show_all" type="checkbox" class="radio-header"/><span>Show All</span>').appendTo('div.dataTables_length');
	if(show_all==1){
		$('#show_all').attr('checked','checked');
	}
	$('#show_all').change(function(){
		if($(this).is(':checked')==true){
			location.href = '<?php echo site_url("pelayanan/pendaftaran/index/1"); ?>';
		}else{
			location.href = '<?php echo site_url("pelayanan/pendaftaran/index/"); ?>';
		}
	});
});
</script>
