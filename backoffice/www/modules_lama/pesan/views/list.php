<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>

         <fieldset id="half">
         <legend>Filter Per Status Pengaduan</legend>
        <?php echo form_open('pesan/filterdata'); ?>
         
         <div id="statusRail" align="left" >
                <div id="leftRail">
                    <label>Status Pengaduan</label>
                </div>
                  
                <div id="rightRail">
                  <select class="input-select-wrc" name="sts_pesan">
                <?php
                    echo "<option>-----Pilih Status Pengaduan-----</option>";
                    foreach ($liststspesan as $row){
                        echo "<option value=".$row->id.">".$row->n_sts_pesan."</option>";
                    }
                 ?>
                  </select>
                
                    <br>
             <?php
                $filter_data = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Filter',
                        'value' => 'Filter'

                    );
                    echo '&nbsp;&nbsp;'.form_submit($filter_data);

                    $add_pesan = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Tambah Pengaduan',
                        'onclick' => 'parent.location=\''. site_url('pesan/create') . '\''
                    );
                    echo form_button($add_pesan);
                ?>
                </div>
            </div>
          
        <? echo form_close(); ?>
         </fieldset>

        <div class="entry" id="centre">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pesan">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Isi Pengaduan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pengaduan</th>
                        <th>Tindak Lanjut</th>
                        <th>Tanggal Pengiriman Pengaduan</th>
                        <th>Sumber Pengaduan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = null;
                    foreach ($list as $data){
                $i++;
                    $kelurahan = new trkelurahan();
                    $kecamatan = new trkecamatan();
                    $kecamatan->where('id', $data->kecamatan)->get();
                    $kelurahan->where('id', $data->kelurahan)->get();
                    $data->trstspesan->get();
                    $data->trsumber_pesan->get();
                    

                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->e_pesan; ?></td>
                        <td><?php echo $data->nama; ?></td>
                        <td><?php echo $data->alamat; ?></td>
                        <td><?php echo $kelurahan->n_kelurahan; ?></td>
                        <td><?php echo $kecamatan->n_kecamatan; ?></td>
                        <td><?php echo $data->trstspesan->n_sts_pesan; ?></td>
                        <td><?php echo $data->c_tindak_lanjut; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry); ?></td>
                        <td><?php echo $data->trsumber_pesan->name; ?></td>
                        <td><center>
                            <?php

                                $img_edit = array(
                                    'src' => base_url().'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                echo anchor(site_url('pesan/edit') .'/'. $data->id,img($img_edit))."&nbsp;";

                            ?></center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>NO</th>
                        <th>Isi Pengaduan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Status Pengaduan</th>
                        <th>Tindak Lanjut</th>
                        <th>Tanggal Pengiriman Pengaduan</th>
                        <th>Sumber Pengaduan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
