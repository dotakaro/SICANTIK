<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
    </div>
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('dokumen/persetujuan');
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
        </fieldset>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="persetujuan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemegang Izin</th>
                        <th>No Pendaftaran</th>
                        <th>Tgl Daftar</th>
                        <th>Jenis Izin</th>
                        <th>No Surat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
                        $no_surat = $rows['no_surat'];
                        $tgl_surat = $rows['tgl_surat'];
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
                                echo "Belum diproses";
                        ?>
                        </td>
                        <td>
                            <center>
                                <?php
                                $confirm_text = 'Apakah Anda yakin pengajuan ini disetujui?';
                                $img_ya = array(
                                    'src' => 'assets/images/icon/tick.png',
                                    'alt' => 'Disetujui',
                                    'title' => 'Disetujui',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                $confirm_text2 = 'Apakah Anda yakin pengajuan ini ditolak?';
                                $img_tidak = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Hapus',
                                    'title' => 'Hapus',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text2.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('dokumen/persetujuan/status/'.$rows['idsk']."/1") ?>"
                                ><?php echo img($img_ya); ?></a>
                                <a class="page-help" href="<?php echo site_url('dokumen/persetujuan/status/'.$rows['idsk']."/0") ?>"
                                ><?php echo img($img_tidak); ?></a>
                            </center>
                        </td>
                    </tr>
                    <?php
                        $i++;
//                        }
                    }
                    ?>
                <?php
//                    $i = 0;
//                    foreach ($list as $data){
//
//                    $data->tmpemohon->get();
//                    $data->trperizinan->get();
//                    $data->tmsk->get();
//                            if(intval($data->tmsk->c_is_requested) === 1 &&
//                                    intval($data->tmsk->c_status_salinan) === 0) {
//                        $bap = new tmbap();
//                        $bap->where_related($data)->get();
//                        if($bap->status_bap === $c_bap){
//                                $i++;
                                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->tmsk->no_surat; ?></td>
                        <td>
                            <?php
                                if(strval($data->tmsk->c_status_salinan) === '0') {
                                    echo "Belum diproses";
                                } else if (strval($data->tmsk->c_status_salinan) === '1') {
                                    echo "<bold>Diterima</bold>";
                                } else {
                                    echo "<bold>Ditolak</bold>";
                                }
                            ?>
                        </td>
                        <td width="50">
                            <center>
                                <?php
                                $confirm_text = 'Apakah Anda yakin pengajuan ini disetujui?';
                                $img_ya = array(
                                    'src' => 'assets/images/icon/tick.png',
                                    'alt' => 'Disetujui',
                                    'title' => 'Disetujui',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                $confirm_text2 = 'Apakah Anda yakin pengajuan ini ditolak?';
                                $img_tidak = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Hapus',
                                    'title' => 'Hapus',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text2.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('dokumen/persetujuan/status/'.$data->tmsk->id."/1") ?>"
                                ><?php echo img($img_ya); ?></a>
                                <a class="page-help" href="<?php echo site_url('dokumen/persetujuan/status/'.$data->tmsk->id."/0") ?>"
                                ><?php echo img($img_tidak); ?></a>
                            </center>
                        </td>
                    </tr>-->
                                <?php
//                        }
//                            }
//                    }
                ?>
                </tbody>
            </table>
        </div>
</div>
