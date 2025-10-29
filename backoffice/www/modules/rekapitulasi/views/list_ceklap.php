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
            echo form_open(site_url('rekapitulasi/ceklap/filterData'));
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
                     'readOnly'=>TRUE,
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
                    echo form_label('Tgl Daftar Akhir','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                $periodeakhir_input = array(
                'name' => 'tglb',
                'value' => $this->lib_date->set_date(date('Y-m-d'), 0),
                     'readOnly'=>TRUE,
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
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/ceklap') . '\''
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
<!--        <div class="entry">
          <table cellpadding="0" cellspacing="0" border="0" class="display" id="realisasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Nama & Alamat</th>
                        <th>Jenis Izin</th>
                        <th>Nama & Alamat Perusahaan</th>
                        <th>Keterangan</th>

                    </tr>
                </thead>
                <tbody>-->
                <?php
//                    $i = 0;
//                    foreach ($listpermohonan as $data){
//                        $data->tmpemohon->get();
//                        $data->tmperusahaan->get();
//                        $data->trperizinan->get();
//                        $data->trstspermohonan->get();
//                        $data->tmpemohon->trkelurahan->get();
//                        $data->trjenis_permohonan->get();
//                        $kelompok = $data->trperizinan->trkelompok_perizinan->get();
//                        if($kelompok->id == 2 || $kelompok->id == 4){
//                        $i++;
                ?>
<!--                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry) ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_survey) ?></td>
                        <td><strong><?php echo $data->tmpemohon->n_pemohon; ?></strong>
                            <br/><small><?php echo $data->tmpemohon->a_pemohon; ?>, <?php echo $data->tmpemohon->trkelurahan->n_kelurahan; ?></small>
                        </td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->tmperusahaan->n_perusahaan; ?>
                            <br/><small><?php echo $data->tmperusahaan->a_perusahaan; ?></small></td>
                        <td></td>


                      </tr>-->
                <?php
//                    }
//                    }
                ?>
<!--                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Peninjauan</th>
                        <th>Nama & Alamat</th>
                        <th>Jenis Izin</th>
                        <th>Alamat Perusahaan</th>
                        <th>Keterangan</th>
                    </tr>
                </tfoot>
            </table>

-->        </div>
  </div>
     <br style="clear: both;" />
</div>
