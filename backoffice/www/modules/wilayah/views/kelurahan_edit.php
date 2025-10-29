<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('wilayah/kelurahan/' . $save_method, $attr);
        echo form_hidden('id', $id);
        $opsi_propinsi = array('0'=>'-------Pilih data-------');
        if ($list_propinsi->id) {
           foreach ($list_propinsi as $row) {
             $opsi_propinsi[$row->id] = $row->n_propinsi;
                   }
         }
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Kelurahan</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <div class="contentForm">
                                <?php
                                 echo form_label('Propinsi');
                                 if($propinsi==0)
                                 {
                                     echo form_dropdown('propinsi_pemohon', $opsi_propinsi,'0','class = "input-select-wrc" id="propinsi_pemohon_id"');
                                 }
                                 else
                                 {
                                     echo form_dropdown('propinsi_pemohon', $opsi_propinsi,$propinsi,'class = "input-select-wrc" id="propinsi_pemohon_id"');
                                    // echo form_hidden('propinsi_pemohon',$propinsi);
                                 }
                                ?>
                            </div>
                        <div style="clear: both" ></div>
                         <div class="contentForm">
                                <?php
                                echo form_label('Kabupaten');
                                if($kabupaten==0)
                                {
                                    echo "<div id='show_kabupaten_pemohon'>Data Tidak Tersedia</div>";
                                }
                                else
                                {
                                    $opsi_kabupaten= array('0'=>'-------Pilih data-------');                                
                                   foreach ($list_kabupaten as $row) 
                                  {
                                     $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                                  }                                 
                                    echo "<div id='show_kabupaten_pemohon'><input type='hidden' name='kabupaten_pemohon' value='".$kabupaten."' />".$opsi_kabupaten[$kabupaten]."</div>";
                                }                                
                                ?>
                            </div>
                        <div style="clear: both" ></div>
                            <div class="contentForm">
                                <?php
                                echo form_label('Kecamatan');
                                if($kecamatan==0)
                                {
                                    echo "<div id='show_kecamatan_pemohon'>Data Tidak Tersedia</div>";
                                }
                                else
                                {
                                    $opsi_kecamatan= array('0'=>'-------Pilih data-------');                                
                                   foreach ($list_kecamatan as $row) 
                                  {
                                     $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                  }
                                 
                                    echo "<div id='show_kecamatan_pemohon'><input type='hidden' name='kecamatan_pemohon' value='".$kecamatan."' />".$opsi_kecamatan[$kecamatan]."</div>";
                                }
                                
                                ?>
                            </div>
                        <div style="clear: both" ></div>
                        <div class="contentForm">
                            <?php
                                $nama_input = array(
                                    'name' => 'nama',
                                    'value' => $nama,
                                    'class' => 'input-wrc required'
                                );
                                echo form_label('Nama Kelurahan');
                                echo form_input($nama_input);
                                echo br(1);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $kode_daerah_input = array(
                                'name' => 'kode_daerah',
                                'value' => $kode_daerah,
                                'class' => 'input-wrc'
                            );
                            echo form_label('Kode Daerah');
                            echo form_input($kode_daerah_input);
                            echo br(4);
                            ?>
                        </div>
                           <div style="clear: both" ></div>
                        <div class="contentForm" style="padding-left: 145px">
                                       
                        </div>
                    </div>
                    <div id="contentright">
              
                    </div>
                    <br style="clear: both;" />
                </div>
            </div>
            <br>
            <?php
            $add_daftar = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_daftar);
            echo "<span></span>";
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('wilayah/kelurahan') . '\''
            );
            echo form_button($cancel_daftar);
            echo form_close();
            ?>
        </div>
        <div class="entry" style="text-align: center;">
           
        </div>
    </div>
    <br style="clear: both;" />
</div>
