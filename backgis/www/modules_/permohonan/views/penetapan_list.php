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
				echo form_open('permohonan/penetapan/index/'.$sALL,'id="filter_form"');
			}else{
				echo form_open('permohonan/penetapan','id="filter_form"');
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
            <div class="spacer"></div>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="sk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			<th>Pemohon</th>
                        <th>Jenis Izin</th>
                        <!--<th>No BAP</th>-->
			<th>Tanggal Permohonan</th>
			<th>Status</th>
			<th width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
                        //$survey_id = $rows['bap_id'];
                        $c_penetapan = $rows['c_penetapan'];
                        $status_bap = $rows['status_bap'];
                        if($rows['tidak_direkomendasi'] != 0){ //Jika ada yang belum mengisi rekomendasi atau tidak merekomendasikan
                            continue; //tidak usah tampilkan
                        }
                        //$c_pesan = $rows['c_pesan'];
                        //if($c_pesan){
//                        $showed = FALSE;
//                        $c_penetapan = NULL;
//                        $status_bap = NULL;
//                        $survey_id = NULL;
//                        $query_data = "SELECT b.c_penetapan, b.status_bap,
//                        b.bap_id, b.c_pesan
//                        FROM tmbap_tmpermohonan a, tmbap b
//                        WHERE a.tmpermohonan_id = '".$rows['id']."'
//                        AND a.tmbap_id = b.id";
//                        $hasil_data = mysql_query($query_data);
//                        while ($rows_data = mysql_fetch_assoc(@$hasil_data)){
//                            if($rows_data['c_pesan']) {
//                                $survey_id = $rows_data['bap_id'];
//                                $c_penetapan = $rows_data['c_penetapan'];
//                                $status_bap = $rows_data['status_bap'];
//                                $showed = TRUE;
//                            } else {
//                                $showed = FALSE;
//                            }
//                        }
//                        if($showed){
                        ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows['pendaftaran_id'];?></td>
                        <td><?php echo $rows['n_pemohon'];?></td>
                        <td><?php echo $rows['n_perizinan'];?></td>
                        <!--<td><?php
                                //echo $survey_id;?></td>-->
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
                                /*if($c_penetapan === "1") {
                                    if($status_bap === "1")
                                        echo "<b>Diizinkan</b>";
                                    else
                                        echo "<b>Ditolak</b>";
                                } else {
                                    echo "Belum ditetapkan";
                                }*/
                                switch($c_penetapan){
                                    case 1:
                                        echo "Diizinkan";
                                        break;
                                    case 2:
                                        echo "Ditolak";
                                        break;
                                    default:
                                        echo "Belum ditetapkan";
                                        break;
                                }
                            ?>
                        </td>
                         <td>
                            <center>
                               <?php
                                $img_bukti = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Detail Penetapan SK',
                                    'title' => 'Detail Penetapan SK',
                                    'border' => '0',
                                );

                                 if($rows['idizin'] === '2' || $rows['idizin'] === '3'){
                                    echo anchor(site_url('permohonan/penetapan/viewSK2') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_bukti))."&nbsp;";
                                    }else{
                                    echo anchor(site_url('permohonan/penetapan/viewSK') .'/'. $rows['id'].'/'.$rows['idizin'], img($img_bukti))."&nbsp;";
                                    }

                                 ?>
                            </center>
                        </td>
                    </tr>
                    <?php
                        $i++;
                        //}
                    }
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
			 form_action = '<?php echo site_url("permohonan/penetapan/index/1"); ?>';
		}else{
		     form_action = '<?php echo site_url("permohonan/penetapan/"); ?>';
		}
		$('#filter_form').attr('action',form_action);
	});
});
</script>