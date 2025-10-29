<style>
    .input-upload-wrc{
        margin-right: -5px;
    }
    a{
        text-decoration: none;
    }
</style>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Setting Profile</b></a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $flashmessage = $this->session->flashdata('pesan');
                    echo!empty($flashmessage) ? '<font color="red" id="pesan"> Pesan : ' . $flashmessage . '</font><hr/><br/>' : '';
                    ?>
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open_multipart('header_instansi/' . $save_method, $attr);

                    echo form_hidden('id', $id);
                    ?>
                    <label>Nama Instansi</label>
                    <?php
                    $data_input = array(
                        'name' => 'nama_badan',
                        'value' => $nama_badan->value,
                        'class' => 'input-area-wrc required'
                    );
                    echo form_textarea($data_input);
                    ?><br/>
                    <label>Nama Wilayah</label>
                    <?php
                    $opsi_kabupaten[0] = '--------Pilih salah satu-------';
                    foreach ($list_kabupaten as $row) 
                   {
                             $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                   }                              
                     echo form_dropdown('kabupaten_pemohon', $opsi_kabupaten,$kabupaten_i->value,'class = "input-select-wrc" id="kabupaten_pemohon_id" ' );
                    ?><br/>

                    <label>Telepon / Fax</label>
                    <?php
                    $data_input2 = array(
                        'name' => 'tlp',
                        'value' => $tlp->value,
                        'class' => 'input-wrc required digits'
                    );
                    echo form_input($data_input2);
                    ?> / <?php
                        $data_input4 = array(
                            'name' => 'fax',
                            'value' => $fax->value,
                            'class' => 'input-wrc required digits'
                        );
                        echo form_input($data_input4);
                    ?><br/>

                    <label>Logo</label>
                    <?php
                    $logo = array(
                        'name' => 'logo',
                        'value' => ''
                    );
                    echo form_upload($logo) . ' &nbsp; format ( JPG/JPEG/GIF/PNG ), size kurang dari 1 Mb, ukuran maksimal 150 x 120';
                     ?>

                    <br />
                    <LABEL>Alamat</label>
                    <?php
                    $data_input = array(
                        'name' => 'alamat',
                        'value' => $alamat->value,
                        'class' => 'input-area-wrc required'
                    );
                    echo form_textarea($data_input);
                    ?>    


                    <br style="clear: both" />
                </div>

            </div>
            <br>        
            <?php
            $add_ijin = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_ijin);
            echo "<span></span>";
            

            $cancel_ijin = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . base_url() . '\''
            );
            echo form_button($cancel_ijin);
            echo form_close();
            ?>

        </div>
    </div>
    <br style="clear: both;" />
</div>