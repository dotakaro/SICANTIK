<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset >
            <legend>Pencarian</legend>
            <div id="contentright">
                <?php
                echo form_open(site_url('monitoring/monitoringkecamatan/getKecamatan'));
                ?>
                        <div class="contentForm">
                            <?php
                                foreach ($list_propinsi as $row){
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                }

                                echo form_label('Propinsi');
                                echo form_dropdown('monitoring_propinsi', $opsi_propinsi, '',
                                     'class = "input-select-wrc" id="propinsi_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kabupaten">
                            <?php
                                foreach ($list_kabupaten as $row){
                                    $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                                }

                                echo form_label('Kabupaten');
                                echo form_dropdown('monitoring_kabupaten', $opsi_kabupaten, '',
                                     'class = "input-select-wrc" id="kabupaten_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kecamatan_pemohon">
                            <?php
                                foreach ($list_kecamatan as $row){
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }

                                echo form_label('Kecamatan');
                                echo form_dropdown('monitoring_kecamatan', $opsi_kecamatan, '',
                                     'class = "input-select-wrc" id="kecamatan_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kelurahan_pemohon">
                            <?php
                                foreach ($list_kelurahan as $row){
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }

                                echo form_label('Kelurahan');
                                echo form_dropdown('mon_kelurahan', $opsi_kelurahan, '','class = "input-select-wrc"');
                            ?>
                        </div>
                <p style="text-align: center">
                  <?php
                    $filter_data = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Filter',
                        'value' => 'Filter'

                    );

                    $reset_data = array(
                                    'name' => 'button',
                                    'content' => 'Reset Filter',
                                    'value' => 'Reset Filter',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('monitoring/monitoringkecamatan') . '\''
                    );
                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                ?></p>
                    </div>


       </fieldset>

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

    </div
  </div>
     <br style="clear: both;" />
</div>
