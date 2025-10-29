<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
         <h2 align="justify" style="border-left-color: #000">
          <fieldset>
            <legend style="color: #159729">
            <?php
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
            echo $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
            ?>
              
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    $Back_data = array(
                        'name'    => 'button',
                        'value'   => 'Back',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('monitoring/monitoringbulan') . '\''
                    );
                    $cetak =  array(
                        'name' => 'submit',
                        'class'=>'button-wrc',
                        'content' => 'Cetak',
                        'type' => 'submit',
                        'onclick' => 'parent.location=\''. site_url('monitoring/monitoringbulan/cetak_monitoring_bulan') .'/'.$tgla.'/'.$tglb. '\''

                    );
                    echo form_submit($Back_data);
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
     <br style="clear: both;" />
</div>
