<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
           
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="koefisientarif">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Perizinan</th>
                        <th>Jumlah Property</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = null;
                    foreach ($list as $data){
                    $jumlah= 0;  $yy = 0;
                        $i++
                ?>
                   
                        <?php
                         $show = FALSE;
                         foreach($list_retribusi as $retribusi_data){
                                 $izinretribusi = new trperizinan_trretribusi();
                                 $izinretribusi->where('trperizinan_id',$data->id)
                                               ->where('trretribusi_id',$retribusi_data->id)->get();

                                 if($izinretribusi->trretribusi_id){
                                                  $show = TRUE;
                                                  break;
                                                  
                                                  }
                                                         
                                 }
                               
                         foreach($list_property as $property_data){
                              $izinprop = new trperizinan_trproperty();
                             $ss =  $izinprop->where('c_retribusi_id',1)
                                        ->where('trperizinan_id',$data->id)
                                        ->where('trproperty_id',$property_data->id)->get();

                                       if($ss->id) {
                                            
                                             $yy++;
                                             $jumlah =  "<center>" . $jumlah+$yy . "</center>";
                                             
                                             }
                                   

                         }
                       
                        ?>

                     <tr>
                        <td><?php echo $i; ?></td>
                        <td>
                         <?php
                                  echo $data->n_perizinan;?></td>
                        <td align="center">
                        <?php if(0<$jumlah){echo $jumlah;}else{echo "Belum ada property";}?>
                        </td>
                        <td width="50">
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/information.png',
                                    'alt' => 'Detail',
                                    'title' => 'Detail',
                                    'border' => '0',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/view'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
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
                        <th>Jenis Perizinan</th>
                        <th>Jumlah Property</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
