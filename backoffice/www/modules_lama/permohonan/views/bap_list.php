<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php 
			if($sALL==1){
				echo form_open('permohonan/bap/index/'.$sALL,'id="filter_form"');
			}else{
				echo form_open('permohonan/bap','id="filter_form"');
			}
                  
            ?>

            <div id="statusRail">
                    <div id="leftRail">
                     <?php
                          echo form_label('Tgl Permohonan Awal','d_tahun');
                      ?>
                    </div>
             <div id="rightRail">
             <?php
                $periodeawal_input = array(
                'name'  => 'tgla',
                'value' => $tgla,
                'class' => 'input-wrc',
                    'readOnly'=>TRUE,
                'class' => 'monbulan'
                );
                echo form_input($periodeawal_input);
                ?>
              </div>
           </div>

       <div id="statusRail">
                    <div id="leftRail">
                <?php
                    echo form_label('Tgl Permohonan Akhir','d_tahun');
                ?>
                    </div>
             <div id="rightRail">
             <?php
                $periodeakhir_input = array(
                'name'  => 'tglb',
                'value' => $tglb,
                    'readOnly'=>TRUE,
                'class' => 'input-wrc',
                'class' => 'monbulan'
            );
            echo form_input($periodeakhir_input);
            ?>
              </div>
           </div>

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
        </fieldset>
        </div>
        <div class="entry">

            <div class="spacer"></div>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="sk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			<th>Pemohon</th>
                        <th>Jenis Izin</th>
                        <th>Jenis Permohonan</th>
			<th>Tanggal Permohonan</th>
			<th>Tanggal Peninjauan</th>
                        <th>Tim Teknis</th>
			<th>Status</th>
			<th width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $results = mysql_query($list);
                    if($results){
                    while ($rows = mysql_fetch_assoc(@$results)){
                        $showed = FALSE;
                        $idkelompok = NULL;
                        $query_data = "SELECT trkelompok_perizinan_id idkelompok
                        FROM trkelompok_perizinan_trperizinan
                        WHERE trperizinan_id = '".$rows['idizin']."'";
                        $hasil_data = mysql_query($query_data);
                        $rows_data = mysql_fetch_object(@$hasil_data);
                        $idkelompok = $rows_data->idkelompok;
                            if($idkelompok == '2' || $idkelompok == '4'){
                                if($rows['c_tinjauan'] === "1") $showed = TRUE;
                                else $showed = FALSE;
                            }else $showed = TRUE;
                        if($showed){
//                        if($idkelompok == '2' || $idkelompok == '4'){
                        ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows['pendaftaran_id'];?></td>
                        <td><?php echo $rows['n_pemohon'];?></td>
                        <td><?php echo $rows['n_perizinan'];?></td>
                        <td><?php echo $rows['n_permohonan'];?></td>
                        <td>
                        <?php
                        if($rows['idjenis'] == '1') $tgl_permohonan = $rows['d_terima_berkas'];
                        else if($rows['idjenis'] == '2') $tgl_permohonan = $rows['d_perubahan'];
                        else if($rows['idjenis'] == '3') $tgl_permohonan = $rows['d_perpanjangan'];
                        else if($rows['idjenis'] == '4') $tgl_permohonan = $rows['d_daftarulang'];
                        if($tgl_permohonan){
                            if($tgl_permohonan != '0000-00-00') echo $this->lib_date->mysql_to_human($tgl_permohonan);
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                                if ($rows['d_survey']) {
                                    echo "<center>" . $this->lib_date->mysql_to_human($rows['d_survey']) . "</center>";
                                } else {
                                    echo "<center>-- Belum Ditentukan --</center>";
                                }
                            ?>
                        </td>
                        <td><?php echo $rows['n_unitkerja'];?></td>
                        <td>
                            <?php
                                $showed = FALSE;
                                $survey_id = NULL;
                                /*$query_data = "SELECT b.c_pesan, b.id
                                FROM tmbap_tmpermohonan a, tmbap b
                                WHERE a.tmpermohonan_id = '".$rows['id']."'
                                AND b.tim_teknis_id =".$rows['tim_teknis_id'].
                                " AND a.tmbap_id = b.id";
                                $hasil_data = mysql_query($query_data);
                                while ($rows_data = mysql_fetch_assoc(@$hasil_data)){*/
                                    if($rows['c_pesan']) {
                                        echo "<b>Sudah di-entry</b>";
                                        $survey_id = $rows['bap_id'];
                                        $showed = TRUE;
                                        //break;
                                    } else {
                                        echo "Belum di-entry";
                                        $survey_id = NULL;
                                        $showed = FALSE;
                                    }
                                //}
                                //if($survey_id == NULL) echo "Belum di-entry";
                            ?>
                        </td>
                         <td>
                           <?php
                            $img_edit = array(
                                'src' => base_url().'assets/images/icon/property.png',
                                'alt' => 'Detail Berita Acara Pemeriksaan',
                                'title' => 'Detail Berita Acara Pemeriksaan',
                                'border' => '0',
                            );
                            $img_bukti = array(
                                'src' => base_url().'assets/images/icon/clipboard.png',
                                'alt' => 'Cetak Berita Acara Pemeriksaan',
                                'title' => 'Cetak Berita Acara Pemeriksaan',
                                'border' => '0',
                            );
							
							if($rows['c_izin_selesai']==1){
							 	echo anchor(site_url('permohonan/bap/viewBAPData') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_edit))."&nbsp;";
							}else{
							 	if($rows['idizin'] === '2' || $rows['idizin'] === '3'){
	                            	echo anchor(site_url('permohonan/bap/viewBAP2') .'/'. $rows['id'].'/'.$rows['idizin'].'/'.$rows['tim_teknis_id'], img($img_edit))."&nbsp;";
	                            }else{
	                            	echo anchor(site_url('permohonan/bap/viewBAP') .'/'. $rows['id'].'/'.$rows['idizin'].'/'.$rows['tim_teknis_id'], img($img_edit))."&nbsp;";
	                            }
							}
                                if($survey_id) {
                                    if($rows['idizin'] === '2' || $rows['idizin'] === '3'){
                                       echo anchor(site_url('report_generator/cetak/BAP') .'/'. $rows['id'].'/'.$rows['idizin'].'/'.$rows['tim_teknis_id'], img($img_bukti),'target="_blank"')."&nbsp;";
                                    }else{
                                       echo anchor(site_url('report_generator/cetak/BAP') .'/'. $rows['id'].'/'.$rows['idizin'].'/'.$rows['tim_teknis_id'], img($img_bukti),'target="_blank"')."&nbsp;";
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                        $i++;
                        }
                    }
                    }
                    ?>
                    <?php
//                    $i = NULL;
//                    foreach ($list as $data) {
//                        $entry_tampil = FALSE;
//                        $data->tmpemohon->get();
//                        $data->trperizinan->get();
//                        $data->trjenis_permohonan->get();
//                        //Cek Data Entry
//                        $daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
//                        $daftar_awal->where('tmpermohonan_id', $data->id)->get();
//                        if($daftar_awal->id) $entry_tampil = TRUE;
//                        $kelompok = $data->trperizinan->trkelompok_perizinan->get();
//                        foreach ($list_izin as $listizin) {
//                            $showed = FALSE;
//                            if($listizin->id === $data->trperizinan->id) {
//                                $showed = TRUE;
//                            }
//                            if($kelompok->id == 2 || $kelompok->id == 4){
//                                if($data->c_tinjauan === "1") $showed = TRUE;
//                                else $showed = FALSE;
//                            }else $showed = TRUE;
//
//                            if($showed) {
//                                if($entry_tampil){
//                                $i++;
//                                $bap = new tmbap();
//                                $bap->where_related($data)->get();
                                ?>
<!--                                    <tr>

                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $data->pendaftaran_id;?></td>
                                        <td><?php echo $data->tmpemohon->n_pemohon;?></td>
                                        <td><?php echo $data->trperizinan->n_perizinan;?></td>
                                        <td><?php echo $data->trjenis_permohonan->n_permohonan;?></td>
                                        <td>
                                        <?php
                                            if($data->d_survey)  {
                                                echo "<center>" . $this->lib_date->mysql_to_human($data->d_survey) . "</center>";
                                            } else {
                                                echo "<center>-- Belum Ditentukan --</center>";
                                            }
                                        ?>
                                        </td>
                                        <td>
                                            <?php
                                                if($bap->c_pesan) {
                                                    echo "<b>Sudah di-entry</b>";
                                                } else {
                                                    echo "Belum di-entry";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                               <?php
                                                $img_edit = array(
                                                    'src' => base_url().'assets/images/icon/property.png',
                                                    'alt' => 'Detail Berita Acara Pemeriksaan',
                                                    'title' => 'Detail Berita Acara Pemeriksaan',
                                                    'border' => '0',
                                                );
                                                $img_bukti = array(
                                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                                    'alt' => 'Cetak Berita Acara Pemeriksaan',
                                                    'title' => 'Cetak Berita Acara Pemeriksaan',
                                                    'border' => '0',
                                                );
                                                 if($data->trperizinan->id === 2){
                                                    echo anchor(site_url('permohonan/bap/viewBAP2') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_edit))."&nbsp;";
                                                    }else{
                                                    echo anchor(site_url('permohonan/bap/viewBAP') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_edit))."&nbsp;";
                                                    }

                                                    if($bap->id) {
                                                        if($data->trperizinan->id === 2){
                                                           echo anchor(site_url('permohonan/bap/cetakBAP2') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_bukti))."&nbsp;";
                                                        }else{
                                                           echo anchor(site_url('permohonan/bap/cetakBAP') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_bukti))."&nbsp;";
                                                        }
                                                    }
                                                ?>
                                        </td>
                                    </tr>-->
                                <?php
//                                break;
//                                }
//                            }
//                        }
////                        }
//                    }
                ?>

<script type="text/javascript">
$(document).ready(function(){
	var show_all=<?php echo $sALL;?>;
	$('<input id="show_all" type="checkbox" class="radio-header"/><span>Izin sudah diserahkan</span>').appendTo('div.dataTables_length');
	if(show_all==1){
		$('#show_all').attr('checked','checked');
	}
	$('#show_all').change(function(){
		var form_action='';
		if($(this).is(':checked')==true){
			 form_action = '<?php echo site_url("permohonan/bap/index/1"); ?>';
		}else{
		     form_action = '<?php echo site_url("permohonan/bap/"); ?>';
		}
		$('#filter_form').attr('action',form_action);
	});
});
</script>


                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
