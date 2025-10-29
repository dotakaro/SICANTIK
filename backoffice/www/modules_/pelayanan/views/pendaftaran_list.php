<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <?php
//        if($hasUnit)://Jika tidak punya Unit, tidak bisa create Pendaftaran
            echo form_open(site_url('pelayanan/pendaftaran/create'),'id="formSelect"');
        ?>
        <fieldset id="half">
            <legend>Data Permohonan</legend>
            <div class="contentForm bg-grid">
                <?php
                    $opsi_paralel = array(
                      'no'  => 'Tidak',
                      'yes' => 'Ya',
                    );

                    echo form_label('Paralel','name_paralel');
                    echo form_dropdown('jenis_paralel', $opsi_paralel, '',
                         'class = "input-select-wrc required" id="paralel_id"');
                ?>
            </div>
            <div class="contentForm" id="show_jenis_izin">
                <?php
                    $opsi_izin = array();
                    if($list_izin->id){
                        foreach ($list_izin as $row){
                            $opsi_izin[$row->id] = $row->n_perizinan;
                        }
                    }

                    echo form_hidden('paralel', 'no');
                    echo form_hidden('jenis_permohonan', $jenis_id);
                    echo form_label('Jenis Izin','name_jenis_izin');
                    echo form_dropdown('jenis_izin', $opsi_izin, '','class = "input-select-wrc required" id="jenis_izin" multiple="multiple"');
                ?>
            </div>
            <div class="contentForm bg-grid">
                <?php
                $opsiUnitKerja = array();
                echo form_label('Unit Kerja','unit_kerja');
                echo form_dropdown('unit_kerja', $opsiUnitKerja, '','class = "input-select-wrc required" id="unit_kerja"');
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
        <?php
            echo form_close();
//        endif;
        ?>
<!--        </div>
        <div class="entry">-->
<!--        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('pelayanan/pendaftaran');
                  echo form_label('Tgl Permohonan Awal','d_tahun');
            ?>

              <div id="rightMainRail">
                <?php
                $periodeawal_input = array(
                'name'  => 'tgla',
                'value' => $tgla,
                'class' => 'input-wrc',
                'class' => 'monbulan'
                );
                echo form_input($periodeawal_input);
                ?>
              </div>
                <p>
                     <?php
                    echo form_label('Tgl Permohonan Akhir','d_tahun');
                ?>

              <div id="rightMaintRail">
                <?php
                $periodeakhir_input = array(
                'name'  => 'tglb',
                'value' => $tglb,
                'class' => 'input-wrc',
                'class' => 'monbulan'
            );
            echo form_input($periodeakhir_input);
            ?>
              </div>
                <p>
                  <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
                <?php
                    $filter_data = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Filter',
                        'value' => 'Filter'
                    );

                    echo form_submit($filter_data);
                    ?>
              </div>
            </div>
            <?php
            echo form_close();
            ?>
        </fieldset>-->
        </fieldset>
        </div>
        <?php
        if($ket_syarat){
            echo "<div class='entry' align=center><b style='color: #FF0000;'>Persyaratan tidak lengkap !!</b></div>";
        }
        ?>
        <div class="entry">
           
          
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendaftaran">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="13%">No Pendaftaran</th>
                        <th width="10%">Id pemohon</th>
                        <th width="15%">Pemohon</th>
                        <th width="15%">Jenis Izin</th>
                        <th width="15%">Unit Kerja</th>
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
                        <td><?php echo $rows['n_unitkerja'];?></td>
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
						 <?php if($rows['c_pendaftaran']==0){?>
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
                                echo anchor(site_url('pelayanan/pendaftaran/cetak_bukti') .'/'. $rows['id'], img($img_bukti))."&nbsp;";
                                echo anchor(site_url('pelayanan/pendaftaran/edit') .'/'. $rows['id'], img($img_edit))."&nbsp;";
//                                if($izin_kelompok->id == '1' || $izin_kelompok->id == '3')
//                                if($izin_kelompok->id == '1')
//                                echo anchor(site_url('pelayanan/rekomendasi/edit') .'/'. $data->id .'/1', img($img_recom))."&nbsp;";
                                echo anchor(site_url('pelayanan/pendaftaran/selesai') .'/'. $rows['id'], img($img_ok))."&nbsp;";
                                echo anchor(site_url('pelayanan/pendaftaran/delete') .'/'. $rows['id'], img($img_delete)); ?>
                        <?php }else{
							$img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                            );
							echo anchor(site_url('pelayanan/pendaftaran/edit') .'/'. $rows['id'].'/99/1', img($img_edit))."&nbsp;";
							//99 tidak ada artinya, hanya untuk memenuhi syarat parameter, sedangkan angka 1 di belakangnya berarti form akan disabled;
							
						}?>
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
