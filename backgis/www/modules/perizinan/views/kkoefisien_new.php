<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
                 <fieldset id="half">
                      <legend>Data Koefisien Tarif Retribusi</legend>
            <?php
            echo form_open('perizinan/koefisientarif/');
             ?>

             <div id="statusRail">
                      <div id="leftRail" >
                        <?php
                            echo form_label('Nama Izin','nama_izin');
                        ?>
                      </div>
                      <div id="rightRail" >
                        <?php
                            echo $jenis_izin->n_perizinan;
                        ?>
                        <?php echo form_hidden('izin_id',$jenis_izin->id);?>

                      </div>
                    </div>
                       <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nama Property','nama_property');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $nama_property;
                        ?>
                        <?php echo form_hidden('id_property',$id_property);?>

                      </div>
                    </div>
  <p style="text-align: right;">
                                   
                                         <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/back_alt.png',
                                            'alt' => 'Back',
                                            'title' => 'Back',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/view/'."/".$jenis_izin->id); ?>">
                                            <?php echo img($img_edit); ?></a>
                                       
                                            <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/home.png',
                                            'alt' => 'Front',
                                            'title' => 'Front',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif'); ?>">
                                            <?php echo img($img_edit); ?></a>
                                             <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/plus.png',
                                            'alt' => 'Tambah',
                                            'title' => 'Tambah koefisien',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/create'."/".$jenis_izin->id."/".$id_property); ?>">
                                            <?php echo img($img_edit); ?></a>
                      </p>          
                 </fieldset>
<br>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="koefisientarif">
                <thead>
                    <tr>
                        <th>ID</th>


                        <th>Kategori</th>
                        <th>Index Kategori</th>
                        <th>Mulai Efektif</th>
                        <th>Selesai</th>
                        <th>ID Entry</th>
                        <th>Tgl. Entry</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                 $i=null;
                    foreach ($list as $data){
                        $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->kategori; ?></td>
                        <td><?php echo $data->index_kategori; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_mulai_efektif); ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_selesai); ?></td>
                        <td><?php echo $data->i_entry; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_entry); ?></td>
                         <td><?php echo $data->harga; ?></td>
                        <td> <center>
                                         <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/property.png',
                                            'alt' => 'Edit',
                                            'title' => 'Edit',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/edit'."/".$jenis_izin->id .'/'. $id_property.'/'.$data->id) ?>">
                                            <?php echo img($img_edit); ?>
                                        </a>
                                        <?php
                                        $confirm_text = 'Apakah Anda yakin akan menghapus '.$data->kategori.'?';
                                        $img_cancel = array(
                                            'src' => 'assets/images/icon/cross.png',
                                            'alt' => 'Delete',
                                            'title' => 'Delete',
                                            'border' => '0',
                                            'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/delete'."/". $jenis_izin->id.'/'. $id_property.'/'.$data->id) ?>">
                                            <?php echo img($img_cancel); ?>

                                        </a>
                              
                                       
                                    </center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>

                        <th>Kategori</th>
                        <th>Index Kategori</th>
                        <th>Mulai Efektif</th>
                        <th>Selesai</th>
                        <th>ID Entry</th>
                        <th>Tgl. Entry</th>
                         <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>





        </div>
    </div>
    <br style="clear: both;" />
</div>