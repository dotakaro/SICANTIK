

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
         <legend style="color: #045000" align="bottom">
             <?php
            echo 'Rekapitulasi Perizinan Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
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
                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/izin'). '\''
                    );
                    echo img($Back_data);
            ?>
             <?php
          $img_cetak = array(
                            'src' => base_url().'assets/images/icon/print.png',
                            'alt' => 'Selesai',
                            'title' => 'View Report with OpenOffice',
                            'onclick' => 'parent.location=\''. site_url('rekapitulasi/izin/cetak').'/'.$tgla.'/'.$tglb. '\''
                        );

                        echo img($img_cetak);
         //    echo anchor(site_url('rekapitulasi/realisasi/cetak_reporting') .'/'. $list_tahun->d_tahun, img($img_cetak))."&nbsp;";
         ?>
            </td>
        </tr>
    </table>
   <table align="center" width="800" border="1" class="display" cellpadding="1" cellspacing="0" id="rev">
        <tr class="title">
           <th rowspan="2"><font  size="1" color="#1A1A1A"><b>No</b></font></th>
           <th rowspan="2"><font  size="1" color="#1A1A1A"><b>Jenis Izin</b></font></th>
           <th rowspan="2"><font  size="1" color="#1A1A1A"><b>Izin Masuk</b></font></th>
           <th colspan="3"><font  size="1" color="#1A1A1A"><b>Izin Terbit</b></font></th>
           <th rowspan="2">&nbsp;</th>
           <th colspan="3"><font  size="1" color="#1A1A1A"><b>Izin Ditolak</b></font></th>
           <th rowspan="2"><font  size="1" color="#1A1A1A"><b>Izin Dalam Proses</b></font></th>
     </tr>
      <tr>
           <th><font  size="1" color="#1A1A1A"><b>Jumlah</b></font></th>
           <th><font  size="1" color="#1A1A1A"><b>Diambil</b></font></th>
           <th><font  size="1" color="#1A1A1A"><b>Belum Diambil</b></font></th>
           <th><font  size="1" color="#1A1A1A"><b>Jumlah</b></font></th>
           <th><font  size="1" color="#1A1A1A"><b>Diambil</b></font></th>
           <th><font  size="1" color="#1A1A1A"><b>Belum Diambil</b></font></th>
    </tr>
    <tr>
<!---------------------------------------------------------------------------------------------------.-->
               <?php
                    $i = NULL;
                    $query_data = "select id, n_perizinan, v_perizinan from trperizinan";
                    $results = mysql_query($query_data);
                    while ($data = mysql_fetch_assoc(@$results)){
                        $i++;
                        $jumlah_masuk = 0;
                        $jumlah_terbit = 0;
                        $terbit_ambil = 0;
                        $terbit_proses = 0;
                        $jumlah_tolak = 0;
                        $tolak_ambil = 0;
                        $tolak_proses = 0;

                        $jumlah_proses = 0;
						$izin = new trperizinan();
                        $izin->get_by_id($data['id']);
                        $permohonan = new tmpermohonan();
						$jumlah_masuk = $permohonan->where_related("trstspermohonan",'id <> 1')->where("d_terima_berkas between '$tgla' and '$tglb'")->where_related($izin)->count();
                        
                        $query = "select a.id jumlah from tmpermohonan a
								 inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
								 where b.trperizinan_id = '".$data['id']."'
								 and a.d_terima_berkas between '$tgla' and '$tglb'";
						$hasil_data = mysql_query($query);
						
						//$jumlah_masuk = mysql_num_rows(@$hasil_data);
						//$query2 = "select a.id, a.c_izin_selesai, a.c_penetapan, d.status_bap from tmpermohonan a
						//			inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
						//			inner join tmbap_tmpermohonan c on a.id = c.tmpermohonan_id
						//			inner join tmbap d on d.id = c.tmbap_id
						//			 where b.trperizinan_id = '".$data['id']."'
						//			 and a.d_terima_berkas between '$tgla' and '$tglb'";
						$query2 = "SELECT DISTINCT A.id, A.c_izin_selesai, A.c_penetapan, I.status_bap
        FROM tmpermohonan as A
        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
        LEFT JOIN (select id, tmpermohonan_id, MAX(tmbap_id) as tmbap_id from tmbap_tmpermohonan group by tmpermohonan_id) H ON A.id = H.tmpermohonan_id
        LEFT JOIN tmbap I ON H.tmbap_id = I.id
        LEFT JOIN tmpermohonan_tmsk J ON A.id = J.tmpermohonan_id
        LEFT JOIN tmsk K ON J.tmsk_id = K.id
        INNER JOIN trkelompok_perizinan_trperizinan L ON L.trperizinan_id = C.id
        /* INNER JOIN trperizinan_user AS M ON M.trperizinan_id = C.id */
        WHERE B.trperizinan_id = '".$data['id']."' and  A.d_terima_berkas between '$tgla' and '$tglb'";
						$hasil_data2 = mysql_query($query2);
                        while ($rows_data2 = mysql_fetch_assoc(@$hasil_data2)){
                            if($rows_data2['c_penetapan'] == "1"){
                                $jumlah_terbit++;
                                if($rows_data2['c_izin_selesai'] == "1") $terbit_ambil++;
                                else $terbit_proses++;
                            }else if($rows_data2['status_bap'] == "2"){
                                $jumlah_tolak++;
                                if($rows_data2['c_izin_selesai'] == "1") $tolak_ambil++;
                                else $tolak_proses++;
                            }
                        }
                        $jumlah_proses = $jumlah_masuk - ($terbit_ambil + $tolak_ambil);
                ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $data['n_perizinan']; ?></td>
                        <td align="center"><?php echo $jumlah_masuk; ?></td>
                        <td align="center"><?php echo $jumlah_terbit; ?></td>
                        <td align="center"><?php echo $terbit_ambil; ?></td>
                        <td align="center"><?php echo $terbit_proses; ?></td>
                        <td align="center"><?php  ?></td>
                        <td align="center"><?php echo $jumlah_tolak; ?></td>
                        <td align="center"><?php echo $tolak_ambil; ?></td>
                        <td align="center"><?php echo $tolak_proses; ?></td>
                        <td align="center"><?php echo $jumlah_proses; ?></td>
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
