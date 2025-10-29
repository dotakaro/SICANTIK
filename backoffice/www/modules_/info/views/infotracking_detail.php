<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
            <legend>Data Permohonan</legend>
            <div id="statusRail">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('No Pendaftaran');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    echo $daftar->pendaftaran_id;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Pemohon');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    $pemohon = $daftar->tmpemohon->get();
                    echo $pemohon->n_pemohon;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('Jenis Izin');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    $data_izin = $daftar->trperizinan->get();
                    echo $data_izin->n_perizinan;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail" >
                <?php
                    echo form_label('Jenis Permohonan');
                ?>
              </div>
              <div id="rightRail" >
                <?php
                    $jenis = $daftar->trjenis_permohonan->get();
                    echo form_label($jenis->n_permohonan);
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('Durasi Pengerjaan Izin');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    echo $data_izin->v_hari.' Hari';
                ?>
              </div>
            </div>
            <div style="text-align:right">
                <?php
                    $img_back = array(
                        'src' => 'assets/images/icon/back_alt.png',
                        'alt' => 'Back',
                        'title' => 'Back',
                        'border' => '0',
                    );
                    echo anchor(site_url('info/infotracking'), img($img_back))."&nbsp;";
                ?>
            </div>
        </fieldset>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" align="center" class="display" id="trackingdetail">
                <thead>
                    <tr>
                        <th width="3%">No</th>
                        <th width="40%">Menu</th>
                        <th width="20%">Durasi Pengerjaan</th>
                        <th width="19%">Waktu Awal</th>
                        <th width="19%">Waktu Akhir</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $displayedStatusId = array();
                $total_awal = NULL;
                $total_akhir = NULL;
                $total_hari = NULL;
//                    foreach ($list as $data){
                $showed = true;
                if (!empty($list_tracking)) {
                    foreach ($list_tracking as $data_track){
                        $status = '';
                        $waktu_awal = '';
                        $waktu_akhir = '';
//                        $data_status = new tmtrackingperizinan_trstspermohonan();
//                        $data_status->where('tmtrackingperizinan_id', $data_track->id)
//                        ->where('trstspermohonan_id', $data->id)->get();

//                        if($data_status->tmtrackingperizinan_id){
                            $showed = TRUE;
                            $status = $data_track->status;
                            $waktu_awal = $data_track->d_entry_awal;
                            $waktu_akhir = $data_track->d_entry;
//                            break;
//                        }


                    //$sts_track = $data->trstspermohonan->get();
                    //$sts_name = $sts_track->n_sts_permohonan;
                if(in_array($data_track->trstspermohonan->id, $displayedStatusId)){
                    $showed = false;
                }
                if(!$showed) {
                    break;
                }
                $displayedStatusId[] = $data_track->trstspermohonan->id;
                $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data_track->trstspermohonan->n_sts_permohonan; ?></td>
                        <td>
                        <?php
                            $hari = 0;
                            if($waktu_awal && $waktu_akhir){
                                if($waktu_awal != '0000-00-00 00:00:00' && $waktu_akhir != '0000-00-00 00:00:00'){
                                    $libur = new tmholiday();
                                    $libur
                                    ->distinct('date')
                                    ->where('date >=', $waktu_awal)
                                    ->where('date <=', $waktu_akhir)
                                    ->order_by('date', 'ASC')
                                    ->get();
                                    if($libur){
                                        foreach($libur as $data_libur){
                                            $hari = $hari + 86400;
                                        }
                                    }
                                    $time_akhir = strtotime($waktu_akhir);
                                    $time_awal = strtotime($waktu_awal);
                                    if($data->id == 1) echo "Menunggu konfirmasi pemohon";
                                    else if ($data->id == 13 || $data->id == 15 || $data->id == 16 || $data->id == 17) echo "Selesai";
                                    else if ($data->id == 12) echo "Telah dicetak";
                                    else{
                                        if($time_akhir === $time_awal) echo "Sedang Diproses";
                                        else{
                                            $time_awal = $time_awal + $hari;
                                            $total_hari = $total_hari + $hari;
                                            echo timespan($time_awal, $time_akhir);
                                        }
                                    }
                                }
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if($waktu_awal){
                                if($waktu_awal != '0000-00-00 00:00:00'){
                                    if($data_track->trstspermohonan->id == "1" || $data_track->trstspermohonan->id == "2"){
                                        $total_awal = $waktu_awal;
                                        /*$list_status = new trstspermohonan();
                                        $list_status->get();
                                        foreach ($list_status as $data_status){
                                            if($data_status->id == $data->id){
                                                $total_awal = $waktu_awal;
                                                break;
                                            }else $total_awal = NULL;
                                        }*/
                                    }
                                    echo $this->lib_date->mysql_to_human($waktu_awal, 1).', '.  substr($waktu_awal, 10);
                                }
                            }
                        ?>
                        </td>
                        <td>
                        <?php
                            if($waktu_akhir){
                                if($waktu_akhir != '0000-00-00'){
                                    echo $this->lib_date->mysql_to_human($waktu_akhir, 1).', '.  substr($waktu_akhir, 10);
                                    $total_akhir = $waktu_akhir;
                                }
                            }
                        ?>
                        </td>
                    </tr>
                <?php
                    }
                }else{
                    $status = '';
                    $waktu_awal = '';
                    $waktu_akhir = '';
                }
//                        }
//                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td><b>Total Pengerjaan</b></td>
                        <td><b>
                        <?php
//                                echo $total_awal."<br>";
//                                echo $total_akhir."<br>";
//                                echo $total_hari."<br>";
                            if($total_awal && $total_akhir){
                                $time_akhir2 = strtotime($total_akhir);
                                $time_awal2 = strtotime($total_awal);
                                $time_awal2 = $time_awal2 + $total_hari;
                                echo timespan($time_awal2, $time_akhir2);
                            }
                        ?>
                            </b></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
