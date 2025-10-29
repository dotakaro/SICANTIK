<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
    </div>
<!--        <div class="entry">
        <?php
            echo form_open(site_url('dokumen/pengajuan'));
        ?>
        <fieldset>
            <legend>Filter Dokumen</legend>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr><td colspan="4" height="10"></td></tr>
                <tr>
                <td width="10%">
                    <?php
                        echo form_label('Jenis Izin');
                    ?>
                </td>
                <td width="40%">
                    <?php
                        $checked_izin = TRUE;
                        foreach ($list_izin as $row){
                            $opsi_izin[$row->id] = $row->n_perizinan;
                        }
//                        if($cek_izin) $checked_izin = TRUE;
//                        else $checked_izin = FALSE;
                        $check_izin = array(
                            'name' => 'cek_izin',
                            'value' => 1,
                            'checked' => $checked_izin
                        );
                        echo form_dropdown('jenis_izin', $opsi_izin, $jenis_izin, 'class = "input-select-wrc"');
//                        echo form_checkbox($check_izin);
                    ?>
                </td>
                <td width="10%">
                    <?php
                        echo form_label('Tahun Daftar');
                    ?>
                </td>
                <td width="40%">
                    <?php
                        $year = new year();
                        $year->order_by('tahun', 'DESC')->get();
                        foreach ($year as $data){
                            $opsi_year[$data->tahun] = $data->tahun;
                        }
                        echo form_dropdown('year_id', $opsi_year, $year_id,'class = "input-select-wrc"');
                    ?>
                </td>
                </tr>
                <tr><td colspan="4" height="10"></td></tr>
                <tr><td colspan="4" align="center">
                <?php
                    echo form_submit('submit','Tampilkan','class="button-wrc"');
                ?>
                    </td></tr>
            </table>
        </fieldset>
        <?php
            echo form_close();
        ?>
        </div><br />-->
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('dokumen/pengajuan');
                  
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pengajuan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Pemegang Izin</th>
                        <th>Jenis Izin</th>
                        <th>Tgl Daftar</th>
                        <th>No Surat</th>
                        <th>Tgl Surat</th>
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
                        if($tgl_surat){
                            if($tgl_surat != '0000-00-00') echo $this->lib_date->mysql_to_human($tgl_surat);
                        }
                        ?>
                        </td>
                        <td>
                            <center>
                                <?php
                                $confirm_text = 'Apakah pemohon akan melakukan pengajuan?';
                                $img_edit = array(
                                    'src' => 'assets/images/icon/tick.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('dokumen/pengajuan/baru'."/".$rows['idsk']) ?>"
                                ><?php echo img($img_edit); ?></a>
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
//
//                            if(intval($data->tmsk->c_is_requested) === 0) {
//                        $bap = new tmbap();
//                        $bap->where_related($data)->get();
//                        if($bap->status_bap === $c_bap){
//                                $i++;
                                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->d_terima_berkas; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->tmsk->no_surat; ?></td>
                        <td><?php echo $data->tmsk->tgl_surat; ?></td>
                        <td width="50">
                            <center>
                                <?php
                                $confirm_text = 'Apakah pemohon akan melakukan pengajuan?';
                                $img_edit = array(
                                    'src' => 'assets/images/icon/tick.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('dokumen/pengajuan/baru'."/".$data->tmsk->id) ?>"
                                ><?php echo img($img_edit); ?></a>
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
