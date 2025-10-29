<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
                 <fieldset id="half">
                      <legend>Data Property</legend>
            <?php
            echo form_open('perizinan/koefisientarif/');
             ?>
            
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
                      <p style="text-align: right;">
                         <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/back_alt.png',
                                            'alt' => 'Back',
                                            'title' => 'Back',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif'); ?>">
                                            <?php echo img($img_edit); ?></a>
                      </p>
                 </fieldset>
<br>
 <table cellpadding="0" cellspacing="0" border="0" class="display" id="koefisientarif">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Property</th>
                        <th>Nama Grup</th>
                        <th>Kode Retribusi</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 1;
                    foreach ($list as $data) {
                        $prop = new trproperty();
                        $prop->where('id', $data->trproperty_id)->get();
                        if($data->c_parent !== $data->trproperty_id) {

                ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                    <?php
                                        echo $prop->n_property;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $prop->where('id', $data->c_parent)->get();
                                        echo $prop->n_property;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if ($data->c_retribusi_id === "1") {
                                            echo "Ada";
                                        } else if ($data->c_retribusi_id === "0" || $data->c_retribusi_id === NULL) {
                                            echo "Tidak Ada";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $prop->where('id', $data->trproperty_id)->get();
                                        if ($prop->c_type === "1") {
                                            echo "Bilangan";
                                        } else if ($prop->c_type === "0") {
                                            echo "Teks";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <center>
                                      
                                        <?php
                                        $img_edit = array(
                                            'src' => 'assets/images/icon/information.png',
                                            'alt' => 'Detail',
                                            'title' => 'Detail',
                                            'border' => '0',
                                        );
                                        ?>
                                        <a class="page-help" href="<?php echo site_url('perizinan/koefisientarif/detail'."/".$data->trperizinan_id .'/'. $data->trproperty_id) ?>">
                                            <?php echo img($img_edit); ?>
                                        </a>
                                    </center>
                                </td>
                            </tr>
                <?php
                    $i++;
                        }
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Property</th>
                        <th>Nama Grup</th>
                        <th>Kode Retribusi</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>



           

        </div>
    </div>
    <br style="clear: both;" />
</div>