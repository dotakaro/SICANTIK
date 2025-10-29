<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>

     <fieldset>
         <legend style="color: #045000" align="bottom">
             <?php
            echo 'Rekapitulasi Izin Tercetak Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
            ?>
          </legend>
         <table align=left>
                <tr>
                    <td align="center">
                   <?php
                            $Back_data = array(
                           'src' => base_url().'assets/images/icon/back_alt.png',
                            'alt' => 'Lihat di HTML to Openoffice',
                            'title' => 'Kembali',
                            'onclick' => 'parent.location=\''. site_url('rekapitulasi/lap_izin'). '\''
                            );
                            echo img($Back_data);
                    ?>
                     <?php
                  $img_cetak = array(
                                    'src' => base_url().'assets/images/icon/print.png',
                                    'alt' => 'Selesai',
                                    'title' => 'View Report with OpenOffice',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/lap_izin/cetak').'/'.$tgla.'/'.$tglb. '\''
                                );

                                echo img($img_cetak);
                 ?>
                    </td>
                </tr>
            </table>
            <?php
                echo form_close();
            ?>
       <br>
       <table align="center" width="800" border="1" class="display" cellpadding="1" cellspacing="0" id="rev">
            <tr class="title">
                <td align="center"><font  size="1" color="#1A1A1A"><b>No</b></font></td>
                <td align="center" width="140"><font  size="1" color="#1A1A1A"><b>No Pendaftaran</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Tanggal Peninjauan</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Tanggal Penetapan</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Nama & Alamat Pemohon</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Nama Perusahaan & Lokasi Izin</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Dicetak</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Jenis Izin</b></font></td>
        </tr>
        <tr>
    <!---------------------------------------------------------------------------------------------------.-->
                   <?php
                        $i = NULL;
                        $query_data = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
                        A.a_izin, A.d_ambil_izin,
                        C.id idizin, C.n_perizinan, E.n_pemohon, E.a_pemohon,
                        I.c_pesan
                        FROM tmpermohonan as A
                        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                        INNER JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
                        INNER JOIN tmbap as I ON H.tmbap_id = I.id
                        WHERE I.c_penetapan = 1
                        AND I.status_bap = 1
                        AND A.c_izin_dicabut = 0
                        AND A.d_terima_berkas between '$tgla' and '$tglb'
                        order by A.id DESC";
                        $hasil_data = mysql_query($query_data);
                        while ($data = mysql_fetch_assoc(@$hasil_data)){
                            $i++;
                            $n_perusahaan = NULL;
                            $query_data2 = "SELECT a.n_perusahaan
                            FROM tmperusahaan a, tmpermohonan_tmperusahaan b
                            WHERE b.tmpermohonan_id = '".$data['id']."'
                            AND a.id = b.tmperusahaan_id";
                            $hasil_data2 = mysql_query($query_data2);
                            $jml_perusahaan = mysql_num_rows(@$hasil_data2);
                            $rows_data2 = mysql_fetch_object(@$hasil_data2);
                            if ($jml_perusahaan) $n_perusahaan = $rows_data2->n_perusahaan;
                            else $n_perusahaan = "-";
                            $tgl_surat = NULL;
                            $dicetak = NULL;
                            $query_data3 = "SELECT a.tgl_surat, a.c_cetak
                            FROM tmsk a, tmpermohonan_tmsk b
                            WHERE b.tmpermohonan_id = '".$data['id']."'
                            AND a.id = b.tmsk_id";
                            $hasil_data3 = mysql_query($query_data3);
                            $jml_surat = mysql_num_rows(@$hasil_data3);
                            $rows_data3 = mysql_fetch_object(@$hasil_data3);
                            if ($jml_surat){
                                $tgl_surat = $rows_data3->tgl_surat;
                                $dicetak = $rows_data3->c_cetak;
                            }
                            if($dicetak){
                    ?>
                        <tr>
                            <td align="center"><?php echo $i; ?></td>
                            <td align="center"><?php echo $data['pendaftaran_id']; ?></td>
                            <td align="center"><?php
                                $d_survey = $data['d_survey'];
                                if($d_survey){
                                    echo $this->lib_date->mysql_to_human($d_survey);
                                }
                                ?>
                            </td>
                            <td align="center"><?php
                                if($tgl_surat){
                                    echo $this->lib_date->mysql_to_human($tgl_surat);
                                }
                                ?>
                            </td>
                            <td><?php echo $data['n_pemohon']."<br>".$data['a_pemohon']; ?></td>
                            <td><?php echo $n_perusahaan."<br>".$data['a_izin']; ?></td>
                            <td align="center"><?php echo $dicetak." kali"; ?></td>
                            <td><?php echo $data['n_perizinan']; ?></td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
    <!---------------------------------------------------------------------------------------------------.-->
          </tr>
    </table>
        </fieldset>
    </div>
    <br style="clear: both;" />
</div>

<!--<div id="content">
    <div class="post">
        <div class="title">
           <h2><?php echo $page_name; ?>
                <br />
            <legend>Bidang Pendataan dan Penetapan Dinas Perizinan Kab. Bantul</legend></h2>
        </div>
         <h2 align="justify" style="border-left-color: #000">
          <fieldset>
            <legend style="color: #159729">
            <?php
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
            echo $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
            ?>

            <div id="statusRail">
              <div id="leftRail">
                <?php
                    $Back_data = array(
                        'name'    => 'button',
                        'value'   => 'Back',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('rekapitulasi/lap_izin/') . '\''
                    );
                    $cetak =  array(
                        'name' => 'submit',
                        'class'=>'button-wrc',
                        'content' => 'Cetak',
                        'type' => 'submit',
                        'onclick' => 'parent.location=\''. site_url('rekapitulasi/lap_izin/cetak') .'/'.$tgla.'/'.$tglb. '\''

                    );
                    echo form_submit($Back_data);
                    echo form_button($cetak);
                 ?>
              </div>
            </div>
          </legend>
        </fieldset>
      </h2>
            </div>
        <div class="entry">
           <table cellpadding="0" cellspacing="0" border="0" class="display" id="realisasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Tanggal Penetapan</th>
                        <th>Nama & Alamat Pemohon</th>
                        <th>Nama Perusahaan & Lokasi Izin</th>
                        <th>Tanggal Cetak</th>
                        <th>Jenis Izin</th>
                        <th>Keterangan</th>

                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = NULL;
                    foreach ($listpermohonan as $data){
                        $i++;
                        $data->tmpemohon->get();
                        $data->tmperusahaan->get();
                        $data->trperizinan->get();
                        $data->tmsk->get();
                        $data->trstspermohonan->get();
                        $data->tmpemohon->trkelurahan->get();
                        if($data->tmsk->id){

                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_survey) ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->tmsk->tgl_surat) ?></td>
                        <td><strong><?php echo $data->tmpemohon->n_pemohon; ?></strong>
                            <br/><small><?php echo $data->tmpemohon->a_pemohon; ?>, <?php echo $data->tmpemohon->trkelurahan->n_kelurahan; ?></small>
                        </td>
                        <td><strong><?php echo $data->tmperusahaan->n_perusahaan; ?></strong>
                            <br/><small><?php echo $data->a_izin ; ?></small></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->tmsk->tgl_surat) ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                    </tr>
                     <?php
//                        }  else {
                     ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php
                    }
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Tanggal Penetapan</th>
                        <th>Nama & Alamat Pemohon</th>
                        <th>Nama Perusahaan & Lokasi Izin</th>
                        <th>Tanggal Cetak</th>
                        <th>Jenis Izin</th>
                        <th>Keterangan</th>
                    </tr>
                </tfoot>
            </table>
  </div>
     <br style="clear: both;" />
</div>-->
