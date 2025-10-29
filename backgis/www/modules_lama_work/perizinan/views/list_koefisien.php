<script type="text/javascript">
    $(document).ready(function() {
        $('#selector').change(function(){
            var value = $('#selector').val();
            oTable = $('#property').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bJQueryUI"  : true,
                "bDestroy": true,
                "bFilter" : false,
                "sAjaxSource": "<?php echo site_url('perizinan/koefisientarif/selector') . "/" ?>" + value,
                "fnServerData": function ( sSource, aoData, fnCallback ) {
                        $.ajax( {
                                "dataType": 'json',
                                "type": "POST",
                                "url": sSource,
                                "data": aoData,
                                "success": fnCallback
                        } );
                }

            });
        });
    });
</script>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            

<?php echo form_open('perizinan/koefisientarif/create'); ?>
              
        <fieldset id="half">

            <legend>Per Jenis Perizinan</legend>
            <div id="statusRail">
              <div id="leftRail">
                    <?php  echo form_label('Jenis Izin');?>
                                
              </div>
              <div id="rightRail">
                    <div class="contentForm" id="show_izin_jenis" >
                <?php
                                foreach ($list_izin as $row){
                                    $opsi_izin[$row->id] = $row->n_perizinan;
                                
                                   
                                }


                                echo form_dropdown('jenis_izin', $opsi_izin, '',
                                     'class = "input-select-wrc" id="izin_jenis"');
                                
                            ?>
                        <?php echo form_hidden('c_retribusi',1);?>
                    </div>
               </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                    <?php  echo form_label('Pilih Property');?>
                                
              </div>
              <div id="rightRail">
                    <div class="contentForm"  id="show_property">
                <?php

                                foreach ($list_property as $row){
                                   
                                    $opsi_property[$row->id] = $row->n_property;
                                    

                                }


                                echo form_dropdown('jenis_property', $opsi_property, '',
                                     'class = "input-select-wrc" id="selector"');

                              
                                ?>
                            
                    </div>
               </div>
            </div>
           

              <div id="statusRail">
              <div id="leftRail">
                   
              </div>
              <div id="rightRail">
                 
           
          
                    
              </div>
            </div>
        
        </fieldset><br>
             <?php
                     $tambah = array(
                                    'name' => 'button',
                                    'content' => 'Tambah',
                                    'value' => 'Tambah koefisien',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('perizinan/koefisientarif/create') .'\''
                     );
                     echo form_submit($tambah);
                     echo form_close();
                     ?>
        </div>

             <div class="entry">
        <?php echo form_open('perizinan/koefisientarif/simulasi'); ?>
            

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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list as $data){

                    $data->trproperty->get();
                   
                ?>
                    <tr>
                        <td><?php echo $data->id; ?></td>

                        <td><?php echo $data->kategori; ?></td>
                        <td><?php echo $data->index_kategori; ?></td>
                        <td><?php echo $data->d_mulai_efektif; ?></td>
                        <td><?php echo $data->d_selesai; ?></td>
                        <td><?php echo $data->i_entry; ?></td>
                        <td><?php echo $data->d_entry; ?></td>
                        <td><center>
                                 <?php
                                $view_data = array(
                                'src' => base_url().'assets/images/icon/clipboard-doc.png',
                                'alt' => 'Lihat di HTML to Openoffice',
                                'title' => 'Edit',
                                'onclick' => 'parent.location=\''. site_url('perizinan/koefisientarif/edit').'/'.$izin.'/'.$data->trproperty->id.'/'.$data->id.'\''
                                );
                                echo img($view_data);
                                $view_data = array(
                                'src' => base_url().'assets/images/icon/cross.png',
                                'alt' => 'Lihat di HTML to Openoffice',
                                'title' => 'Delete',
                                'onclick' => 'parent.location=\''. site_url('perizinan/koefisientarif/delete').'/'.$data->id.'/'.$data->trproperty->id.'\''
                                );
                                echo img($view_data);

                     ?>
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
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>