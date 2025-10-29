<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('permohonan/skditolak');
                  
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="sk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			<th>Pemohon</th>
                        <th>Jenis Izin</th>
                        <th>Alamat Permohonan</th>
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
//                        $showed = FALSE;
                        $showed = true;
                        $c_cetak = null;
                        /*$query_data = "SELECT b.c_skrd, b.status_bap,
                        b.bap_id, b.c_pesan, d.no_surat, d.c_cetak
                        FROM tmbap_tmpermohonan a, tmbap b, tmpermohonan_tmsk c, tmsk d
                        WHERE a.tmpermohonan_id = '".$rows['id']."'
                        AND a.tmbap_id = b.id
                        AND c.tmpermohonan_id = a.tmpermohonan_id
                        AND c.tmsk_id = d.id";
                        $hasil_data = mysql_query($query_data);
                        while ($rows_data = mysql_fetch_assoc(@$hasil_data)){
                            if($rows_data['status_bap'] === $c_bap) {
                                $c_cetak = $rows_data['c_cetak'];
                                $showed = TRUE;
                            } else {
                                $showed = FALSE;
                            }
                        }*/
                        if($showed){
                        ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows['pendaftaran_id'];?></td>
                        <td><?php echo $rows['n_pemohon'];?></td>
                        <td><?php echo $rows['n_perizinan'];?></td>
                        <td><?php echo $rows['a_pemohon'];?></td>
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
                                if($c_cetak){
                                    echo "<b>Dicetak ".$c_cetak." kali</b>";
                                } else {
                                    echo "Belum di-cetak";
                                }
                            ?>
                        </td>
                         <td>
                            <center>
                          <?php
                                $img_cetak = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak SK Ditolak',
                                    'title' => 'Cetak SK Ditolak',
                                    'border' => '0',
                                );
                                echo anchor(site_url('permohonan/skditolak/cetak') .'/'. $rows['id'].'/'. $rows['idizin'], img($img_cetak),'target="_blank"')
                                     ."&nbsp;";
                            ?>
                             </center>
                        </td>
                    </tr>
                    <?php
                        $i++;
                        }
                    }
                    ?>
                <?php
//                    $i=1;
//
//                    foreach ($list as $data){
//                        $data->tmpemohon->get();
//                        $data->trperizinan->get();
//                        $data->tmsk->get();
//                        $bap = new tmbap();
//                        $bap->where_related($data)->get();
//                        if($bap->status_bap === $c_bap){
                    ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id;?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon;?></td>
                        <td><?php echo $data->trperizinan->n_perizinan;?></td>
                        <td><?php echo $data->tmpemohon->a_pemohon;?></td>
			<td>
                        <?php
                        if($data->d_entry){
                            if($data->d_entry != '0000-00-00') echo $this->lib_date->mysql_to_human($data->d_entry);
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                                if($data->tmsk->c_cetak){
                                    echo "<b>Dicetak ".$data->tmsk->c_cetak." kali</b>";
                                } else {
                                    echo "Belum di-cetak";
                                }
                            ?>
                        </td>
                         <td><center>
                          <?php
                                $img_cetak = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak SK Ditolak',
                                    'title' => 'Cetak SK Ditolak',
                                    'border' => '0',
                                );
                                echo anchor(site_url('permohonan/skditolak/cetak') .'/'. $data->id, img($img_cetak))
                                     ."&nbsp;";
                            ?></center>
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
