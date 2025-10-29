<div id="content">
    <div class="post">
        <div class="title">
                  <h2><?php echo $page_name; ?></h2>
        </div>
    <div class="subnav">
            <div class="status">
                <fieldset id="half">
            <legend>Filter Perizinan Sudah/Belum jadi dan Kadaluarsa</legend>
                      <?php
                echo form_open(site_url('monitoring/monitoringbsjk/filterdata'));
                ?>
        <select name="subeka" class="input-select-wrc" >
        <option value="1" id="selector">Sudah Jadi</option>
        <option value="0" id="selector">Belum Jadi</option>
        <option value="2" id="selector">Kadaluarsa</option>
        </select>
                
                        <?php   $filter_data = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Filter',
                        'value' => 'Filter'

                    );
                  echo form_submit($filter_data);
                     $reset_data = array(
                                    'name' => 'button',
                                    'content' => 'Reset Filter',
                                    'value' => 'Reset Filter',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('monitoring/monitoringbsjk') . '\''
                    );

                    echo form_button($reset_data);
      
                 ?>
                   <br />
           
               
                </fieldset>
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
                        $data->tmpemohon->trkelurahan->get();
                        $data->trperizinan->get();
                        $data->trstspermohonan->get();



                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_terima_berkas) ?></td>
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
