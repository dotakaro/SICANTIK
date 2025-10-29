

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
            echo 'Realisasi Penerimaan Tahun '.$list_tahun->d_tahun;
           ?>
          </legend>

         <br><br>

         <table align="center" width="800" border="1" class="display" cellpadding="1" cellspacing="0" bgcolor="#00000" id="rev">
        <tr class="title">
            <td align="center"><font  size="1" face="book"><b>No</b></font></td>
            <td align="center"><font  size="1" face="book"><b>Jenis Izin</b></font></td>
            <td align="center"><font  size="1" face="book"><b>Jumlah Permohonan Izin</b></font></td>
            
    </tr>
    <tr>
<!---------------------------------------------------------------------------------------------------.-->
               <?php
                    $i = NULL;
                    foreach ($list as $data){
                        $i++;
                        $g = 0;
                        $y = 0;
                        $o = 0;
                        $jumlah = 0;
                        $jum1 = 0;
                        $z = 0;
                        $relasi = new tmpermohonan_trperizinan();
                        $list_relasi = $relasi->where('trperizinan_id',$data->id)->get();

                        foreach ($list_relasi as $data_relasi){
                            $daftar = new tmpermohonan();
                            $daftar->get_by_id($data_relasi->tmpermohonan_id);
                            if($daftar->d_tahun === $data_tahun){
                                $z++;
                                $jumlah = $z;
                                }
                        }
                ?>
                    <tr>
                        <td><?php  echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td align="right"><?php echo anchor(site_url('rekapitulasi/DetailTahun').'/'. $data->id .'/'. $list_tahun->d_tahun , $jumlah, 'class="link2-wrc" rel="rekapitulasi_box"'); ?></td>
                        
                    </tr>
                <?php
                    }
                ?>
<!---------------------------------------------------------------------------------------------------.-->
      </tr>
</table>
         </fieldset>
    <table align="center" width="800">
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
                            'src' => base_url().'assets/images/icon/clipboard.png',
                            'alt' => 'Selesai',
                            'title' => 'View Report with OpenOffice',
                            'onclick' => 'parent.location=\''.site_url('rekapitulasi/realisasi/cetak_report').'/'.$list_tahun->d_tahun.'\''
                        );

                        echo img($img_cetak);
           //  echo anchor(site_url('rekapitulasi/realisasi/cetak_report') .'/'. $list_tahun->d_tahun, img($img_cetak))."&nbsp;";
         ?>
            </td>
        </tr>
    </table>
</form>
        </div>
        </div>
        </body>
</html>
