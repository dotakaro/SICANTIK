
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('kasir');
                  
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="permohonan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Permohonan</th>
                        <th>No Surat</th>
                        <th>Status</th>
                        <th width="30px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
                        $showed = FALSE;
                        $idkelompok = NULL;
                        $query_data2 = "SELECT trkelompok_perizinan_id idkelompok
                            FROM trkelompok_perizinan_trperizinan
                            WHERE trperizinan_id = '".$rows['idizin']."'";
                        $hasil_data2 = mysql_query($query_data2);
                        $rows_data2 = mysql_fetch_object(@$hasil_data2);
                        $idkelompok = $rows_data2->idkelompok;
                        //if($idkelompok == '4'){
                        $no_surat = NULL;
                        $query_data = "SELECT b.c_skrd, b.status_bap,
                        b.bap_id, b.c_pesan, d.no_surat, d.c_cetak, d.id idsk
                        FROM tmbap_tmpermohonan a, tmbap b, tmpermohonan_tmsk c, tmsk d
                        WHERE a.tmpermohonan_id = '".$rows['id']."'
                        AND a.tmbap_id = b.id
                        AND c.tmpermohonan_id = a.tmpermohonan_id
                        AND c.tmsk_id = d.id";
                        $hasil_data = mysql_query($query_data);
                        while ($rows_data = mysql_fetch_assoc(@$hasil_data)){
                            //if($rows_data['status_bap'] === $c_bap && $rows_data['idsk']) {
                                $no_surat = $rows_data['no_surat'];
                            //    $showed = TRUE;
                            //} else {
                            //    $showed = FALSE;
                            //}
                        }
                        //if($showed){
                        ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows['pendaftaran_id'];?></td>
                        <td><?php echo $rows['n_pemohon'];?></td>
                        <td><?php echo $rows['n_perizinan'];?></td>
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
                        <td><?php echo $no_surat;?></td>
                        <td>
                            <?php
                                if($rows['c_status_bayar'] == 1) echo "<b>Sudah Membayar</b>";
                                else echo "Belum Membayar";
                            ?>
                        </td>
                         <td>
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('kasir/edit'."/".$rows['id']) ?>"
                                ><?php echo img($img_edit); ?></a>
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
//                    $i = NULL;
//                    foreach ($list as $data){
//                        $data->tmpemohon->get();
//                        $data->trperizinan->get();
//                        $data->trstspermohonan->get();
//                        $data->tmsk->get();
//                        $data->trperizinan->trkelompok_perizinan->get();
//                        if($data->trperizinan->trkelompok_perizinan->id == 4 && $data->tmsk->id){
//
//                        $bap = new tmbap();
//                        $bap->where_related($data)->get();
//                        if($bap->status_bap === $c_bap){
//                            $cetak_skrd = $bap->c_skrd;
                ?>
<!--                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->tmsk->no_surat; ?></td>
                        <td>
                            <?php
                                if(intval($data->c_status_bayar) === 1) {
                                    echo "<b>Sudah Bayar</b>";
                                } else if(intval($data->c_status_bayar) === 0) {
                                    echo "Belum Bayar";
                                }
                            ?>
                        </td>
                        <td width="50">
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('kasir/edit'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                            </center>
                        </td>
                    </tr>-->
                <?php
//                        }
//                        }
//                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
