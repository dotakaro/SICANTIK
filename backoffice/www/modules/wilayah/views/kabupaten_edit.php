<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('wilayah/kabupaten/' . $save_method, $attr);
        echo form_hidden('id', $id);
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Kabupaten</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <div class="contentForm">
                            <?php
                            if ($list_propinsi->id) {
                                    foreach ($list_propinsi as $row) {
                                        //$opsi_propinsi[0] = '--------Pilih salah satu-------';
                                        $opsi_propinsi[$row->id] = $row->n_propinsi;
                                    }
                                } else {
                                    $opsi_propinsi[0] = "";
                                }
                                 echo form_label('Propinsi');
                                 if ($save_method=='update')
                                 {
                                    echo form_dropdown('propinsi_pemohon', $opsi_propinsi,$propinsi, 'class = "input-select-wrc" id="propinsi_pemohon_id" ' );
                                    //echo form_hidden('propinsi_pemohon',$propinsi);

                                 }else {
                                echo form_dropdown('propinsi_pemohon', $opsi_propinsi,'0', 'class = "input-select-wrc" id="propinsi_pemohon_id" ' );
                                 }
                                ?>
                        </div>
                        <div class="contentForm">
                            <?php
                               $nama_input = array(
                                    'name' => 'nama_kabupaten',
                                    'value' => $nama,
                                    'class' => 'input-wrc required'
                                );
                                echo form_label('Nama Kabupaten');
                                echo form_input($nama_input);                                
                                ?>
                        </div>
                        <div class="contentForm">
                            <?php
                               $nama_input = array(
                                    'name' => 'nama_ibukota',
                                    'value' => $ibukota,
                                    'class' => 'input-wrc required'
                                );
                                echo form_label('Nama Ibukota');
                                echo form_input($nama_input);
                                ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $kode_daerah = array(
                                'name' => 'kode_daerah',
                                'value' => $kode_daerah,
                                'class' => 'input-wrc'
                            );
                            echo form_label('Kode Daerah');
                            echo form_input($kode_daerah);
                            echo br(4);
                            ?>
                        </div>
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
                'onclick' => 'parent.location=\''. site_url('wilayah/kabupaten') . '\''
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
