<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset>
        <?php echo form_open('perijinan/datateknis/filterdata'); ?>
            <legend>Filter Per Jenis Perijinan</legend>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Jenis Izin','name_jenis_izin');
                ?>
              </div>
              <div id="rightRail">

                <?php
                   foreach ($list_ijin as $row){
                        $opsi_izin[$row->id] = $row->n_perijinan;
                   }
                    echo form_dropdown('jenis_izin', $opsi_izin, '','class = "input-select-wrc"');
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
                                    'onclick' => 'parent.location=\''. site_url('perijinan/datateknis') . '\''
                    );
                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                ?>
              </div>
            </div>
        <? echo forn_close(); ?>
        </fieldset>

        <div class="entry">
            <?php
                $add_datateknis = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Data Teknis',
                    'onclick' => 'parent.location=\''. site_url('perijinan/datateknis/create') . '\''
                );
                echo form_button($add_datateknis);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="datateknis">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Property</th>
                        <th>ID Perizinan</th>
                        <th>Kode Retribusi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){
                ?>
                    <tr>
                        <td><?php echo $data->id; ?></td>
                        <td><?php echo $data->n_property; ?></td>
                        <td><?php echo $data->perijinan_id; ?></td>
                        <td><?php echo $data->c_retribusi; ?></td>
                        <td><center>
                            <?php
                                $detail = array(
                                    'name' => 'button',
                                    'content' => 'Edit',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('perijinan/datateknis/edit') .'/'. $data->id . '\''
                                );
                                $delete = array(
                                    'name' => 'button',
                                    'class' => 'button-wrc',
                                    'content' => 'Hapus',
                                    'onclick' => 'parent.location=\''. site_url('perijinan/datateknis/delete') .'/'. $data->id . '\''
                                );
                                echo form_button($detail);
                                echo form_button($delete);
                            ?></center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nama Property</th>
                        <th>ID Perizinan</th>
                        <th>Kode Retribusi</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>