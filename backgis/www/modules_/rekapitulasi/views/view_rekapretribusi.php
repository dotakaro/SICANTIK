<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
              <fieldset>
         <legend style="color: #045000" align="bottom">
               <?php
            echo 'Retribusi Penerimaan Tahun '.$list_tahun->d_tahun;
           ?>
         </legend>
       <br>
       <table cellpadding="0" cellspacing="0" border="1" align="center"  width="800px" class="display" style="text-align: center">
                <thead>
                    <tr style="height: 35px;" class="title">
                        <th>No</th>
                        <th>Jenis Izin</th>
                        <th>Izin Jadi</th>
                        <th>Retribusi Izin</th>
                        <th>Terbayar</th>
                        <th>Terhutang</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i=1;

                    foreach ($list as $data){
                        $data->trperizinan->get();

                ?>
                    <tr>
                        <td><?php  echo $i; ?></td>
                        <td align="left" height="19px"><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>

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
              </fieldset>
        </div>
    </div>
    <br style="clear: both;" />
</div>
