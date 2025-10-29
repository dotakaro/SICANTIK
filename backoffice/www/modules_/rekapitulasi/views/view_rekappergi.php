<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            
      
       <br>
         <table cellpadding="0" cellspacing="0" border="1" align="center" width="900px" style="text-align: center" >
             <thead>

               <tr style="height: 35px;" class="title">
                   <th rowspan="2">No</th>
                   <th rowspan="2">jenis Izin</th>
                   <th rowspan="2">Izin Masuk</th>

                   <th colspan="3">Izin Terbit</th>
                   
                   <th colspan="3">Izin Ditolak</th>
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
                     $i=1;
                     foreach ($list as $data){
                     $data->trperizinan->get();
                 ?>
                 <tr>
                     <td><?php echo $i; ?></td>
                     <td align="left" height="19px"><?php echo $data->trperizinan->n_perizinan; ?></td>
                     <td><?php echo $data->d_tahun;?></td>
                     <td>&nbsp;</td>
                     <td><?php echo $jum2;?></td>
                     <td><?php echo $jum3;?></td>
                     
                     <td>&nbsp;</td>
                     <td><?php echo $jum2;?></td>
                     <td><?php echo $jum3;?></td>
                 </tr>
                <?php $i++;} ?>
              </tbody>
               
            </table>

             <br><br>
        <center>
            <?php
                     $view_data = array(
                    'src' => base_url().'assets/images/icon/navigation.png',
                    'alt' => 'Lihat di HTML to Openoffice',
                    'title' => 'back',
                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/retribusi'). '\''
                    );
                    echo img($view_data);
                    ?>
                    &nbsp; &nbsp;
                    <?php
                    $open_data = array(
                   'src' => base_url().'assets/images/icon/openoffice.png',
                    'alt' => 'Lihat di HTML to Openoffice',
                    'title' => 'cetak',
                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/retribusi/cetak'). '\''
                    );
                    echo img($open_data);
                ?>
           </center>
        </div>
    </div>
    <br style="clear: both;" />
</div>
