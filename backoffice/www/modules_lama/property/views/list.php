<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
          <div class="entry">
        <?php echo form_open('property/property/simulasi'); ?>
              <?php echo form_hidden('jenis_izin',$jenis_izin);?>
          
        <fieldset id="half">

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

              </div>
            </div>
                     <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Retribusi Terhutang','retribusi_terhitung');
                ?>
              </div>
              <div id="rightRail">

                <?php
                   foreach ($list_retribusi as $row){
                        
                        $opsi_retribusi[$row->v_retribusi] = $row->v_retribusi;
                   }

                    echo form_dropdown('retribusi_terhitung', $opsi_retribusi, '','class = "input-select-wrc"');
                ?>

              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
                <?php
                    $simulasi_data = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Simulasi',
                        'value' => 'simulasi'
                    );
                    echo form_submit($simulasi_data);
                ?>
              </div>
            </div>
        <? echo forn_close(); ?>
        </fieldset>
          </div>

        
              
        <?php echo form_label('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Harga Retribusi&nbsp;&nbsp;&nbsp;&nbsp;=','harga_retribusi');?>
             <?php echo $retribusi;?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <br><br>
        <?php echo form_label('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Retribusi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=','harga_retribusi');?>
             Rp.<?php echo $tot;?>,00


        <div class="entry">
             <br><br>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="property">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data Teknis</th>
                     
                         <th>Kategori</th>
                         <th>Index</th>
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
                     
                        <td><?php 
                              
                                if($data->id == $id){echo $kategori;
                                echo form_hidden('kategori'.$data->id, $kategori);
                                }
                                else{ echo form_hidden('kategori'.$data->id, $kategori);
                               echo $temporer;

                                }
                             ?>
                    

                   
                        </td>
                        <td>
                           <?php if($data->id == $id){echo $index_k;
                           echo form_hidden('index'.$data->id, $index_k);
                           }
                           else{echo $temporer;}
                                   ?>
                         
                     
                        
                        </td>
              

               
                        <td>
                            
                            <center>
                        

                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('property/property/panel1'."/".$data->id."/".$jenis_izin)."/" ?>"
                                ><?php echo img($img_edit); ?></a>
                            </center>


                           
                        </td>
                                  <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Data Teknis</th>
               
                         <th>Kategori</th>
                         <th>Index</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    



    </div>
    <br style="clear: both;" />
</div>