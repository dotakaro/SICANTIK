<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
                 <fieldset id="half">
                      <legend>Data Perizinan</legend>
            <?php
            echo form_open('perizinan/koefisientarif/' . $save_method);
             ?>
             <?php echo form_hidden('id',$id);?>
             <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nama Izin','nama_izin');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $jenis_izin->n_perizinan;
                        ?>
                        <?php echo form_hidden('izin_id',$jenis_izin->id);?>
                      </div>
                    </div>
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Nama Property','nama_property');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                            echo $property->n_property;
                        ?>
                        <?php echo form_hidden('property_id',$property->id);?>
                      </div>
                    </div>
          <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Kategori','kategori');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                          <b>  <?php
                           echo $dkoef->kategori;
                        ?></b>
                      </div>
          </div>

           <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Index Kategori','index_kategori');
                        ?>
                      </div>
                      <div id="rightRail">
                       <?php
                           echo $dkoef->index_kategori;
                        ?>
                      </div>
          </div>
          <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Satuan','satuan');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                          <b>  <?php
                           echo $dkoef->satuan;
                        ?></b>
                      </div>
          </div>

         <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Harga','harga');
                        ?>
                      </div>
                      <div id="rightRail">
                          <b>  <?php
                           echo $dkoef->harga;
                        ?></b>
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
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/detail'."/".$jenis_izin->id)."/".$id_property; ?>">
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
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/createlev1'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id); ?>">
                                            <?php echo img($img_edit); ?></a>
                                             <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/green_edit.png',
                                            'alt' => 'Edit',
                                            'title' => 'Edit koefisien',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/editLev1'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id); ?>">
                                            <?php echo img($img_edit); ?></a>
                      </p>
                      
                 </fieldset>
<br>
<!-- ............................ Grid ..............................-->
              <div class="entry">
           <table cellpadding="0" cellspacing="0" border="0" class="display" id="koefisientarif">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Index</th>
                        <th>Harga</th>
                        <th>i_entry</th>
                        <th>aksi</th>

                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = NULL;
                    foreach ($list as $data){
                        $i++;
                        $row = new trkoefisienretribusilev1();
                        $row->where('id', $data->trkoefisienretribusilev1_id)->get();

                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->kategori; ?></td>
                        <td><?php echo $row->index_kategori; ?></td>
                        <td><?php echo $row->v_index_kategori; ?></td>
                        <td><?php echo $row->i_entry; ?></td>
                        <td> <center>
                                         <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/property.png',
                                            'alt' => 'Edit',
                                            'title' => 'Edit',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/edit1'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id."/".$row->id) ?>">
                                            <?php echo img($img_edit); ?>
                                        </a>
                                       <?php
                                        $confirm_text = 'Apakah Anda yakin akan menghapus '.$row->kategori.'?';
                                        $img_del = array(
                                            'src' => 'assets/images/icon/cross.png',
                                            'alt' => 'Delete',
                                            'title' => 'Delete',
                                            'border' => '0',
                                            'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/delete1'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id."/".$row->id) ?>">
                                            <?php echo img($img_del); ?>
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
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Index</th>
                        <th>Harga</th>
                        <th>i_entry</th>
                        <th>aksi</th>
                    </tr>
                </tfoot>
            </table>
  </div>

        </div>
    </div>
    <br style="clear: both;" />
</div>