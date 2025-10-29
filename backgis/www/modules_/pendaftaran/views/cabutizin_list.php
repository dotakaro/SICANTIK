<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <?php
            echo form_open(site_url('pendaftaran/cabutizin/edit'));
        ?>
        <fieldset id="half">
            <legend>Data Permohonan</legend>
            <div class="contentForm">
                <?php
                    $no_input = array(
                        'name' => 'no_surat',
                        'value' => '',
                        'class' => 'input-wrc'
                    );
                    echo form_label('No Surat Izin');
                    echo form_input($no_input);
                ?>
            </div>
            <div class="contentForm">
                <?php
//                    foreach ($list_izin as $row){
//                        $opsi_izin[$row->id] = $row->n_perizinan;
//                    }
//
//                    echo form_label('Jenis Izin','name_jenis_izin');
//                    echo form_dropdown('jenis_izin', $opsi_izin, '','class = "input-select-wrc" id="izin_id"');
                ?>
            </div>
            <div class="contentForm" id="show_daftar_izin">
                <?php
//                    $year = new year();
//                    $year->order_by('tahun', 'DESC')->get();
//                    foreach ($year as $data){
//                        $opsi_year[$data->tahun] = $data->tahun;
//                    }
//                    echo form_label('Tahun Surat Izin');
//                    echo form_dropdown('year', $opsi_year, '','class = "input-select-wrc" id="year_id"');
                ?>
                <br style="clear: both;" />
                <div class="contentForm" id="show_list_izin">
                <?php
//                    $perizinan = new trperizinan();
//                    $perizinan->get_by_id($jenis_izin_id);
//                    $pencabutan = new tmpermohonan();
//                    $pencabutan->where_related($perizinan)
//                            ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                            ->where('c_izin_selesai', 1) //SK Sudah diserahkan
//                            ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                            ->order_by('id','ASC')->limit(0)->get();
//
//                    $c_bap = "1";
//
//                    if($pencabutan->id){
//                        foreach ($pencabutan as $row){
//                            $bap = new tmbap();
//                            $bap->where_related($row)->get();
//                            if($bap->status_bap === $c_bap){
//                                $row->tmsk->get();
//                                if($row->tmsk->no_surat)
//                                $opsi_daftar[$row->id] = $row->tmsk->no_surat;
//                            }else{
//                                $opsi_daftar[0] = '-';
//                            }
//                        }
//                    }else{
//                            $opsi_daftar[0] = '-';
//                    }
//
//                    echo form_label('No Surat Izin');
//                    echo form_dropdown('no_daftar', $opsi_daftar, '','class = "input-select-wrc" id="no_daftar" multiple="multiple"');
                ?>
                </div>
            </div>
            <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
                <?php
                    echo form_submit('daftar','Pencabutan','class="button-wrc"');
                ?>
              </div>
            </div>
        </fieldset>
        <?php
            echo form_close();
        ?>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendaftaran">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="20%">No Pendaftaran</th>
                        <th width="20%">Jenis Izin</th>
                        <th width="15%">Pemohon</th>
                        <th width="20%">Alamat</th>
                        <th width="15%">Jenis Permohonan</th>
                        <th width="8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    foreach ($list as $data){
                        $i++;
                        $data->trperizinan->get();
                        $izin_kelompok = $data->trperizinan->trkelompok_perizinan->get();
                        $data->tmpemohon->get();
                        $data->trjenis_permohonan->get();
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->tmpemohon->a_pemohon; ?></td>
                        <td>
                        <?php
                        echo $data->trjenis_permohonan->n_permohonan;
                        ?>
                        </td>
                        <td>
                            <?php
                                $img_bukti = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak Bukti',
                                    'title' => 'Cetak Bukti',
                                    'border' => '0',
                                );
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/information.png',
                                    'alt' => 'View',
                                    'title' => 'View',
                                    'border' => '0',
                                );
                                echo anchor(site_url('pendaftaran/cabutizin/cetak_bukti') .'/'. $data->id, img($img_bukti))."&nbsp;";
                                echo anchor(site_url('pendaftaran/cabutizin/edit2') .'/'. $data->id, img($img_edit))."&nbsp;";
                            ?>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Jenis Izin</th>
                        <th>Pemohon</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
