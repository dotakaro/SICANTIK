

<html>
<head>
<title>Realisasi Penerimaan</title>
</head>
<!--<body onLoad="window.print()">-->
<body>
    <div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
<form name="form1" method="post">
   
     <fieldset>
         <legend style="color: #045000; font-size: 15px;" align="bottom">
             <?php
            echo 'Realisasi Penerimaan Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
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
                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/realisasi'). '\''
                    );
                    echo img($Back_data);
          $img_cetak = array(
              
                            'src' => base_url().'assets/images/icon/print.png',
                            'alt' => 'Selesai',
                            'title' => 'View Report with OpenOffice',
                            'onclick' => 'parent.location=\''. site_url('rekapitulasi/realisasi/cetak_reporting').'/'.$tgla.'/'.$tglb. '\''
                        );

                        echo img($img_cetak);
         //    echo anchor(site_url('rekapitulasi/realisasi/cetak_reporting') .'/'. $list_tahun->d_tahun, img($img_cetak))."&nbsp;";
         ?>
            </td>
        </tr>
    </table>
   <table align="center" width="800" border="1" class="display" cellpadding="1" cellspacing="0" id="rev">
        <tr class="title">
            <td align="center"><font  size="1" color="#1A1A1A"><b>No</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Jenis Pelayanan Perizinan</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A" ><b>Target Anggaran</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Realisasi Pendapatan</b></font></td>
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
                        $jumlah = 0;
                        $query = "select a.id, d.nilai_bap_awal jumlah from tmpermohonan a
                                inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
                                inner join tmbap_tmpermohonan c on a.id = c.tmpermohonan_id
                                inner join tmbap d on d.id = c.tmbap_id
                                 where a.c_status_bayar = 1 and b.trperizinan_id = '".$data['id']."'
                                 and a.d_terima_berkas between '$tgla' and '$tglb'";
//                        $hasil_data2 = mysql_query($query);
//                        $rows_data2 = mysql_fetch_object(@$hasil_data2);
//                        $jumlah = $rows_data2->jumlah;
                        $results = mysql_query($query);
                        while ($rows = mysql_fetch_assoc(@$results)){
                            $nilai_ret = 0;
                            $query2 = "select a.v_prosentase_retribusi persen
                                     from tmkeringananretribusi a, tmkeringananretribusi_tmpermohonan b
                                     where b.tmpermohonan_id = '".$rows['id']."'
                                     and a.id = b.tmkeringananretribusi_id";
                            $hasil_data2 = mysql_query($query2);
                            $count_data = mysql_num_rows(@$hasil_data2);
                            $data2 = mysql_fetch_object(@$hasil_data2);
                            if ($count_data)
                            {
                                $nilai_ret = ($data2->persen * 0.01) * $rows['jumlah'];
                            }
                            else
                            {
                                $nilai_ret = $rows['jumlah'];
                            }
                            $jumlah = $jumlah + $nilai_ret;
                        }
                ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $data['n_perizinan']; ?></td>
                        <td align="right"><?php if ($data['v_perizinan']){
                        echo 'Rp. '.$this->terbilang->nominal($data['v_perizinan']).',00';
                        }else{
                            echo "Rp. 0,00";}?></td>
                        <td align="right"><?php
                        echo 'Rp. '. $this->terbilang->nominal($jumlah).',00';
                        ?> </td>
                    </tr>
                <?php
                    }
                ?>
<!---------------------------------------------------------------------------------------------------.-->
      </tr>
</table>
         </fieldset>
    
</form>
        </div>
        </div>
</body>
</html>
