<script>
    $.validator.addMethod('minStrict', function (value, el, param) {
    return value > param;
});
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('wilayah/kecamatan/' . $save_method, $attr);
        echo form_hidden('id', $id);
        if ($list_propinsi->id) {
			$opsi_propinsi[NULL] = '--------Pilih salah satu-------';
            foreach ($list_propinsi as $row) {    
                $opsi_propinsi[$row->id] = $row->n_propinsi;
            }
			 //$opsi_propinsi = array('0' => '--------Pilih salah satu-------') + $options;
        } else {
            $opsi_propinsi[] = "";
        }
        
        //edited by mucktar 12-04-2013
        if ($list_kabupaten->id) {
	 		$opsi_kabupaten[] = '--------Pilih salah satu-------';
            foreach ($list_kabupaten as $row) {
                $opsi_kabupaten[$row->id] = $row->n_kabupaten;
            }
        } else {
            $opsi_kabupaten[] = "";
        }
        ?>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Kecamatan</a></li>
                </ul>
                <div id="tabs-1">
                    <div id="contentleft">
                        <div class="contentForm">
    <?php
        echo form_label('Provinsi');
        if($save_method=='update')
        {
            echo form_dropdown('propinsi', $opsi_propinsi,$propinsi,
            'class = "input-select-wrc reguired " id="propinsi_pemohon_id" ');
            //echo form_hidden('propinsi',$propinsi);
        }
        else
        {
            echo form_dropdown('propinsi', $opsi_propinsi,NULL,'class = "input-select-wrc required min" id="propinsi_pemohon_id" ');
        }
    ?>
                        </div>
                        <div class="contentForm" id="show_kabupaten_pemohon">
    <?php
        //echo form_label('Kabupaten');
        /*if($save_method=='update')
        {
            echo form_dropdown('kabupaten', $opsi_kabupaten,$kabupaten,'class = "input-select-wrc required" id="kabupaten_pemohon_id" ');
            //echo form_hidden('kabupaten',$kabupaten);
        }
        else
        {
            echo form_dropdown('kabupaten', $opsi_kabupaten,'0','class = "input-select-wrc required" id="kabupaten_pemohon_id" ');
        }*/
        echo "<div id='show_kabupaten'>".form_label('Kabupaten');
        if(isset($kabupaten)&&$kabupaten!=''){
			echo form_dropdown('kabupaten', $opsi_kabupaten,$kabupaten,'class = "input-select-wrc required" id="kabupaten_pemohon_id" ');
		}else{
			echo "Data Tidak Tersedia";
		}
		echo "</div><br>";
        //end edit
    ?>

                        </div>

                        <div class="contentForm">
                            <?php
                                $nama_input = array(
                                    'name' => 'nama',
                                    'value' => $nama,
                                    'class' => 'input-wrc required'
                                );
                                echo form_label('Nama Kecamatan');
                                echo form_input($nama_input);
                                echo br(1);
                                ?>
                        </div>
                        <div class="contentForm">
                            <?php
                            $kode_daerah_input = array(
                                'name' => 'kode_daerah',
                                'value' => $kode_daerah,
                                'class' => 'input-wrc required'
                            );
                            echo form_label('Kode Daerah');
                            echo form_input($kode_daerah_input);
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
                'onclick' => 'parent.location=\''. site_url('wilayah/kecamatan') . '\''
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
