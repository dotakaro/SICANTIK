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
				echo form_open('permohonan/skrd/index'.$sALL,'id="filter_form"');
			}else{
				echo form_open('permohonan/skrd','id="filter_form"');
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
                'class' => 'input-wrc',
                    'readOnly'=>TRUE,
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

           <?php
//            echo form_open('permohonan/skrd/view');
           
            ?>

            <div class="spacer"></div>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="skrd">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			            <th>Pemohon</th>
                        <th>Jenis Izin</th>
                        <th>Jenis Permohonan</th>
                        <th>Tanggal Permohonan</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Status</th>
                        <th width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
                        $showed = FALSE;
                        $idkelompok = NULL;

                        $query_data = "SELECT trkelompok_perizinan_id idkelompok
                            FROM trkelompok_perizinan_trperizinan
                            WHERE trperizinan_id = '".$rows['idizin']."'";
                        $hasil_data = mysql_query($query_data);
                        $rows_data = mysql_fetch_object(@$hasil_data);
                        $idkelompok = $rows_data->idkelompok;
//                        $kelompok = new trkelompok_perizinan_trperizinan();
//                        $kelompok->where('trperizinan_id', $rows['idizin'])->get();
                        //if($idkelompok == '2' || $idkelompok == '4'){
                        $c_skrd = NULL;
                        $query_data = "SELECT b.c_skrd, b.status_bap,
                            b.bap_id, b.c_pesan
                            FROM tmbap_tmpermohonan a, tmbap b
                            WHERE a.tmpermohonan_id = '".$rows['id']."'
                            AND a.tmbap_id = b.id";
                        $hasil_data = mysql_query($query_data);
                        while ($rows_data = mysql_fetch_assoc(@$hasil_data)){
                            //if($rows_data['status_bap'] === $c_bap) {
                                $c_skrd = $rows_data['c_skrd'];
                            //    $showed = TRUE;
                            //} else {
                          //      $showed = FALSE;
                            //}
                        }

                        //if($showed){
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
                        <td>
                            <?php
                                if($c_skrd) {
                                    echo "<b>Dicetak ".$c_skrd." kali</b>";
                                } else {
                                    echo "Belum di-cetak";
                                }
                            ?>
                        </td>
                         <td>
                            <center>
                             <?php
                                $img_bukti = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak SKRD',
                                    'title' => 'Cetak SKRD',
                                    'border' => '0',
                                );
                                $img_skrd2 = array(
                                    'src' => base_url().'assets/images/icon/clipboard-doc.png',
                                    'alt' => 'Cetak Lampiran SKRD',
                                    'title' => 'Cetak Lampiran SKRD',
                                    'border' => '0',
                                );
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit Keringanan',
                                    'title' => 'Edit Keringanan',
                                    'border' => '0',
                                );
                                $img_create = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Create Keringanan',
                                    'title' => 'Create Keringanan',
                                    'border' => '0',
                                );
//                                if($rows['idizin'] === '2' || $rows['idizin'] === '3'){
//                                echo anchor(site_url('permohonan/skrd/cetakSKRDimb') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_bukti))."&nbsp;";
//                                echo anchor(site_url('permohonan/skrd/cetakLampImb') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_skrd2))."&nbsp;";
//                                }  elseif($rows['idizin'] === '1' || $rows['idizin'] === '88' || $rows['idizin'] === '89') {
                                echo anchor(site_url('permohonan/skrd/cetakSKRD') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_bukti),'target="_blank"')."&nbsp;";
                                echo anchor(site_url('permohonan/skrd/cetakSKRD2') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_skrd2),'target="_blank"')."&nbsp;";
//                                }  else {
//                                echo anchor(site_url('permohonan/skrd/cetakSKRDgeneric') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_bukti))."&nbsp;";
//                                echo anchor(site_url('permohonan/skrd/cetakSKRD2') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_skrd2))."&nbsp;";
//                                }

                                $showed2 = FALSE;
                                $ringan_id = NULL;
                                $query_data2 = "SELECT b.id
                                FROM tmkeringananretribusi_tmpermohonan a, tmkeringananretribusi b
                                WHERE a.tmpermohonan_id = '".$rows['id']."'
                                AND a.tmkeringananretribusi_id = b.id";
                                $hasil_data2 = mysql_query($query_data2);
                                while ($rows_data2 = mysql_fetch_assoc(@$hasil_data2)){
                                    if($rows_data2['id']) {
                                        $ringan_id = $rows_data2['id'];
                                        $showed2 = TRUE;
                                    } else {
                                        $showed2 = FALSE;
                                    }
                                }
								
								if($rows['c_izin_selesai']!=1){
									if($showed2) {
	                                	echo anchor(site_url('permohonan/skrd/editdiskon') .'/'. $rows['id'].'/'.$rows['idizin'].'/'.$ringan_id, img($img_edit))."&nbsp;";
	                                }else{
	                                	echo anchor(site_url('permohonan/skrd/diskon') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_create))."&nbsp;";
	                                }
								}
                                ?>
                          </center>
                        </td>
                    </tr>
                    <?php
                        $i++;
                        //}
                        //}
                    }
                    ?>
                <?php
