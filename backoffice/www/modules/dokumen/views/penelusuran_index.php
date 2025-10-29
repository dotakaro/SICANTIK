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
                    echo $data_pemohon->n_pemohon;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Index Dokumen');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    if(empty($index_dokumen)) $index_dokumen = "-";
                    echo $index_dokumen;
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
        <div class="entry">
            <div id="tabs2">
                <ul>
                    <li><a href="#tabs-2">Data Perizinan</a></li>
                </ul>
                <div id="tabs-2">
                    <?php
                    $permohonan = new tmpermohonan();
                    $permohonan
                    ->where_related($data_pemohon)
                    ->where('c_izin_selesai', 1) // 1 -> izin yg selesai
                    ->where('c_izin_dicabut', 0) // 0 -> izin yg berlaku
                    ->order_by('id DESC')->get();

                    $x = 1;
                    foreach($permohonan as $data_permohonan){
                        $show = FALSE;
                        if($list_bap){
                            foreach ($list_bap as $data_bap){
                                $data_sk = new tmbap_tmpermohonan();
                                $data_sk->where('tmpermohonan_id', $data_permohonan->id)
                                ->where('tmbap_id', $data_bap->id)->get();
                                if($data_sk->tmpermohonan_id){
                                    if($data_bap->status_bap == $c_bap){
                                        $show = TRUE;
                                        break;
                                    }else{
                                        $show = FALSE;
                                        break;
                                    }
                                }
                            }
                        }else{
                            $show = FALSE;
                            break;
                        }
                        if($show){
                            
                        $paralel_id = $data_permohonan->c_paralel;
                        $paralel_jenis = new trparalel();
                        $paralel_jenis->get_by_id($paralel_id);
                        $daftar = new tmpermohonan();
//                        if($paralel_id) $pendaftaran = $daftar->where('c_paralel', $paralel_id)->get();
//                        else
                            $pendaftaran = $daftar->get_by_id($data_permohonan->id);
                        echo "<hr><b class=isi>".$x.". ".anchor(site_url('dokumen/penelusuran/detail') .'/'. $pendaftaran->id, $pendaftaran->pendaftaran_id)."</b><hr>";
                        $x++;

                        foreach($pendaftaran as $data){
                            $i = 1;
                            $data->trperizinan->get();
                            echo "<b>".$data->trperizinan->n_perizinan."</b>";
                    ?>
                    <table cellpadding="0" cellspacing="0" border="1" align="center" class="display" style="margin: 10px 0 20px 0;">
                        <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th width="72%">Nama Dokumen</th>
                                <th width="20%">No Dokumen</th>
                                <th width="5%">Aksi</th>
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
                                        'alt' => 'Cetak BAP',
                                        'title' => 'Cetak Berita Acara Pemeriksaan',
                                        'border' => '0',
                                    );
                                if($data->trperizinan->id === 2) $url_bap = "permohonan/bap/cetakBAP2";
                                else $url_bap = "permohonan/bap/cetakBAP";
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
                                <td><?php echo "Surat Keputusan"; ?></td>
                                <td align="center"><?php echo $sk->no_surat; ?></td>
                                <td align="center"><?php
                                    if($bap->status_bap === "1"){
                                        $img_sk = array(
                                            'src' => base_url().'assets/images/icon/clipboard.png',
                                            'alt' => 'Cetak SK',
                                            'title' => 'Cetak SK',
                                            'border' => '0',
                                        );
                                        echo anchor(site_url('permohonan/sk/cetak') .'/'. $data->id, img($img_sk))."&nbsp;";
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
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br style="clear: both;" />
</div>
