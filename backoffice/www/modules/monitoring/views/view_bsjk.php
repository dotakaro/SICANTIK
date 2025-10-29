<div id="content">
    <div class="post">
        <div class="title">
                  <h2><?php echo $page_name; ?></h2>
        </div>
    <div class="subnav">
            <div class="status">
                <h2 align="justify" style="border-left-color: #000; padding-left: 15px">
          <fieldset id="half">
            <legend style="color: #159729">
                 <?php
    	  	if($listpermohonan->c_izin_selesai ==="0") {
                    echo "<strong>Belum Jadi</strong> ";
                } elseif($listpermohonan->c_izin_selesai ==="1"){
                    echo "<strong>Sudah jadi</strong> ";
                } else {
                    echo "<strong>Kadaluarsa</strong> ";
                }
                 ?>
            </legend>
                
            <div id="leftMain">
                <div align="left" id="action">
                <?php
                    $Back_data = array(
                        'name'    => 'button',
                        'value'   => 'Back',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('monitoring/monitoringbsjk') . '\''

                    );
                    echo form_submit($Back_data);

                          $cetak = array(
                        'name'    => 'button',
                        'value'   => 'Cetak',
                        'class'   => 'button-wrc',
                        'content' => 'Cetak',
                        'onclick' => 'parent.location=\''. site_url('monitoring/monitoringbsjk/cetak_kadaluarsa') .'/'.$bsjk. '\''

                    );
                    echo form_submit($cetak);
                 ?>
              </div>
            </div>
              </fieldset>
                    </h2>
         </div>
        <div class="entry">
           <table cellpadding="0" cellspacing="0" border="0" class="display" id="monitoring">
                <thead>
                    <tr>
                       <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Status Permohonan</th>
                        <th>Alamat Pemohon</th>
                        <th>Kelurahan</th>

                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = NULL;
                    foreach ($listpermohonan as $data){
                       
                        $i++;
                        $data->tmpemohon->get();
                        $data->trperizinan->get();
                        $data->trstspermohonan->get();
                        $data->tmpemohon->trkelurahan->get();

                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry) ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->trstspermohonan->n_sts_permohonan; ?></td>
                        <td><?php echo $data->tmpemohon->a_pemohon; ?></td>
                        <td><?php echo $data->tmpemohon->trkelurahan->n_kelurahan; ?></td>


                      </tr>
                <?php
                    
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Status Permohonan</th>
                        <th>Alamat Pemohon</th>
                        <th>Kelurahan</th>
                    </tr>
                </tfoot>
            </table>        
        </div>
    </div
  </div>
     <br style="clear: both;" />
</div>
