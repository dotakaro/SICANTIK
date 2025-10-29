<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
             <?php
            echo form_open(site_url('rekapitulasi/pergi/view'));
             ?>
                    <fieldset id="half">
                <legend>Daftar Per Tahun</legend>
             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Berdasarkan Tahun','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    foreach ($list_tahun as $row){
                        $opsi_tahun[$row->d_tahun] = $row->d_tahun;
                    }

                    echo form_dropdown('d_tahun', $opsi_tahun, '','class = "input-select-wrc"');
                ?>
              </div>
            </div>
             <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
              <?php
            $add_pesan = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Tampilkan'
            );
            echo form_submit($add_pesan);
                echo form_close();
                ?>
              </div>
            </div>
        </fieldset>
            <?php
            echo form_close();
        ?>
       <br>
         <table cellpadding="0" cellspacing="0" border="0" class="display" id="pergi">
             <thead>

               <tr>
                   <th rowspan="2">No</th>
                   <th rowspan="2">jenis Izin</th>
                   <th rowspan="2">Izin Masuk</th>

                   <th colspan="3">Izin Terbit</th>
                   <th rowspan="2">&nbsp;</th>
                   <th colspan="3">Izin Ditolak</th>
               </tr>
               <tr>
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
                     <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                     <td><?php echo $data->d_tahun;?></td>
                     <td>&nbsp;</td>
                     <td><?php echo $jum2;?></td>
                     <td><?php echo $jum3;?></td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td><?php echo $jum2;?></td>
                     <td><?php echo $jum3;?></td>                      
                 </tr>
                <?php $i++;} ?>
              </tbody>
                <tfoot>
                  <tr>
                     <th>No</th>
                     <th>Jenis Izin</th>
                     <th>Izin Masuk</th>
                     <th colspan="3">Izin Terbit</th>
                     <th>&nbsp;</th>
                     <th colspan="3">Izin Ditolak</th>
                  </tr>
                </tfoot>
            </table>
       
        </div>
    </div>
    <br style="clear: both;" />
</div>
