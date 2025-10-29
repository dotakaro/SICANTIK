

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
            echo 'Rekap Pendaftaran Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
//            echo 'Realisasi Penerimaan Tahun '.$list_tahun->d_tahun;
           ?>
          </legend>
         <table align="left">
        <tr>
            <td align="center">
           <?php
                    $Back_data = array(
                   'src' => base_url().'assets/images/icon/back_alt.png',
                    'alt' => 'Lihat di HTML to Openoffice',
                    'title' => 'Kembali',
                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/rekapitulasi'). '\''
                    );
                    echo img($Back_data);
            ?>
             <?php
          $img_cetak = array(
                            'src' => base_url().'assets/images/icon/print.png',
                            'alt' => 'Selesai',
                            'title' => 'View Report with OpenOffice',
                            'onclick' => 'parent.location=\''.site_url('rekapitulasi/realisasi/cetak_report').'/'.$tgla.'/'.$tglb.'\''
                        );

                        echo img($img_cetak);
           //  echo anchor(site_url('rekapitulasi/realisasi/cetak_report') .'/'. $list_tahun->d_tahun, img($img_cetak))."&nbsp;";
         ?>
            </td>
        </tr>
    </table>
   <table align="center" width="90%" border="1" class="display" cellpadding="1" cellspacing="0" id="rev">
        <tr class="title">
            <td align="center" width="5%"><font  size="1" color="#1A1A1A"><b>No</b></font></td>
            <td align="center" width="80%"><font  size="1" color="#1A1A1A"><b>Jenis Izin</b></font></td>
            <td align="center" width="15%"><font  size="1" color="#1A1A1A"><b>Jumlah Permohonan</b></font></td>
            
    </tr>
    <tr>
<!---------------------------------------------------------------------------------------------------.-->
               <?php
                    $i = NULL;
                    $query_data = "select id, n_perizinan, v_perizinan from trperizinan";
                    $hasil_data = mysql_query($query_data);
                    while ($data = mysql_fetch_assoc(@$hasil_data)){
                        $i++;
                        $jumlah = 0;
                        $izin = new trperizinan();
                        $izin->get_by_id($data['id']);
                        $permohonan = new tmpermohonan();
                        $jumlah = $permohonan->where_related("trstspermohonan",'id <> 1')->where("d_terima_berkas between '$tgla' and '$tglb'")->where_related($izin)->count();
                ?>
                    <tr>
                        <td align="center"><?php  echo $i; ?></td>
                        <td><?php echo $data['n_perizinan']; ?></td>
                        <td align="center"><?php echo anchor(site_url('rekapitulasi/DetailTahun').'/'. $data['id'] .'/'. $tgla.'/'. $tglb , $jumlah, 'class="link2-wrc" rel="rekapitulasi_box"'); ?></td>
                        
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
