<style>
#eror
{
    text-align: center;
    color: red;
    font-weight: bold;
   
}

</style>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
             <?php
            echo ("<div id='eror'>".$error."</div>");
            echo form_open(site_url('rekapitulasi/back_lap/filterData'));
             ?>
                    <fieldset id="half">
                <legend>Daftar Per Periode</legend>
             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Tgl Daftar Awal','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                $periodeawal_input = array(
                'name' => 'tgla',
                'value' => $this->lib_date->set_date(date('Y-m-d'), -2),
                'class' => 'input-wrc',
                     'readOnly'=>TRUE,
                'class'=>'tarif'
            );
            echo form_input($periodeawal_input);
            ?>
              </div>
            </div>

             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Tgl Daftar Akhir','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                $periodeakhir_input = array(
                'name' => 'tglb',
                'value' => $this->lib_date->set_date(date('Y-m-d'), 0),
                'class' => 'input-wrc',
                     'readOnly'=>TRUE,
                'class'=>'tarif'
            );
            echo form_input($periodeakhir_input);
            ?>
              </div>
              </div>
             <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
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
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/back_lap') . '\''
                    );
                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                ?>
              </div>
            </div>
        </fieldset>
            <?php
            echo form_close();
        ?>
       <br>

<!--        Untuk Waktu       -->
                <?php
//                echo form_open(site_url('rekapitulasi/back_lap/filterData'));
                ?>
<!--         <fieldset id="half">
                <legend>Berdasarkan Range Waktu</legend>
             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Dari Tanggal','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                $periodeawal_input = array(
                'name' => 'tgla',
                'value' => '',
                'class' => 'input-wrc',
                'class'=>'tarif'
            );
            echo form_input($periodeawal_input);
            ?>
              </div>
            </div>

             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Sampai Tanggal','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                $periodeakhir_input = array(
                'name' => 'tglb',
                'value' => '',
                'class' => 'input-wrc',
                'class'=>'tarif'
            );
            echo form_input($periodeakhir_input);
            ?>
              </div>
            </div>
             <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
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
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/back_lap') . '\''
                    );
                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                ?>
              </div>
            </div>
        </fieldset>-->


<!--        <div class="entry">
          <table cellpadding="0" cellspacing="0" border="0" class="display" id="realisasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Nama & Alamat Pemohon</th>
                        <th>Nama Perusahaan &<br />Lokasi Izin</th>
                        <th>Alasan Kembali</th>
                        <th>Tgl Pengembalian</th>
                        <th>Jenis Izin</th>

                    </tr>
                </thead>
                <tbody>-->
                <?php
//                    $i = NULL;
//                    foreach ($listpermohonan as $data){
//                        $i++;
//                        $data->tmpemohon->get();
//                        $data->tmperusahaan->get();
//                        $data->tmbap->get();
//                        $data->trperizinan->get();
//                        $data->trstspermohonan->get();
//                        $data->tmpemohon->trkelurahan->get();


                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_survey) ?></td>
                        <td><strong><?php echo $data->tmpemohon->n_pemohon; ?></strong>
                            <br/><small><?php echo $data->tmpemohon->a_pemohon; ?>, <?php echo $data->tmpemohon->trkelurahan->n_kelurahan; ?></small>
                        </td>
                       <td><strong><?php echo $data->tmperusahaan->n_perusahaan; ?></strong>
                            <br/><small><?php echo $data->a_izin ; ?></small></td>
                        <td><?php echo $data->tmbap->c_pesan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_ambil_izin) ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>


                      </tr>-->
                <?php
//                    }
                ?>
<!--                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Nama & Alamat Pemohon</th>
                        <th>Nama Perusahaan &<br />Lokasi Izin</th>
                        <th>Alasan Kembali</th>
                        <th>Tgl Pengembalian</th>
                        <th>Jenis Izin</th>
                    </tr>
                </tfoot>
            </table>-->

        </div>
  </div>
     <br style="clear: both;" />
</div>
