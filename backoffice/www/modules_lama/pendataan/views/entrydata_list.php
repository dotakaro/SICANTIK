<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('pendataan');
                 
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
            <?php if (isset($warning)) {?>
            <p align="center" style="font-weight: bold; color: red"><?php echo $warning; ?></p>
            <br>
            <?php } ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendataan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			<th>Pemohon</th>
                        <th>Jenis Izin</th>
                        <th>Jenis Permohonan</th>
			<th>Tanggal Permohonan</th>
			<th>Status</th>
			<th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
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
                                $idproperty = NULL;
                                $query_data = "SELECT tmproperty_jenisperizinan_id idproperty
                                FROM tmpermohonan_tmproperty_jenisperizinan
                                WHERE tmpermohonan_id = '".$rows['id']."'";
                                $hasil_data = mysql_query($query_data);
                                $rows_data = mysql_fetch_object(@$hasil_data);
                                @$idproperty = $rows_data->idproperty;
                                $entry_data = new tmpermohonan_tmproperty_jenisperizinan();
                                $jumlah_entry = $entry_data->where('tmpermohonan_id', $rows['id'])->count();
                                if($jumlah_entry) {
                                    if($rows['id_lama']) echo "Data Lama";
                                    else echo "<b>Sudah di-entry</b>";
                                } else {
                                    echo "Belum di-entry";
                                }
                            ?>
                        </td>
                         <td><center>
                          <?php
                                $img_parent = array(
                                    'src' => base_url().'assets/images/icon/information.png',
                                    'alt' => 'Data Awal',
                                    'title' => 'Data Awal',
                                    'border' => '0',
                                );
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $data_jenis = $rows['idjenis'];
                                if($data_jenis == "1") $url_awal = site_url('pelayanan/pendaftaran/edit');
                                else if($data_jenis == "2") $url_awal = site_url ('pendaftaran/edit/2');
                                else if($data_jenis == "3") $url_awal = site_url ('pendaftaran/edit/3');
                                else if($data_jenis == "4") $url_awal = site_url ('pendaftaran/edit/4');
                                echo anchor($url_awal .'/'. $rows['id'] .'/1', img($img_parent))
                                     ."&nbsp;";
                                echo anchor(site_url('pendataan/edit') .'/'. $rows['id'], img($img_edit))
                                     ."&nbsp;";
                                $img_recom = array(
                                    'src' => base_url().'assets/images/icon/clipboard-doc.png',
                                    'alt' => 'Buat Permohonan Rekomendasi',
                                    'title' => 'Buat Permohonan Rekomendasi',
                                    'border' => '0',
                                );
                                $kelompok = new trkelompok_perizinan_trperizinan();
                                $kelompok->where('trperizinan_id', $rows['idizin'])->get();
                                if($kelompok->trkelompok_perizinan_id == '1')
                                echo anchor(site_url('pelayanan/rekomendasi/edit') .'/'. $rows['id'], img($img_recom))."&nbsp;";
                          ?></center>
                        </td>
                    </tr>
                    <?php
                        $i++;
                    }
                    ?>
                <?php
//                    $i=1;
//
//                    foreach ($list as $data){
//                        $data->tmpemohon->get();
//                        $data->trperizinan->get();
//                        $data->trjenis_permohonan->get();
//                        foreach ($list_izin as $listizin) {
//                            $showed = FALSE;
//                            if($listizin->id === $data->trperizinan->id) {
//                                $showed = TRUE;
//                            }
//
//                            if($showed) {
//                                $entry = new tmproperty_jenisperizinan();
//                                $entry->where_related($data)->get();

                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id;?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon;?></td>
                        <td><?php echo $data->trperizinan->n_perizinan;?></td>
                        <td><?php echo $data->trjenis_permohonan->n_permohonan;?></td>
                        <td>-->
                        <?php
//                        if($data->trjenis_permohonan->id == 1) $tgl_permohonan = $data->d_terima_berkas;
//                        else if($data->trjenis_permohonan->id == 2) $tgl_permohonan = $data->d_perubahan;
//                        else if($data->trjenis_permohonan->id == 3) $tgl_permohonan = $data->d_perpanjangan;
//                        else if($data->trjenis_permohonan->id == 4) $tgl_permohonan = $data->d_daftarulang;
//                        if($tgl_permohonan){
//                            if($tgl_permohonan != '0000-00-00') echo $this->lib_date->mysql_to_human($tgl_permohonan);
//                        }
                        ?>
<!--                        </td>
                        <td>-->
                            <?php
//                                if($entry->id) {
//                                    $kelompok = $data->trperizinan->trkelompok_perizinan->get();
////                                    if($kelompok->id == "1") $id_status = "7"; //Penetapan Izin [Lihat Tabel trstspermohonan()]
////                                    else if($kelompok->id == "3") $id_status = "7"; //Penetapan Izin [Lihat Tabel trstspermohonan()]
////                                    else if($kelompok->id == "2" || $kelompok->id == "4") $id_status = "4"; //Survey Lokasi [Lihat Tabel trstspermohonan()]
////                                    $status_izin = $data->trstspermohonan->get();
////                                    if($status_izin->id >= $id_status)
//                                    if($data->id_lama) echo "Data Lama";
//                                    else echo "<b>Sudah di-entry</b>";
//                                } else {
//                                    echo "Belum di-entry";
//                                }
                            ?>
<!--                        </td>
                         <td><center>-->
                          <?php
//                                $img_parent = array(
//                                    'src' => base_url().'assets/images/icon/information.png',
//                                    'alt' => 'Data Awal',
//                                    'title' => 'Data Awal',
//                                    'border' => '0',
//                                );
//                                $img_edit = array(
//                                    'src' => base_url().'assets/images/icon/property.png',
//                                    'alt' => 'Edit',
//                                    'title' => 'Edit',
//                                    'border' => '0',
//                                );
//                                $data_jenis = $data->trjenis_permohonan->id;
//                                if($data_jenis == "1") $url_awal = site_url('pelayanan/pendaftaran/edit');
//                                else if($data_jenis == "2") $url_awal = site_url ('pendaftaran/edit/2');
//                                else if($data_jenis == "3") $url_awal = site_url ('pendaftaran/edit/3');
//                                else if($data_jenis == "4") $url_awal = site_url ('pendaftaran/edit/4');
//                                echo anchor($url_awal .'/'. $data->id .'/1', img($img_parent))
//                                     ."&nbsp;";
//                                echo anchor(site_url('pendataan/edit') .'/'. $data->id, img($img_edit))
//                                     ."&nbsp;";
//                                $img_recom = array(
//                                    'src' => base_url().'assets/images/icon/clipboard-doc.png',
//                                    'alt' => 'Buat Permohonan Rekomendasi',
//                                    'title' => 'Buat Permohonan Rekomendasi',
//                                    'border' => '0',
//                                );
//                                $izin_kelompok = $data->trperizinan->trkelompok_perizinan->get();
//                                if($izin_kelompok->id == '1')
//                                echo anchor(site_url('pelayanan/rekomendasi/edit') .'/'. $data->id, img($img_recom))."&nbsp;";
                            ?>
<!--                        </center>
                        </td>
                    </tr>-->
                <?php
//                                $i++;
//                                break;
//                            }
//                        }
//
//                        }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
