<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <?php
            echo form_open(site_url('dokumen/penelusuran'));
        ?>
        <fieldset>
            <legend>Filter Dokumen</legend>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr><td colspan="4" height="10"></td></tr>
                <tr class="bg-grid">
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
                        echo form_label('Jenis Kegiatan');
                    ?>
                </td>
                <td width="40%">
                    <?php
                        $checked_kegiatan = TRUE;
                        foreach ($list_kegiatan as $row){
                            $opsi_kegiatan[$row->id] = $row->n_kegiatan;
                        }
                        if($cek_kegiatan) $checked_kegiatan = TRUE;
                        else $checked_kegiatan = FALSE;
                        $check_kegiatan = array(
                            'name' => 'cek_kegiatan',
                            'value' => 1,
                            'checked' => $checked_kegiatan
                        );
                        echo form_dropdown('jenis_kegiatan', $opsi_kegiatan, $jenis_kegiatan, 'class = "input-select-wrc"');
                        echo form_checkbox($check_kegiatan);
                    ?>
                </td>
                </tr>
                <tr>
                <td>
                    <?php
                        echo form_label('Investasi');
                    ?>
                </td>
                <td>
                    <?php
                        $checked_invest = TRUE;
                        foreach ($list_investasi as $row){
                            $opsi_investasi[$row->id] = $row->n_investasi;
                        }
                        if($cek_investasi) $checked_invest = TRUE;
                        else $checked_invest = FALSE;
                        $check_invest = array(
                            'name' => 'cek_investasi',
                            'value' => 1,
                            'checked' => $checked_invest
                        );
                        echo form_dropdown('jenis_investasi', $opsi_investasi, $jenis_investasi, 'class = "input-select-wrc"');
                        echo form_checkbox($check_invest);
                    ?>
                </td>
                <td>
                    <?php
                        echo form_label('Tahun Daftar');
                    ?>
                </td>
                <td>
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
                <tr class="bg-grid">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
                <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="penyerahan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			<th>Nama Pemilik Izin</th>
                        <th>Jenis Izin</th>
                        <th>No Surat Izin</th>
			<th>Tanggal Surat</th>
			<th>Status</th>
			<th>Index Dokumen</th>
			<th width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i=1;
                    foreach ($list as $data){
                        $data->tmpemohon->get();
                        $data->trperizinan->get();
                        $data->tmsk->get();
                        $data->tmpemohon->tmarchive->get();
                        $data->tmbap->get();
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id;?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon;?></td>
                        <td><?php echo $data->trperizinan->n_perizinan;?></td>
                        <td><?php echo $data->tmsk->no_surat;?></td>
			<td>
                        <?php
                        if($data->tmsk->tgl_surat){
                            if($data->tmsk->tgl_surat != '0000-00-00') echo $this->lib_date->mysql_to_human($data->tmsk->tgl_surat);
                        }
                        ?>
                        </td>
                        <td>
                        <?php
                        if($data->c_izin_dicabut) echo "Sudah dicabut";
                        else{
                            if($data->tmbap->status_bap == "1") echo "Diizinkan";
                            else if($data->tmbap->status_bap == "2") echo "Ditolak";
                            else echo "-";
                        }
                        ?>
                        </td>
                        <td align="center"><?php
                        $index_dokumen = anchor(site_url('dokumen/penelusuran/detail_index') .'/'. $data->tmpemohon->tmarchive->id, $data->tmpemohon->tmarchive->i_archive, 'class=isi');
                        if(empty($data->tmpemohon->tmarchive->id) || empty($data->tmpemohon->id)) $index_dokumen = "-";
                        echo "<b>".$index_dokumen."</b>"; ?></td>
                         <td><center>
                          <?php
                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                echo anchor(site_url('dokumen/penelusuran/detail') .'/'. $data->id, img($img_edit))."&nbsp;";
                            ?>
                             </center>
                         </td>
                    </tr>
                    <?php
                        $i++;
//                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