//                    $i=1;
//                    foreach ($list as $data){
//                        $data->tmpemohon->get();
//                        $data->trperizinan->get();
//                        $data->trjenis_permohonan->get();
//                        $data->tmkeringananretribusi->get();
//                        $kelompok = $data->trperizinan->trkelompok_perizinan->get();
//
//                        if($kelompok->id == 2 || $kelompok->id == 4){
//                        $bap = new tmbap();
//                        $bap->where_related($data)->get();
//                        if($bap->status_bap === $c_bap){
//                            $cetak_skrd = $bap->c_skrd;
                ?>
<!--                    <tr>
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
                                if($cetak_skrd) {
                                    echo "<b>Dicetak ".$cetak_skrd." kali</b>";
                                } else {
                                    echo "Belum di-cetak";
                                }
                            ?>
                        </td>
                         <td>
                          <?php echo form_open('permohonan/skrd/cetakSKRD');?>
                           <?php echo form_hidden('id',$i);?>
                           <?php echo form_hidden('ids',$data->tmkeringananretribusi->id);?>
                           <?php echo form_hidden('nopendaftaran',$data->pendaftaran_id);?>
                           <?php echo form_hidden('idjenis',$data->trperizinan->id);?>
                           <?php echo form_hidden('idpemohon',$data->tmpemohon->id);?>
                          <center>
                             <?php
                                $img_bukti = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak SKRD',
                                    'title' => 'Cetak SKRD',
                                    'border' => '0',
                                );
                                $img_skrd2 = array(
                                    'src' => base_url().'assets/images/icon/clipboard-doc.png',
                                    'alt' => 'Cetak Lampiran SKRD',
                                    'title' => 'Cetak Lampiran SKRD',
                                    'border' => '0',
                                );
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit Keringanan',
                                    'title' => 'Edit Keringanan',
                                    'border' => '0',
                                );
                                $img_create = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Create Keringanan',
                                    'title' => 'Create Keringanan',
                                    'border' => '0',
                                );
                                if($data->trperizinan->id === 2 || $data->trperizinan->id === 3){
                                echo anchor(site_url('permohonan/skrd/cetakSKRDimb') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_bukti))."&nbsp;";
                                echo anchor(site_url('permohonan/skrd/cetakLampImb') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_skrd2))."&nbsp;";
                                }  elseif($data->trperizinan->id === 1 || $data->trperizinan->id === 88) {
                                echo anchor(site_url('permohonan/skrd/cetakSKRD') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_bukti))."&nbsp;";
                                echo anchor(site_url('permohonan/skrd/cetakSKRD2') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_skrd2))."&nbsp;";
                                }  else {
                                echo anchor(site_url('permohonan/skrd/cetakSKRDgeneric') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_bukti))."&nbsp;";
                                echo anchor(site_url('permohonan/skrd/cetakSKRD2') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_skrd2))."&nbsp;";
                                }

                                if($data->tmkeringananretribusi->id) {
                                echo anchor(site_url('permohonan/skrd/editdiskon') .'/'. $data->id.'/'.$data->trperizinan->id.'/'.$data->tmkeringananretribusi->id, img($img_edit))."&nbsp;";
                                }else{
                                echo anchor(site_url('permohonan/skrd/diskon') .'/'. $data->id.'/'.$data->trperizinan->id, img($img_create))."&nbsp;";
                                }
                                echo form_close();
                                ?>
                          </center>
                        </td>
                    </tr>-->
                <?php
//                   $i++; }
//                   }
//                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>

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
			 form_action = '<?php echo site_url("permohonan/skrd/index/1"); ?>';
		}else{
		     form_action = '<?php echo site_url("permohonan/skrd/"); ?>';
		}
		$('#filter_form').attr('action',form_action);
	});
});
</script>
