
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>

            <div class="entry">
        <?php echo form_open('property/pendataan/filter'); ?>

        <fieldset>

            <legend>Per Jenis Perizinan</legend>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Jenis Izin','name_jenis_izin');
                ?>
              </div>
              <div id="rightRail">

                <?php
                   foreach ($list_izin as $row){

                        $opsi_izin[$row->id] = $row->n_perizinan;
                   }

                    echo form_dropdown('jenis_izin', $opsi_izin, '','class = "input-select-wrc"');
                ?>
                <?php
                    $simulasi_data = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Filter',
                        'value' => 'Filter'
                    );
                    echo form_submit($simulasi_data);
                ?>
              </div>
            </div>
        
        <? echo forn_close(); ?>
        </fieldset>
          </div>

        <div class="entry">
            <?php
                $add_role = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Peran',
                    'onclick' => 'parent.location=\''. site_url('pendataan/create') . '\''
                );
                echo form_button($add_role);
            ?>
            <br><br>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendataan">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Jenis Perizinan</th>
                        <th>Kelompok Perizinan</th>
                        <th>Lama Proses(Hari)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i=1;
                    foreach ($list as $data){
                        $data->trperizinan->get();
                        $data->trjenis_permohonan->get();
                    
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->trjenis_permohonan->n_permohonan; ?></td>
                        <td>&nbsp;</td>
                        <td><center>
                            <?php
                                $detail = array(
                                    'name' => 'button',
                                    'content' => 'Detail',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('pendataan/detail') .'/'. $data->id . '\''
                                );
                               
                                echo form_button($detail);
                   
                            ?></center>
                        </td>
                    </tr>
                <?php
                   $i++; }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>NO</th>
                        <th>Jenis Perizinan</th>
                        <th>Kelompok Perizinan</th>
                        <th>Lama Proses(Hari)</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
