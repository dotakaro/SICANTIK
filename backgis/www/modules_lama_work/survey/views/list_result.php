<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
                <legend>Filter Data</legend>
            <?php echo form_open('survey/result');
                 
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="survey">
                <thead>
                    <tr>
                        <th>No</th>
                        <th width="180">No Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis Izin</th>
                        <th>Jenis Permohonan</th>
                        <th>Tanggal Permohonan</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Tim Teknis</th>
                        <th>Status</th>
                        <th width="50">Aksi</th>
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
                        if(in_array($idkelompok,$arr_izin_tinjauan)){
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
                                if($rows['status_tinjauan'] == 1) {
                                    echo "<b>Sudah di-update</b>";
                                } else {
                                    echo "Belum di-update";
                                }
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
                                <!--<a class="page-help" href="<?php echo site_url('survey/resultUpdate'."/".$rows['id']) ?>"
                                ><?php echo img($img_edit); ?></a>-->
                                <a class="page-help" href="<?php echo site_url('survey/resultUpdate'."/".$rows['tim_teknis_id']) ?>"
                                ><?php echo img($img_edit); ?></a>
                            </center>
                        </td>
                    </tr>
                    <?php
                        $i++;
                        }
                    }
                    ?>
                <?php
//                    $i = NULL;
//                    foreach ($list as $data) {
//                        $data->tmpemohon->get();
//                        $data->trperizinan->get();
//                        $data->trjenis_permohonan->get();
//                        $kelompok = $data->trperizinan->trkelompok_perizinan->get();
//                        if($kelompok->id == 2 || $kelompok->id == 4){
//                        foreach ($list_izin as $listizin) {
//                            $showed = FALSE;
//                            if($listizin->id === $data->trperizinan->id) {
//                                $showed = TRUE;
//                                //Cek Data Entry
//                                $data_property = new tmpermohonan_tmproperty_jenisperizinan();
//                                $data_property->where('tmpermohonan_id', $data->id)->get();
//                                if($data_property->id) $showed = TRUE;
//                                else $showed = FALSE;
//                            }
//
//                            if($showed) {
//                                $i++;
                                ?>
<!--                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $data->pendaftaran_id; ?></td>
                                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                                        <td><?php echo $data->trjenis_permohonan->n_permohonan;?></td>
                                        <td><?php
                                        if($data->trjenis_permohonan->id == 1) $tgl_permohonan = $data->d_terima_berkas;
                                        else if($data->trjenis_permohonan->id == 2) $tgl_permohonan = $data->d_perubahan;
                                        else if($data->trjenis_permohonan->id == 3) $tgl_permohonan = $data->d_perpanjangan;
                                        else if($data->trjenis_permohonan->id == 4) $tgl_permohonan = $data->d_daftarulang;
                                        if($tgl_permohonan){
                                            if($tgl_permohonan != '0000-00-00') echo $this->lib_date->mysql_to_human($tgl_permohonan);
                                        }
                                        ?></td>
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
                                                if($data->c_tinjauan === "1") {
                                                    echo "<b>Sudah di-update</b>";
                                                } else {
                                                    echo "Belum di-update";
                                                }
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
                                                <a class="page-help" href="<?php echo site_url('survey/resultUpdate'."/".$data->id) ?>"
                                                ><?php echo img($img_edit); ?></a>
                                            </center>
                                        </td>
                                    </tr>-->
                                <?php
//                                break;
//                            }
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
