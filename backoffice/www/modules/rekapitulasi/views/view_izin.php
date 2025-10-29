<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
     
          
          <fieldset>
         <legend style="color: #045000" align="bottom">
               <?php
            echo 'Rekapitulasi Perizinan "'.$this->lib_date->mysql_to_human($periodeawal).' - '.$this->lib_date->mysql_to_human($periodeakhir).' "';
           ?>
         </legend>
              <br><br>
            <table cellpadding="0" cellspacing="0" border="1" align="center" class="display" width="800px" style="text-align: center">
                <thead>

                    <tr style="height: 35px;" class="title">
                        <th rowspan="2" width="30px">No</th>
                        <th rowspan="2">jenis Izin</th>
                        <th rowspan="2">Izin Masuk</th>

                        <th colspan="3">Izin Terbit</th>
                      
                        <th colspan="3">Izin Ditolak</th>
                        <th rowspan="2">Izin Dalam proses</th>

                    </tr>
                    <tr style="height: 35px;" class="title">

                       <th>Jumlah</th>
                       <th>Diambil</th>
                       <th>Belum Diambil</th>
                       <th>Jumlah</th>
                       <th>Diambil</th>
                       <th>Belum Diambil</th>
                    </tr>
                </thead>
                 <tbody>
                <?php
                 $i = NULL;
                    foreach ($listlist as $data){
                        $i++;
                        $a = 0;$juma = 0;
                        $b = 0;$jumb = 0;
                        $c = 0;$jumc = 0;
                        $d = 0;$jumd = 0;
                        $relasi = new tmpermohonan_trperizinan();
                        $bap = new tmbap();
                        $izin = new trperizinan();
                        $permohonan = new tmpermohonan();
                        $mohonbap = new tmbap_tmpermohonan();
                        $permohonan->where("d_entry BETWEEN '$periodeawal' AND '$periodeakhir'")->get();
                        $jumlah = $relasi->where('trperizinan_id',$data->id)->count();
                        $list_jumlah = $relasi->where('trperizinan_id',$data->id)->get();

                        $tot = $mohonbap->where('tmpermohonan_id', $permohonan->id)->count();


                        $x = $relasi->where('tmpermohonan_id',$data->id)->get();
                        $izin->where('id',$x->trperizinan_id)->get();
                        $izin->distinct();
                        // izin terbit dan izin tolak
                        foreach ($list_jumlah as $data_relasi){

                            $permohonan->where('id',$data_relasi->tmpermohonan_id)
                                       ->where("d_entry BETWEEN '$periodeawal' AND '$periodeakhir'")->get();
                            $iddaftar = $permohonan->id;
                            $mohonbap->where('tmpermohonan_id',$iddaftar)->get();
                            $iddaftarbap = $mohonbap->tmbap_id;

                            $terbit = $bap->where('id',$iddaftarbap)->get();
                            //izin terbit
                            if($terbit->id){
                                if($terbit->status_bap === '1'){
                                        $a++;
                                        $juma = $juma + $a;
                                }
                                else if($terbit->status_bap === '0')
                                {
                                       $b++;
                                       $jumb = $jumb + $b;
                                }
                            }
                        }
                        //izin selesai dan izin belum
                            foreach ($list_jumlah as $data_relasi){
                            $daftar = new tmpermohonan();
                            $daftar->get_by_id($data_relasi->tmpermohonan_id);
                            if($daftar->c_izin_selesai === '1'){
                                $c++;
                                $jumc = $c;
                            }
                             if($daftar->c_izin_selesai === '0'){
                                    $d++;
                                    $jumd = $d;

                                }
                        }



                ?>
                    <tr>
                        <td><?php  echo $i; ?></td>
                        <td align="left"><?php echo  $izin->n_perizinan; ?></td>
                        <td align="right"><?php echo $jumlah;?></td>
                        <td align="right"><?php echo $juma;?></td>
                        <td align="right"><?php echo $jumc;?></td>
                        <td align="right"><?php echo $jumd;?></td>
                  
                        <td align="right"><?php echo $jumb;?></td>
                        <td align="right"><?php echo $jumc;?></td>
                        <td align="right"><?php echo $jumd;?></td>
                        <td align="right"><?php echo $jumlah-$juma-$jumb;?></td>
                    </tr>
                <?php
                 $i++;   }
                ?>
                </tbody>
               
            </table>
           <br><br>
           <center>
            <?php
                     $view_data = array(
                    'src' => base_url().'assets/images/icon/navigation.png',
                    'alt' => 'Lihat di HTML to Openoffice',
                    'title' => 'back',
                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/izin'). '\''
                    );
                    echo img($view_data);
                    ?>
                    &nbsp; &nbsp;
                   
           </center>
          </fieldset>

        </div>
    </div>
    <br style="clear: both;" />
</div>
