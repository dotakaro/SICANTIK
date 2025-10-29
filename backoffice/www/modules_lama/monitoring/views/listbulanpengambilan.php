<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset id="half">
            <legend>Filter Per Bulan Pengambilan Izin</legend>
                <?php
               
                echo form_open(site_url('monitoring/monitoringblnpengambilan/getPerbulan'));
               ?>
             
                <div id="statusRail">
                    <div id="leftRail">
                    <?php
                    echo form_label('Periode Awal','d_tahun');
                    ?>
                    </div>
              <div id="rightRail">
                <?php
                $periodeawal_input = array(
                'name'  => 'tgla',
                'value' => '',
                'class' => 'input-wrc',
                'class' => 'monbulan'
            );
            echo form_input($periodeawal_input);
            ?>
              </div>
                </div>

                <div id="statusRail">
                <div id="leftRail">
                <?php
                    echo form_label('Periode Akhir','d_tahun');
                ?>
                </div>
                <div id="rightRail">
                <?php
                $periodeakhir_input = array(
                'name'  => 'tglb',
                'value' => '',
                'class' => 'input-wrc',
                'class' => 'monbulan'
            );
            echo form_input($periodeakhir_input);
      
            ?>
              </div>
                </div>
            <div id="statusRail">
                <div id="rightRail">
                    <?php
                    $filter_data = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Filter',
                        'value' => 'Filter',
                        

                    );

                    $reset_data = array(
                                    'name' => 'button',
                                    'content' => 'Reset Filter',
                                    'value' => 'Reset Filter',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('monitoring/monitoringblnpengambilan') . '\''
                    );
                    ?>
                    <table border="0">
                        <tr>
                            <td><?php  echo form_submit($filter_data);?></td>
                            <td><?php echo form_button($reset_data); echo form_close();?></td>
                            <td>                 <?php

                    
                ?></td>
                        </tr>
                    </table>

                </div>
            </div>
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
