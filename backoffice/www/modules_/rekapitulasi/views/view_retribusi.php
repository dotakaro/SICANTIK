<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>

     <fieldset>
         <legend style="color: #045000" align="bottom">
             <?php
            echo 'Rekapitulasi Retribusi Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
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
                            'onclick' => 'parent.location=\''. site_url('rekapitulasi/retribusi'). '\''
                            );
                            echo img($Back_data);
                    ?>
                     <?php
                  $img_cetak = array(
                                    'src' => base_url().'assets/images/icon/print.png',
                                    'alt' => 'Selesai',
                                    'title' => 'View Report with OpenOffice',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/retribusi/cetak').'/'.$tgla.'/'.$tglb. '\''
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
            <td align="center"><font  size="1" color="#1A1A1A"><b>Jenis Izin</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Izin Jadi</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Retribusi Izin</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Terbayar</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Terhutang</b></font></td>
    </tr>
    <tr>
<!---------------------------------------------------------------------------------------------------.-->
               <?php
                    $i = NULL;
                    $query_data = "select a.id, a.n_perizinan, a.v_perizinan
                                from trperizinan a, trkelompok_perizinan_trperizinan b
                                where b.trkelompok_perizinan_id = 4 /*Izin Bertarif*/
                                and a.id = b.trperizinan_id";
                    $hasil_data = mysql_query($query_data);
                    while ($data = mysql_fetch_assoc(@$hasil_data)){
                        $i++;
                        $izin_jadi = 0;
                        $retribusi = 0;
                        $terbayar = 0;
                        $terhutang = 0;
                        $query = "select a.id, a.c_status_bayar bayar, d.nilai_retribusi retribusi from tmpermohonan a
                                inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
                                inner join tmbap_tmpermohonan c on a.id = c.tmpermohonan_id
                                inner join tmbap d on d.id = c.tmbap_id
                                 where b.trperizinan_id = '".$data['id']."' and d.c_penetapan = 1 and d.status_bap = 1
                                 and a.d_terima_berkas between '$tgla' and '$tglb'";
                        $results = mysql_query($query);
                        while ($rows = mysql_fetch_assoc(@$results)){
                            $izin_jadi++;
                            $nilai_ret = 0;
                            $query2 = "select a.v_prosentase_retribusi persen
                                     from tmkeringananretribusi a, tmkeringananretribusi_tmpermohonan b
                                     where b.tmpermohonan_id = '".$rows['id']."'
                                     and a.id = b.tmkeringananretribusi_id";
                            $hasil_data2 = mysql_query($query2);
                            $count_data = mysql_num_rows(@$hasil_data2);
                            $data2 = mysql_fetch_object(@$hasil_data2);
                            //if ($count_data)
//                                $nilai_ret = ($data2->persen * 0.01) * $rows['retribusi'];
//                            else
                                $nilai_ret = $rows['retribusi'];
                            $retribusi = $retribusi + $nilai_ret;
                            if($rows['bayar'] == "1") $terbayar = $terbayar + $nilai_ret;
                            else $terhutang = $terhutang + $nilai_ret;
                        }
                ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $data['n_perizinan']; ?></td>
                        <td align="center"><?php echo $izin_jadi; ?></td>
                        <td align="right"><?php
                        echo 'Rp. '. $this->terbilang->nominal($retribusi).',00';
                        ?> </td>
                        <td align="right"><?php
                        echo 'Rp. '. $this->terbilang->nominal($terbayar).',00';
                        ?> </td>
                        <td align="right"><?php
                        echo 'Rp. '. $this->terbilang->nominal($terhutang).',00';
                        ?> </td>
                    </tr>
                <?php
                    }
                ?>
<!---------------------------------------------------------------------------------------------------.-->
      </tr>
</table>
<!--            <table cellpadding="0" cellspacing="0" border="0" class="display" id="retribusi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Izin</th>
                        <th>Izin Jadi</th>
                        <th>Retribusi Izin</th>
                        <th>Terbayar</th>
                        <th>Terhutang</th>
                    </tr>
                </thead>
                <tbody>-->
                <?php
               $i = 0;
//                   foreach ($list as $data){
//
//                        $a = 0;$juma = 0;
//                        $b = 0;$jumlah = 0;
//
//                        $mohonstatus = new tmpermohonan_trstspermohonan();
//                        $permohonan  = new tmpermohonan();
//                        $mohonizin   = new tmpermohonan_trperizinan();
//                        $retribusi   = new trretribusi();
//                        $retizin     = new trperizinan_trretribusi();
//
//
//                        //$jumlah = $mohonizin->where('trperizinan_id',$data->id)->count();
//                        $pharga = $retizin->where('trperizinan_id',$data->id)->get();
//                        $harga  = $retribusi->where('id',$pharga->trretribusi_id)->get();
//
//                        $list_jumlah = $mohonizin->where('trperizinan_id',$data->id)->get();
//
//                        foreach ($list_jumlah as $data_relasi){  //8.11.19.20.24
//                            $permohonan = new tmpermohonan();
//                            $permohonan->where("d_entry BETWEEN '$tgla' AND '$tglb'")
//                                       ->get_by_id($data_relasi->tmpermohonan_id);
//                            $iddaftar = $permohonan->id; //8.11.19.20.24
//                            $status = $mohonstatus->where('tmpermohonan_id',$iddaftar)->get();
//
//                               if($permohonan->d_tahun){
//                                $b++;
//                                $jumlah = $b;
//                                }
//
//                            if($status->id)
//                            {
//                                 if($status->trstspermohonan_id === '14'){
//
//                                        $juma = $juma;
//                                        $juma++;
//
//
//                                }
//                            }
//
//                         }
//                            $totalanggaran = $harga->v_retribusi * $jumlah;
//                            $terbayar      = $juma * $harga->v_retribusi;
//                            $terhutang     = $totalanggaran - $terbayar;
//
//
//
//         //jumlah
//         if ($harga->id) {
//            if ($harga->id){
//                $jumlahharga   = "Rp".number_format($totalanggaran,2,',','.');
//                $terbayar      = "Rp".number_format($terbayar,2,',','.');
//                $terhutang     = "Rp".number_format($terhutang,2,',','.');
//
//            }else
//                {
//                    $jumlahharga = "Rp".number_format(0,2,',','.');
//                    $terbayar    = "Rp".number_format(0,2,',','.');
//                    $terhutang   = "Rp".number_format(0,2,',','.');
//                }
//                }else
//                    {
//                     $jumlahharga = "Rp".number_format(0,2,',','.');
//                     $terbayar    = "Rp".number_format(0,2,',','.');
//                     $terhutang   = "Rp".number_format(0,2,',','.');
//                    }

                        ?>
<!--                    <tr>
                        <td><?php  echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td align="right"> <?php echo anchor(site_url('rekapitulasi/retribusi/pick_retribusi_list'.'/'.$data->id), $jumlah, 'class="link2-wrc" rel="pendaftar_box"');?></td>
                        <td align="right"><?php
                        if($harga->id){
                        echo "Rp".number_format($totalanggaran,2,',','.');
                        }else
                        { echo "Rp".number_format(0,2,',','.');}?></td>
                        <td align="right"><?php
                        if($harga->id){
                        echo $terbayar;
                        }else
                        { echo "Rp".number_format(0,2,',','.');}

                        ?></td>
                        <td align="right">
                            <?php
                        if($harga->id){
                        echo $terhutang;
                        }else
                        { echo "Rp".number_format(0,2,',','.');}

                        ?>
                        </td>

                    </tr>-->
                <?php
//                }
                ?>
<!--                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Jumlah</th>
                        <th>Retribusi izin</th>
                        <th>Terbayar</th>
                        <th>Terhutang</th>
                    </tr>
                </tfoot>-->
<!--            </table>-->
        </fieldset>
    </div>
    <br style="clear: both;" />
</div>
