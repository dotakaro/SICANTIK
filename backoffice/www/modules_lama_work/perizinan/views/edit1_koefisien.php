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
                        <?php
                           echo $dkoef->kategori;
                        ?>
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
                            echo form_label('Kategori','index_kategori');
                        ?>
                      </div>
               <div id="rightRail" class="bg-grid">
                          <b><?php
                           echo $koeflev1->kategori." (".$koeflev1->index_kategori.")";
                        ?></b>
                      </div>
          </div>
           <div id="statusRail">
               <div id="leftRail">
                        <?php
                            echo form_label('Harga','index_kategori');
                        ?>
                      </div>
               <div id="rightRail">
                          <?php
                           echo $koeflev1->v_index_kategori;
                        ?>
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
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/edit'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id); ?>">
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
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/createlev2'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id."/".$koeflev1->id); ?>">
                                            <?php echo img($img_edit); ?></a>
                           <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/green_edit.png',
                                            'alt' => 'Edit',
                                            'title' => 'Edit koefisien',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/editLev2'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id."/".$koeflev1->id); ?>">
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
                        <th>aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = NULL;
                    foreach ($list as $data){
                        $i++;

                        $row = new trkoefisienretribusilev2();
                        $row->where('id', $data->trkoefisienretribusilev2_id)->get();
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->kategori; ?></td>
                        <td><?php echo $row->index_kategori; ?></td>
                        <td>
                             <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/property.png',
                                            'alt' => 'Edit',
                                            'title' => 'Edit',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/editLev3'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id."/". $data->trkoefisienretribusilev1_id."/".$row->id) ?>">
                                            <?php echo img($img_edit); ?>
                                        </a>
                             <?php
                                        $confirm_text = 'Apakah Anda yakin akan menghapus '.$row->kategori.'?';
                                        $img_del  = array(
                                            'src' => 'assets/images/icon/cross.png',
                                            'alt' => 'Delete',
                                            'title' => 'Delete',
                                            'border' => '0',
                                            'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/delete2'."/".$jenis_izin->id."/".$id_property."/".$dkoef->id."/". $data->trkoefisienretribusilev1_id."/".$row->id) ?>">
                                            <?php echo img($img_del); ?>
                                        </a></td>
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
                        <th>aksi</th>
                    </tr>
                </tfoot>
            </table>
  </div>

        </div>
    </div>
    <br style="clear: both;" />
</div>