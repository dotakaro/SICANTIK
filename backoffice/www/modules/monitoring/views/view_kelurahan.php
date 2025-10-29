<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
         <h2 align="justify" style="border-left-color: #000">
             <fieldset id="half">
            <legend style="color: #159729">Kelurahan 
            <?php
            echo  $list_kelurahan->n_kelurahan;
            
            ?>
               <div id="statusRail">
              <div id="leftRail">
                <?php
                    $Back_data = array(
                        'name'    => 'button',
                        'value'   => 'Back',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('monitoring/monitoringkecamatan') . '\''
                       );
                    echo form_submit($Back_data);

                       $cetak =  array(
                        'name' => 'submit',
                        'class'=>'button-wrc',
                        'content' => 'Cetak',
                        'type' => 'submit',
                        'onclick' => 'parent.location=\''. site_url('monitoring/monitoringkecamatan/cetak_kelurahan') .'/'.$hasil. '\''

                    );
                    echo form_button($cetak);
                 ?>
              </div>
            </div>
          </legend>
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
                      
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 1;
                    foreach ($listpermohonan as $data){
                        
                        $show = FALSE;
                        if($list_pemohon){
                            foreach($list_pemohon as $data_pemohon){
                                $data_relasi = new tmpemohon_tmpermohonan();
                                $data_relasi->where('tmpemohon_id', $data_pemohon->id)->where('tmpermohonan_id', $data->id)->get();
                                if($data_relasi->tmpemohon_id){
                                    $show = TRUE;
                                    break;
                                }
                            }
                        }else{
                            $show = FALSE;
                            break;
                        }
                        
                        $data->tmpemohon->get();
                        $data->trperizinan->get();
                        $data->trstspermohonan->get();
                        $data->tmpemohon->trkelurahan->get();

                        if($show){
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry) ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->trstspermohonan->n_sts_permohonan; ?></td>
                        <td><?php echo $data->tmpemohon->a_pemohon; ?></td>


                      </tr>
                <?php
                        $i++;
                        }
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
                    </tr>
                </tfoot>
            </table>
  </div>
     <br style="clear: both;" />
</div>
