<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/addSMSGateway/jquery.ui.dialog.js"></script>
<form id="smsdialog" action="<?php echo base_url(); ?>pelayanan/ambilsk/sendSMSGateway" method="post" style="display:none">
Apa anda yakin akan mengirim SMS ke no ini ?<br />
<b>No Telp :</b><span id="spanno"></span><input type="hidden" size="15" maxlength="15" id="txtno" name="txtno" value=""  />
<input type="hidden" name="txtisi" id="txtisi" value=""  /><br />
<input type="submit" value="Kirim" name="tblkirim" id="tblkirim"  />&nbsp;&nbsp;<input type="reset" value="Batal" name="tblreset" id="tblreset"  />
<span id="warning"></span>
</form>
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
				echo form_open('pelayanan/ambilsk/index/1','id="filter_form"');
            }else{
				echo form_open('pelayanan/ambilsk','id="filter_form"');
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
                              'readOnly'=>TRUE,
                'class' => 'input-wrc',
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="penyerahan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			<th>Pemohon</th>
                        <th>Jenis Izin</th>
			<th>Tanggal Permohonan</th>
                        <th>No Surat</th>
			<th>Tanggal Surat</th>
			<th width="70">Status</th>
			<th width="40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
                        $status_bap = $rows['status_bap'];
                        $no_surat = $rows['no_surat'];
                        $tgl_surat = $rows['tgl_surat'];
                        $c_cetak = $rows['c_cetak'];
                        $idkelompok = $rows['idkelompok'];
//                        $showed = FALSE;
//                        $tgl_surat = NULL;
//                        $no_surat = NULL;
//                        $status_bap = NULL;
//                        $c_cetak = NULL;
//                        $query_data = "SELECT b.c_skrd, b.status_bap,
//                        d.id idsk, d.tgl_surat, d.no_surat, d.c_cetak
//                        FROM tmbap_tmpermohonan a, tmbap b, tmpermohonan_tmsk c, tmsk d
//                        WHERE a.tmpermohonan_id = '".$rows['id']."'
//                        AND a.tmbap_id = b.id
//                        AND c.tmpermohonan_id = a.tmpermohonan_id
//                        AND c.tmsk_id = d.id";
//                        $hasil_data = mysql_query($query_data);
//                        while ($rows_data = mysql_fetch_assoc(@$hasil_data)){
//                            if($rows_data['idsk']) {
//                                $status_bap = $rows_data['status_bap'];
//                                $no_surat = $rows_data['no_surat'];
//                                $tgl_surat = $rows_data['tgl_surat'];
//                                $c_cetak = $rows_data['c_cetak'];
//                                $showed = TRUE;
//                            } else {
//                                $showed = FALSE;
//                            }
//                        }
//                        if($showed){
//                        $idkelompok = NULL;
//                        $query_data2 = "SELECT trkelompok_perizinan_id idkelompok
//                        FROM trkelompok_perizinan_trperizinan
//                        WHERE trperizinan_id = '".$rows['idizin']."'";
//                        $hasil_data2 = mysql_query($query_data2);
//                        $rows_data2 = mysql_fetch_object(@$hasil_data2);
//                        $idkelompok = $rows_data2->idkelompok;
                        ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows['pendaftaran_id'];?></td>
                        <td><?php echo $rows['n_pemohon'];?></td>
                        <td><?php echo $rows['n_perizinan'];?></td>
                        <td>
                        <?php
                        //if($rows['idjenis'] == '1') $rows['d_terima_berkas'] $rows['d_terima_berkas'];
                        //else if($rows['idjenis'] == '2') $tgl_permohonan = $rows['d_perubahan'];
                        //else if($rows['idjenis'] == '3') $tgl_permohonan = $rows['d_perpanjangan'];
                        //else if($rows['idjenis'] == '4') $tgl_permohonan = $rows['d_daftarulang'];
						$tgl_permohonan = $rows['d_terima_berkas'];
                        if($tgl_permohonan){
                            if($tgl_permohonan != '0000-00-00') echo $this->lib_date->mysql_to_human($tgl_permohonan);
                        } 
                        ?>
                        </td>
                        <td><?php echo $no_surat;?></td>
			<td>
                        <?php
                        if($tgl_surat){
                            if($tgl_surat != '0000-00-00') echo $this->lib_date->mysql_to_human($tgl_surat);
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                            if($rows['c_penetapan']==1){
                                if(in_array($idkelompok, $list_izin_bertarif)){
                                    if($rows['c_status_bayar'] == 1){
                                        echo "Sudah Membayar";
                                    }else{
                                        echo "Belum Membayar";
                                    }
                                }else{
                                    echo "Tidak Membayar";
                                }
                            }else echo "Ditolak";
                            ?>
                        </td>
                         <td nowrap="nowrap">
                          <?php
                            $confirm_text = 'Apakah anda yakin Izin akan diserahkan?';
                            $img_aktif = array(
                                'src' => base_url().'assets/images/icon/tick.png',
                                'alt' => 'Izin Diserahkan',
                                'title' => 'Izin Diserahkan',
                                'border' => '0',
                                'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                            );
							
							if($rows['c_izin_selesai']!=1){
								if($status_bap == 2 || $rows['c_status_bayar'] == 1 || $idkelompok !== '4')
								//echo anchor(site_url('pelayanan/ambilsk/diambil') .'/'. $rows['id'] .'/'. $tgla .'/'. $tglb, img($img_aktif))."&nbsp;";
								//Update 13 Feb 2014
                                echo anchor(site_url('pelayanan/ambilsk/diambil') .'/'. $rows['id'] .'/'. $tgla .'/'. $tglb.'/'.$sALL, img($img_aktif))."&nbsp;";
                            }
                            

                            $confirm_text = 'Apakah anda yakin mengirim SMS surat izin?';
                            $img_aktif = array(
                                'src' => base_url().'assets/images/icon/smartphone_key.png',
                                'alt' => 'Mengirim SMS',
                                'title' => 'Mengirim SMS',
                                'border' => '0'
                            );
							
							if($rows['c_izin_selesai']!=1){
	                            if($status_bap == 2 || $rows['c_status_bayar'] == 1 || $idkelompok !== '4')
	                            {
	                                echo "<a class ='kirimsms' onClick='isino(\"".$rows['telp_pemohon']."\",\"Surat ".$rows['n_perizinan']." dengan no pendaftaran : ".$rows['pendaftaran_id']." sudah selesai. Mohon segera diambil.\");return false;' href='".base_url()."pelayanan/ambilsk/sendSMSGateway/".$rows['telp_pemohon']."/Surat ".$rows['n_perizinan']." dengan no pendaftaran : ".$rows['pendaftaran_id']." sudah selesai. Mohon segera diambil.'>".img($img_aktif)."</a>";
	                            }
							}
                            ?>
                        </td>
                    </tr>
                    <?php
                        $i++;
//                        }
                    }
                    ?>
                <?php
//                    $i=1;
//
//                    foreach ($list as $data){
//                        $data->tmpemohon->get();
//                        $data->trperizinan->get();
//                        $data->tmsk->get();
//                        $data->trperizinan->trkelompok_perizinan->get();
//                        $bap = new tmbap();
//                        $bap->where_related($data)->get();
//                        $status = $bap->status_bap;
//                        if($data->tmsk->id){
                    ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id;?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon;?></td>
                        <td><?php echo $data->trperizinan->n_perizinan;?></td>
                        <td><?php //if($data_bap->status_bap == 1)
                                echo $data->tmsk->no_surat;?></td>
			<td>
                        <?php
                        if($data->tmsk->tgl_surat){
                            if($data->tmsk->tgl_surat != '0000-00-00') echo $this->lib_date->mysql_to_human($data->tmsk->tgl_surat);
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                                if($status == 1){
                                    if($data->trperizinan->trkelompok_perizinan->id == 4){
                                        if($data->c_status_bayar == 1) echo "Sudah Membayar";
                                        else echo "Belum Membayar";
                                    }else echo "Tidak Membayar";
                                }else echo "Ditolak";
                            ?>
                        </td>
                         <td>
                          <?php
                                $confirm_text = 'Apakah anda yakin Izin akan diserahkan?';
                                $img_aktif = array(
                                    'src' => base_url().'assets/images/icon/tick.png',
                                    'alt' => 'Izin Diserahkan',
                                    'title' => 'Izin Diserahkan',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                if($status == 0 || $data->c_status_bayar == 1 || $data->trperizinan->trkelompok_perizinan->id !== 4)
                                echo anchor(site_url('pelayanan/ambilsk/diambil') .'/'. $data->id, img($img_aktif))."&nbsp;";
                            ?>
                        </td>
                    </tr>-->
                    <?php
//                        $i++;
//                        }
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
		var form_action
		if($(this).is(':checked')==true){
			 form_action = '<?php echo site_url("pelayanan/ambilsk/index/1"); ?>';
		}else{
		     form_action = '<?php echo site_url("pelayanan/ambilsk"); ?>';
		}
		$('#filter_form').attr('action',form_action);
	});
});
</script>

