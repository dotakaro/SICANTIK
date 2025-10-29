<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>

     <fieldset>
         <legend style="color: #045000" align="bottom">
             <?php
            echo 'Rekapitulasi Tinjauan Lapangan Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
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
                            'onclick' => 'parent.location=\''. site_url('rekapitulasi/ceklap'). '\''
                            );
                            echo img($Back_data);
                    ?>
                     <?php
                  $img_cetak = array(
                                    'src' => base_url().'assets/images/icon/print.png',
                                    'alt' => 'Selesai',
                                    'title' => 'View Report with OpenOffice',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/ceklap/cetak').'/'.$tgla.'/'.$tglb. '\''
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
                <td align="center"><font  size="1" color="#1A1A1A"><b>Tanggal Daftar</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Tanggal Peninjauan</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Nama & Alamat Pemohon</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Jenis Izin</b></font></td>
                <td align="center"><font  size="1" color="#1A1A1A"><b>Nama Perusahaan & Lokasi Izin</b></font></td>
        </tr>
        <tr>
    <!---------------------------------------------------------------------------------------------------.-->
                   <?php
                        $i = NULL;
                        $query_data = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey, A.a_izin,
                        C.id idizin, C.n_perizinan, E.n_pemohon, E.a_pemohon
                        FROM tmpermohonan as A
                        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                        WHERE A.c_pendaftaran = 1
                        AND A.c_tinjauan = 1
                        AND A.c_izin_dicabut = 0
                        AND A.c_izin_selesai = 0
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
                        

                    ?>
                        <tr>
                            <td align="center"><?php echo $i; ?></td>
                            <td align="center"><?php echo $data['pendaftaran_id']; ?></td>
                            <td align="center"><?php
                                $tgl_permohonan = $data['d_terima_berkas'];
                                if($tgl_permohonan){
                                    if($tgl_permohonan != '0000-00-00') echo $this->lib_date->mysql_to_human($tgl_permohonan);
                                }
                                ?>
                            </td>
                            <td align="center"><?php
                                $d_survey = $data['d_survey'];
                                if($d_survey){
                                    echo $this->lib_date->mysql_to_human($d_survey);
                                }
                                ?>
                            </td>
                            <td><?php echo $data['n_pemohon']."<br>".$data['a_pemohon']; ?></td>
                            <td><?php echo $data['n_perizinan']; ?></td>
                            <td><?php echo $n_perusahaan."<br>".$data['a_izin']; ?></td>
                        </tr>
                    <?php
                        }
                    ?>
    <!---------------------------------------------------------------------------------------------------.-->
          </tr>
    </table>
        </fieldset>
    </div>
    <br style="clear: both;" />
</div>

<!--           <table cellpadding="0" cellspacing="0" border="0" class="display" id="realisasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Nama & Alamat</th>
                        <th>Jenis Izin</th>
                        <th>Nama & Alamat Perusahaan</th>
                        <th>Keterangan</th>

                    </tr>
                </thead>
                <tbody>-->
                <?php
//                    $i = NULL;
//                    foreach ($listpermohonan as $data){
//                        $data->tmpemohon->get();
//                        $data->tmperusahaan->get();
//                        $data->trperizinan->get();
//                        $data->trstspermohonan->get();
//                        $data->tmpemohon->trkelurahan->get();
//                        $data->trjenis_permohonan->get();
//                        $kelompok = $data->trperizinan->trkelompok_perizinan->get();
//                        if($kelompok->id == 2 || $kelompok->id == 4){
//                        $i++;
                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry) ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_survey) ?></td>
                        <td><strong><?php echo $data->tmpemohon->n_pemohon; ?></strong>
                            <br/><small><?php echo $data->tmpemohon->a_pemohon; ?>, <?php echo $data->tmpemohon->trkelurahan->n_kelurahan; ?></small>
                        </td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->tmperusahaan->n_perusahaan; ?>
                            <br/><small><?php echo $data->tmperusahaan->a_perusahaan; ?></small></td>
                        <td></td>


                      </tr>-->
                <?php
//                    }
//                    }
                ?>
<!--                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Nama & Alamat</th>
                        <th>Jenis Izin</th>
                        <th>Alamat Perusahaan</th>
                        <th>Keterangan</th>
                    </tr>
                </tfoot>
            </table>-->
<!--  </div>
     <br style="clear: both;" />
</div>-->
