<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset>
            <legend>Data Permohonan</legend>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Nama Pemegang Izin');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    $data_permohonan->tmpemohon->get();
                    echo $data_permohonan->tmpemohon->n_pemohon;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('No Pendaftaran');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    echo $data_permohonan->pendaftaran_id;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('Nama Perusahaan');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    $data_permohonan->tmperusahaan->get();
                    echo $data_permohonan->tmperusahaan->n_perusahaan;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('Index Dokumen');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    $link_dokumen = anchor(site_url('dokumen/penelusuran/detail_index') .'/'. $index_dokumen->id, $index_dokumen->i_archive, 'class=isi');
                    if(empty($index_dokumen->id)) $link_dokumen = "-";
                    echo $link_dokumen;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Nama Penandatangan');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    echo $data_permohonan->nama_ttd. " / ".$data_permohonan->nip_ttd;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('File Tandatangan (*.png)');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    $attr = array('id' => 'form');
                    echo form_open_multipart('dokumen/penelusuran/save_ttd', $attr);
                    echo form_hidden('id_daftar', $data_permohonan->id);
                    $file_ttd = array(
                        'name' => 'file_ttd',
                        'value' => '',
                        'class' => 'input-wrc'
                    );
                    if($data_permohonan->file_ttd){
                        $img_cetak = array(
                            'src' => base_url() . 'assets/images/icon/clipboard.png',
                            'alt' => 'File Doc',
                            'title' => 'File Doc',
                            'border' => '0',
                        );
                        $linkttd = anchor($pathttd . $data_permohonan->file_ttd . '/' , img($img_cetak), "target='_blank'");
                        echo $linkttd;
                        $img_icon = "status.png";
                    }else{
                        $img_icon = "status-busy.png";
                    }
                    $img_status = array(
                        'src' => base_url().'assets/images/icon/'.$img_icon,
                        'alt' => 'Edit',
                        'title' => 'Edit',
                        'border' => '0',
                    );
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
                    echo form_close();
                ?>
              </div>
            </div>
            <div style="text-align:right">
                <?php
                    echo form_close();
                    $img_back = array(
                        'src' => 'assets/images/icon/back_alt.png',
                        'alt' => 'Back',
                        'title' => 'Back',
                        'border' => '0',
                    );
                    echo anchor(site_url('dokumen/penelusuran'), img($img_back))."&nbsp;";
                ?>
            </div>
        </fieldset>
        </div>
        <?php
            $paralel_id = $data_permohonan->c_paralel;
            $paralel_jenis = new trparalel();
            $paralel_jenis->get_by_id($paralel_id);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Persyaratan Perizinan</a></li>
                </ul>
                <div id="tabs-1">
                    <table cellpadding="0" cellspacing="0" border="1" align="center" class="display">
                        <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th width="78%">Nama Syarat Izin</th>
                                <th width="10%">Status</th>
                                <th width="10%">Terpenuhi</th>
