<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('dokumen/penyerahan');
                  
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
                        <th>Nama Pemegang Izin</th>
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
                                $img_cetak = array(
                                    'src' => base_url() . 'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak Salinan',
                                    'title' => 'Cetak Salinan',
                                    'border' => '0',
                                );
                                $img_tidak = array(
                                    'src' => base_url() . 'assets/images/icon/cross.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                //echo $rows['c_status_salinan'];
                                if(strval($rows['c_status_salinan']) === '0') {
                                    echo "Belum diproses";
                                } else if (strval($rows['c_status_salinan']) === '1') {
                                    echo "<bold>Diterima</bold>";
                                    $link = anchor(site_url('dokumen/penyerahan/cetak') . '/' . $rows['id'] . '/' . $rows['idsk'], img($img_cetak),'target="_blank"');
                                } else if (strval($rows['c_status_salinan']) === '2') {
                                    echo "<bold>Ditolak</bold>";
                                    $link = anchor(site_url('dokumen/penyerahan/hapus') . '/' . $rows['idsk'], img($img_tidak));
                                } else {
                                    echo "<b>Sudah diserahkan</b>";
                                    $link = anchor(site_url('dokumen/penyerahan/cetak') . '/' . $rows['id'] . '/' . $rows['idsk'], img($img_cetak),'target="_blank"');
                                }
                        ?>
                        </td>
                        <td>
                            <center>
                                <?php
                                    $attr = array('id' => 'form');
//                                        $attr = array('id' => 'form');
                                    //echo form_open_multipart('dokumen/penyerahan/save', $attr);
                                    //echo form_hidden('id_daftar', $rows['id']);
                                    echo $link."&nbsp;";
                                    if($rows['file_ttd'])
                                        $img_icon = "status.png";
                                    else{
                                        $img_icon = "status-busy.png";
                                    }
                                    $img_status = array(
                                        'src' => base_url().'assets/images/icon/'.$img_icon,
                                        'alt' => 'Edit',
                                        'title' => 'Edit',
                                        'border' => '0',
                                    );
                                    if (strval($rows['c_status_salinan']) !== '2'){
//                                    echo img($img_status)."&nbsp;";
//                                    echo "<input type='file' name='file_ttd' class = 'input-wrc' />";
                                    $add_daftar = array(
                                        'name' => 'submit',
                                        'class' => 'submit-wrc',
                                        'content' => 'Simpan',
                                        'type' => 'submit',
                                        'value' => 'ok'
                                    );
//                                    echo form_submit($add_daftar);
                                    $confirm_text = 'Apakah Anda yakin salinan akan diserahkan?';
                                    $img_ya = array(
                                        'src' => 'assets/images/icon/tick.png',
                                        'alt' => 'Diserahkan',
                                        'title' => 'Diserahkan',
                                        'border' => '0',
                                        'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                    );
                                ?>
                                <a class="page-help" href="<?php echo site_url('dokumen/penyerahan/status/'.$rows['idsk']) ?>"
                                ><?php if (strval($rows['c_status_salinan']) !== '3') echo img($img_ya); ?></a>
                                <?php }
                                //echo form_close(); ?>
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
//                                    intval($data->tmsk->c_status_salinan) !== 0) {
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
                                $img_cetak = array(
                                    'src' => base_url() . 'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak Salinan',
                                    'title' => 'Cetak Salinan',
                                    'border' => '0',
                                );
                                $img_tidak = array(
                                    'src' => base_url() . 'assets/images/icon/cross.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                if(strval($data->tmsk->c_status_salinan) === '0') {
                                    echo "Belum diproses";
                                } else if (strval($data->tmsk->c_status_salinan) === '1') {
                                    echo "<bold>Diterima</bold>";
                                    $link = anchor(site_url('dokumen/penyerahan/cetak') . '/' . $data->id . '/' . $data->tmsk->id, img($img_cetak));
                                } else if (strval($data->tmsk->c_status_salinan) === '2') {
                                    echo "<bold>Ditolak</bold>";
                                    $link = anchor(site_url('dokumen/penyerahan/hapus') . '/' . $data->tmsk->id, img($img_tidak));
                                } else {
                                    echo "<b>Sudah diserahkan</b>";
                                    $link = anchor(site_url('dokumen/penyerahan/cetak') . '/' . $data->id . '/' . $data->tmsk->id, img($img_cetak));
                                }
                            ?>
                        </td>
                        <td width="300">
                            <center>
                                <?php
                                    $attr = array('id' => 'form');
//                                        $attr = array('id' => 'form');
                                    echo form_open_multipart('dokumen/penyerahan/save', $attr);
                                    echo form_hidden('id_daftar', $data->id);
                                    echo $link."&nbsp;";
                                    if($data->file_ttd)
                                        $img_icon = "status.png";
                                    else{
                                        $img_icon = "status-busy.png";
                                    }
                                    $img_status = array(
                                        'src' => base_url().'assets/images/icon/'.$img_icon,
                                        'alt' => 'Edit',
                                        'title' => 'Edit',
                                        'border' => '0',
                                    );
                                    if (strval($data->tmsk->c_status_salinan) !== '2'){
                                    echo img($img_status)."&nbsp;";
                                    echo "<input type='file' name='file_ttd' class = 'input-wrc' />";
                                    $add_daftar = array(
                                        'name' => 'submit',
                                        'class' => 'submit-wrc',
                                        'content' => 'Simpan',
                                        'type' => 'submit',
                                        'value' => 'ok'
                                    );
                                    echo form_submit($add_daftar);
                                    $confirm_text = 'Apakah Anda yakin salinan akan diserahkan?';
                                    $img_ya = array(
                                        'src' => 'assets/images/icon/tick.png',
                                        'alt' => 'Diserahkan',
                                        'title' => 'Diserahkan',
                                        'border' => '0',
                                        'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                    );
                                ?>
                                <a class="page-help" href="<?php echo site_url('dokumen/penyerahan/status/'.$data->tmsk->id) ?>"
                                ><?php if (strval($data->tmsk->c_status_salinan) !== '3') echo img($img_ya); ?></a>
                                <?php }
                                echo form_close(); ?>
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
    <br style="clear: both;" />
</div>