<!--                                <th width="25%">Upload (*.doc/*.jpg)</th>-->
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $perizinan = new trperizinan();
//                            if($paralel_id) $jenis_izin = $perizinan->where_related($paralel_jenis)->get();
//                            else
                            $jenis_izin = $perizinan->get_by_id($data_permohonan->trperizinan->id);
                            $syarat_perizinan = new trsyarat_perizinan();
                            $syarat_perizinan->where_related($jenis_izin)->get();

//                            if($paralel_id){
//                                $i = 0;
//                                $x = 1;
//                                $data_izin = 0;
//                                foreach ($jenis_izin as $row){
//                                    if($x == 1) $data_izin = $row->id;
//                                    else $data_izin = $data_izin.", ".$row->id;
//                                    $x++;
//                                }
//                                $query = "select distinct(trsyarat_perizinan_id)
//                                    from trperizinan_trsyarat_perizinan
//                                    where trperizinan_id IN(".$data_izin.") ";
//                                $results = mysql_query($query);
//                                while ($rows = mysql_fetch_assoc(@$results)){
//                                    $i++;
//                                    $syarat_perizinan = new trsyarat_perizinan();
//                                    $data = $syarat_perizinan->get_by_id($rows['trsyarat_perizinan_id']);
//                        ?>
<!--                            <tr>
                                <td valign="top"><?php echo $i; ?></td>
                                <td valign="top"><?php echo $data->v_syarat; ?></td>
                                <td align="center" valign="top"><?php if($data->status === "1") echo "Wajib"; else echo "Tidak Wajib"; ?></td>
                                <td align="center" valign="top">
                                <?php
                                ?>
                                </td>
                            </tr>-->
                        <?php
//                                }
//                            }else{
                                $i = 0;
                                foreach ($syarat_perizinan as $data){
                                    $i++;
                        ?>
                            <tr>
                                <td valign="top"><?php echo $i; ?></td>
                                <td valign="top"><?php echo $data->v_syarat; ?></td>
                                <td align="center" valign="top"><?php if($data->status === "1") echo "Wajib"; else echo "Tidak Wajib"; ?></td>
                                <td align="center" valign="top">
                                <?php
                                    $img_icon = "status-busy.png";
                                    if($data_permohonan){
                                        foreach ($data_permohonan as $data_daftar){
                                            $data_syarat = new tmpermohonan_trsyarat_perizinan();
                                            $data_syarat->where('tmpermohonan_id', $data_daftar->id)
                                            ->where('trsyarat_perizinan_id', $data->id)->get();
                                            if($data_syarat->trsyarat_perizinan_id){
                                                $img_icon = "status.png";
                                                break;
                                            }
                                        }
                                    }else{
                                        $img_icon = "status-busy.png";
                                        break;
                                    }
                                    $img_status = array(
                                        'src' => base_url().'assets/images/icon/'.$img_icon,
                                        'alt' => 'Status',
                                        'title' => 'Status',
                                        'border' => '0',
                                    );
                                    echo img($img_status);
                                ?>
                                </td>
<!--                                <td align="right" valign="top">
                                    <?php
//                                        $attr = array('id' => 'form');
//                                        echo form_open_multipart('dokumen/penelusuran/save_syarat', $attr);
//                                        echo form_hidden('id_daftar', $data_permohonan->id);
//                                        echo form_hidden('id_syarat', $data->id);
//                                        $archive_syarat = new tmarchive_syarat();
//                                        $archive_syarat
//                                        ->where('tmpermohonan_id', $data_permohonan->id)
//                                        ->where('trsyarat_perizinan_id', $data->id)
//                                        ->get();
//                                        if($archive_syarat->file_doc){
//                                            $img_cetak = array(
//                                                'src' => base_url() . 'assets/images/icon/clipboard.png',
//                                                'alt' => 'File Doc',
//                                                'title' => 'File Doc',
//                                                'border' => '0',
//                                            );
//                                            $link = anchor($pathfile . $archive_syarat->file_doc . '/' , img($img_cetak), "target='_blank'");
//                                            echo $link;
//                                            $img_icon = "status.png";
//                                        }else{
//                                            $img_icon = "status-busy.png";
//                                        }
//                                        $img_status = array(
//                                            'src' => base_url().'assets/images/icon/'.$img_icon,
//                                            'alt' => 'Status',
//                                            'title' => 'Status',
//                                            'border' => '0',
//                                        );
//                                        echo img($img_status)."&nbsp;";
//                                        echo "<input type='file' name='file_syarat' class = 'input-wrc' />";
//                                        $add_daftar = array(
//                                            'name' => 'submit',
//                                            'class' => 'submit-wrc',
//                                            'content' => 'Simpan',
//                                            'type' => 'submit',
//                                            'value' => 'ok'
//                                        );
//                                        echo form_submit($add_daftar);
//                                        echo form_close();
                                    ?>
                                </td>-->
                            </tr>
                        <?php
                                }
//                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="entry">
            <div id="tabs2">
                <ul>
                    <li><a href="#tabs-2">Data Perizinan</a></li>
                </ul>
                <div id="tabs-2">
                    <?php
                        $daftar = new tmpermohonan();
//                        if($paralel_id) $pendaftaran = $daftar->where('c_paralel', $paralel_id)->get();
//                        else
                            $pendaftaran = $daftar->get_by_id($data_permohonan->id);

                        foreach($pendaftaran as $data){
                            $i = 1;
                            $data->trperizinan->get();
                            echo "<b>".$data->trperizinan->n_perizinan."</b>";
                    ?>
                    <table cellpadding="0" cellspacing="0" border="1" align="center" class="display" style="margin: 10px 0 20px 0;">
                        <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th width="47%">Nama Dokumen</th>
                                <th width="20%">No Dokumen</th>
                                <th width="5%">Aksi</th>
                                <th width="25%">Scan Dokumen (*.jpg)</th>
                            </tr>
                        </thead>
                        <tbody>
<!--                            <tr>
                                <td><?php //echo $i++; ?></td>
                                <td><?php //echo "Tanda Terima Pendaftaran"; ?></td>
                                <td align="center"><?php //echo $data->pendaftaran_id; ?></td>
                                <td align="center"><?php
                                    $img_bukti = array(
                                        'src' => base_url().'assets/images/icon/clipboard.png',
                                        'alt' => 'Cetak Bukti',
                                        'title' => 'Cetak Bukti',
                                        'border' => '0',
                                    );
                                //echo anchor(site_url('pelayanan/pendaftaran/cetak_bukti') .'/'. $data->id, img($img_bukti))."&nbsp;";
                                ?></td>
                            </tr>-->
                            <?php
                                $data_bap = new tmbap_tmpermohonan();
                                $data_bap->where('tmpermohonan_id', $data->id)->get();
                                if($data_bap->tmbap_id){
                                    $bap = new tmbap();
                                    $bap->get_by_id($data_bap->tmbap_id);
                            ?>
<!--                            <tr>
                                <td><?php //echo $i++; ?></td>
                                <td><?php //echo "Berita Acara Pemeriksaan"; ?></td>
                                <td align="center"><?php //echo $bap->bap_id; ?></td>
                                <td align="center"><?php
                                    $img_bap = array(
                                        'src' => base_url().'assets/images/icon/clipboard.png',
                                        'alt' => 'Cetak Berita Acara Pemeriksaan',
                                        'title' => 'Cetak Berita Acara Pemeriksaan',
                                        'border' => '0',
                                    );
                                if($data->trperizinan->id === 2) $url_bap = "permohonan/bap/cetakBAP2_archive";
                                else $url_bap = "permohonan/bap/cetakBAP_archive";
                                //echo anchor(site_url($url_bap) .'/'. $data->id.'/'.$data->trperizinan->id, img($img_bap))."&nbsp;";
                                ?></td>
                            </tr>-->
                            <?php
                                    if($bap->c_skrd){
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo "SKRD"; ?></td>
                                <td align="center"><?php echo $bap->no_skrd; ?></td>
                                <td align="center"><?php
                                    $img_skrd = array(
                                        'src' => base_url().'assets/images/icon/clipboard.png',
                                        'alt' => 'Cetak SKRD',
                                        'title' => 'Cetak SKRD',
                                        'border' => '0',
                                    );
                                if($data->trperizinan->id === 2 || $data->trperizinan->id === 3){
                                    $url_skrd = "permohonan/skrd/cetakSKRDimb_archive";
                                    $url_skrd_salinan = "permohonan/skrd/cetakSKRDimb_archive";
                                }
                                elseif($data->trperizinan->id === 1){
                                    $url_skrd = "permohonan/skrd/cetakSKRDgeneric_archive";
                                    $url_skrd_salinan = "permohonan/skrd/cetakSKRDgeneric_archive";
                                }
                                else{
                                    $url_skrd = "permohonan/skrd/cetakSKRD_archive";
                                    $url_skrd_salinan = "permohonan/skrd/cetakSKRD_archive";
                                }

                                echo anchor(site_url($url_skrd) .'/'. $data->id.'/'.$data->trperizinan->id, img($img_skrd))."&nbsp;";
                                ?></td>
                                <td align="right" valign="top">
                                    <?php
                                        $attr = array('id' => 'form');
                                        echo form_open_multipart('dokumen/penelusuran/save_skrd', $attr);
                                        echo form_hidden('id_daftar', $data_permohonan->id);
                                        echo form_hidden('id_surat', $bap->id);
                                        if($bap->file_doc){
                                            $img_cetak = array(
                                                'src' => base_url() . 'assets/images/icon/clipboard.png',
                                                'alt' => 'File Doc',
                                                'title' => 'File Doc',
                                                'border' => '0',
                                            );
                                            $link = anchor($pathfile . $bap->file_doc . '/' , img($img_cetak), "target='_blank'");
                                            echo $link;
                                            $img_icon = "status.png";
                                        }else{
                                            $img_icon = "status-busy.png";
                                        }
                                        $img_status = array(
                                            'src' => base_url().'assets/images/icon/'.$img_icon,
                                            'alt' => 'Status',
                                            'title' => 'Status',
                                            'border' => '0',
                                        );
                                        echo img($img_status)."&nbsp;";
                                        echo "<input type='file' name='file_doc' class = 'input-wrc' />";
                                        $add_daftar = array(
                                            'name' => 'submit',
                                            'class' => 'submit-wrc',
                                            'content' => 'Simpan',
                                            'type' => 'submit',
                                            'value' => 'ok'
                                        );
                                        echo form_submit($add_daftar);
                                        echo form_close();
                                    ?>
                                </td>
                            </tr>
                            <?php
                                    }
                                }
                                $data_sk = new tmpermohonan_tmsk();
                                $data_sk->where('tmpermohonan_id', $data->id)->get();
                                if($data_sk->tmsk_id){
                                    $sk = new tmsk();
                                    $sk->get_by_id($data_sk->tmsk_id);
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo "Surat Izin"; ?></td>
                                <td align="center"><?php echo $sk->no_surat; ?></td>
                                <td align="center"><?php
                                    if($bap->status_bap === "1"){
                                        $img_sk = array(
                                            'src' => base_url().'assets/images/icon/clipboard.png',
                                            'alt' => 'Cetak Surat Izin',
                                            'title' => 'Cetak Surat Izin',
                                            'border' => '0',
                                        );
                                        echo anchor(site_url('permohonan/sk/cetak_archive') .'/'. $data->id, img($img_sk))."&nbsp;";
                                    }else{
                                        $img_sk = array(
                                            'src' => base_url().'assets/images/icon/clipboard.png',
                                            'alt' => 'Cetak SK Ditolak',
                                            'title' => 'Cetak SK Ditolak',
                                            'border' => '0',
                                        );
                                        echo anchor(site_url('permohonan/skditolak/cetak') .'/'. $data->id, img($img_sk))."&nbsp;";
                                    }
                                ?></td>
                                <td align="right" valign="top">
                                    <?php
                                        $attr = array('id' => 'form');
                                        echo form_open_multipart('dokumen/penelusuran/save_izin', $attr);
                                        echo form_hidden('id_daftar', $data_permohonan->id);
                                        echo form_hidden('id_surat', $sk->id);
                                        if($sk->file_doc){
                                            $img_cetak = array(
                                                'src' => base_url() . 'assets/images/icon/clipboard.png',
                                                'alt' => 'File Doc',
                                                'title' => 'File Doc',
                                                'border' => '0',
                                            );
                                            $link = anchor($pathfile . $sk->file_doc . '/' , img($img_cetak), "target='_blank'");
                                            echo $link;
                                            $img_icon = "status.png";
                                        }else{
                                            $img_icon = "status-busy.png";
                                        }
                                        $img_status = array(
                                            'src' => base_url().'assets/images/icon/'.$img_icon,
                                            'alt' => 'Status',
                                            'title' => 'Status',
                                            'border' => '0',
                                        );
                                        echo img($img_status)."&nbsp;";
                                        echo "<input type='file' name='file_doc' class = 'input-wrc' />";
                                        $add_daftar = array(
                                            'name' => 'submit',
                                            'class' => 'submit-wrc',
                                            'content' => 'Simpan',
                                            'type' => 'submit',
                                            'value' => 'ok'
                                        );
                                        echo form_submit($add_daftar);
                                        echo form_close();
                                    ?>
                                </td>
                            </tr>
                            <?php
                                }
                                if($data->c_status_bayar){
                            ?>
<!--                            <tr>
                                <td><?php //echo $i++; ?></td>
                                <td><?php //echo "Kwitansi Pembayaran"; ?></td>
                                <td align="center"><?php
                                    $no_daftar = $data->pendaftaran_id;
                                    $data_izin = $data->trperizinan->id;
                                    $i_izin = strlen($data_izin);
                                    for($i=3;$i>$i_izin;$i--){
                                        $data_izin = "0".$data_izin;
                                    }
                                    $data_izin = 'K'.$data_izin;
                                    $no_kwitansi = substr($no_daftar, 0, 6).$data_izin.substr($no_daftar, 9, 11);
                                    //echo $no_kwitansi; ?></td>
                                <td align="center"><?php
                                    $img_kasir = array(
                                        'src' => base_url().'assets/images/icon/clipboard.png',
                                        'alt' => 'Cetak Bayar Kwitansi',
                                        'title' => 'Cetak Bayar Kwitansi',
                                        'border' => '0',
                                    );
                                //echo anchor(site_url('kasir/cetak') .'/'. $data->id, img($img_kasir))."&nbsp;";
                                ?></td>
                            </tr>-->
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br style="clear: both;" />
</div>
