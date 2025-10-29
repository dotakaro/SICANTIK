<script>
    function cekNilai()
    {
            var propinsi= document.getElementById("propinsi_pemohon_id");
            var kabupaten= document.getElementById("kabupaten_pemohon_id");
            var kecamatan= document.getElementById("kecamatan_pemohon_id");
            var kelurahan= document.getElementById("kelurahan_pemohon_id");
            if(propinsi.value=="0")
            {
                $("#errorpropinsi").html("<i style='color: #FF0000'>Pilih Propinsi</i>");
                return false;
            }
            else
            {
                    $("#errorpropinsi").html("");
                    if(kabupaten==null)
                    {
                            $("#show_kabupaten_pemohon").html("<i style='color: #FF0000'>Pilih Kabupaten</i>");
                            return false;
                    }
                    else
                    {
                                 if(kecamatan==null)
                                {
                                        $("#show_kecamatan_pemohon").html("<i style='color: #FF0000'>Pilih Kecamatan</i>");
                                        return false;
                                }
                                else
                                {
                                                 if(kelurahan==null)
                                                {
                                                        $("#show_kelurahan_pemohon").html("<i style='color: #FF0000'>Pilih Kelurahan</i>");
                                                        return false;
                                                }
                                                else
                                                {
                                                     if(kelurahan.value=="0")
                                                     {
                                                             alert('Pilih Kelurahan');
                                                             return false;
                                                     }
                                                     else
                                                     {
                                                             return true; 
                                                     }                                                         
                                                }     
                                }      
                    }                    
            }
    }
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <fieldset id="half">
            <legend>Manajemen Pesan</legend>
        <div class="entry">
            <?php
             $attr = array('id' => 'form','onSubmit'=>'return cekNilai();');
            echo form_open('pesan/' . $save_method, $attr);
            echo form_hidden('id', $id);
            ?>
            <div class="bg-grid">
            <label><b>Nama</b></label>
            <?php
            $pesan_nama = array(
                'name' => 'nama',
                 'style'=>'margin-left:-10px',
                'value' => $nama,
                'class' => 'input-wrc required'
            );
            echo form_input($pesan_nama);
            ?>
            </div>
            <label><b>Telp/HP</b></label>
            <?php
            $pesan_telp = array(
                'name' => 'telp',
                 'style'=>'margin-left:-10px',
                'value' => $telp,
                'class' => 'input-wrc digits'
            );
            echo form_input($pesan_telp);
            ?><br />
            <div class="bg-grid">
            <label><b>Alamat</b></label>
            <?php
            $pesan_alamat = array(
                'name' => 'alamat',
                 'style'=>'margin-left:-10px',
                'value' => $alamat,
                'class' => 'input-wrc required'
            );
            echo form_input($pesan_alamat);
            ?><br />
            </div>
           <!--..................................................................-->
 <div class="contentForm" id="show_propinsi_usaha">
                            <b><?php echo form_label('Propinsi'); ?> </b>
                            <?php
                                $opsi_propinsi = array('0'=>'-------Pilih data-------');
                                foreach ($list_propinsi as $row)
                               {
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                     
                                }
                                
                                if ($propinsi_usaha==" ")
                                {
                                echo form_dropdown('propinsi_usaha', $opsi_propinsi, '0', 'class = "input-select-wrc" id="propinsi_pemohon_id"');
                                }
                                else
                                {                                
                                      echo form_dropdown('propinsi_usaha', $opsi_propinsi, $propinsi_usaha,'class = "input-select-wrc" id="propinsi_pemohon_id"');
                                }
                            ?>
                        </div>
           <div style="clear: both" ></div>
                        <div class="contentForm" id="show_kabupaten_usaha">
                            <b><?php echo form_label('Kabupaten'); 
                                $opsi_kabupaten = array('0'=>'-------Pilih data-------');
                                foreach ($list_kabupaten as $row)
                                {
                                    $opsi_kabupaten[$row->id] = $row->n_kabupaten;                                    
                                }
                                if($kabupaten_usaha==NULL)
                                {
                                    echo "<div id='show_kabupaten_pemohon'>Data Tidak Tersedia</div>";
                                }
                                else
                                {
                                   echo "<div id='show_kabupaten_pemohon'><input type='hidden' value='".$kabupaten_usaha."' name='kabupaten_pemohon' id='kabupaten_pemohon_id' />".$opsi_kabupaten[$kabupaten_usaha] ."</div>";
                                }
                            ?>
                        </div>
           <div style="clear: both" ></div>
                        <div class="contentForm" id="show_kecamatan_usaha">
                             <b><?php echo form_label('Kecamatan'); 
                             $opsi_kecamatan = array('0'=>'-------Pilih data-------');
                                foreach ($list_kecamatan as $row)
                                {
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;                                   
                                }
                                if($kecamatan_usaha=="0")
                                {
                                   echo "<div id='show_kecamatan_pemohon'>Data Tidak Tersedia</div>";
                                }
                                else
                                {
                                    echo "<div id='show_kecamatan_pemohon'><input type='hidden' value='".$kecamatan_usaha."' name='kecamatan_pemohon' id='kecamatan_pemohon_id' />$opsi_kecamatan[$kecamatan_usaha]</div>";
                                }
                                 ?>
                        </div>
           <div style="clear: both" ></div>
                        <div class="contentForm" id="show_kelurahan_usaha">
                            <b><?php  echo form_label('Kelurahan'); 
                               $opsi_kelurahan = array('0'=>'-------Pilih data-------');
                               foreach ($list_kelurahan as $row)
                               {
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;                                    
                                }
                                if($kelurahan_usaha=="0")
                                {
                                    echo "<div id='show_kelurahan_pemohon'>Data Tidak Tersedia</div>";
                                }
                                else
                                {
                                   echo "<div id='show_kelurahan_pemohon'><input type='hidden' value='".$kelurahan_usaha."' name='kelurahan_pemohon' id='kelurahan_pemohon_id' />$opsi_kelurahan[$kelurahan_usaha]</div>";
                                }
                                 ?>
                        </div>
<!--.................................................................................................-->

            </div>
            </fieldset>
        <div class="entry">
           <div id="tabs">
               <ul>
                    <li><a href="#tabs-1" title="Untuk Memberikan Koreksi Terhadap Surat Pengaduan">Pesan Pengaduan</a></li>
                    <li><a href="#tabs-2" title="Untuk Memberikan Kategori dan Tindak Lanjut Terhadap Surat Pengaduan">Kategori dan Tindak Lanjut Pesan</a></li>
               </ul>
               <div id="tabs-1">
            <fieldset id="half">
                <legend>Isi Pesan</legend>
            <?php
            echo $e_pesan;
            ?><br />
            </fieldset><p>
            <div class="bg-grid">
            <label class="label-wrc">Tanggal Penulisan Pesan </label>
            <?php
            $d_entry_input = array(
                'name' => 'd_entry',
                'value' => $d_entry,
                'class' => 'input-wrc',
                  'readOnly'=>TRUE,
                'id' => 'pesan'
            );
            echo form_input($d_entry_input);
            ?><br /><p>
            </div>
            <div>
            <fieldset class="entry">
            <legend class="label-wrc" title="Edit Untuk Isi Surat Pengaduan">Koreksi Pesan</legend>
            <?php
            $e_pesan_koreksi_input = array(
                'name' => 'e_pesan_koreksi',
                'value' => $e_pesan_koreksi,
                'style' => 'width:98%',
                'class' => 'input-area-wrc required'
            );
            echo form_textarea($e_pesan_koreksi_input);
            ?><br />
            </fieldset>
            </div>
            </div>
               <div id="tabs-2">
            <?php
                    foreach ($list_sumber as $row){
                        $opsi_sumber[$row->id] = $row->name;
                    }

                    echo "<b>".form_label('Sumber')."</b>";
                    echo form_dropdown('sumber_pesan', $opsi_sumber, $sumber_pesan,'class = "input-select-wrc"');
                ?>
            <br style="clear: both" />
            <div class="bg-grid">
                <?php
                    foreach ($list_status as $row){
                        $opsi_status[$row->id] = $row->n_sts_pesan;
                    }

                    echo "<b>".form_label('Status Pesan')."</b>";
                    echo form_dropdown('status_pesan', $opsi_status, $status_pesan,'class = "input-select-wrc"');
                ?>
            <br style="clear: both" />
            </div>

            <label class="label-wrc">Tindak Lanjut</label>
            <?php 
                $option1="checked";
                $option2="";
                if($RbTindakLanjut==="Tidak")
                {
                    $option1="";
                    $option2="checked";
                }
            ?>
            <input type="radio" name="RbTindakLanjut" value="Ya"  <?php echo $option1; ?> />
	      Ya &nbsp; &nbsp; &nbsp;

	      <input type="radio" name="RbTindakLanjut" value="Tidak" <?php echo $option2; ?>  />
	      Tidak &nbsp; &nbsp; &nbsp;

              <input type="radio" name="RbTindakLanjut" value="Hapus" >
	      Hapus &nbsp; &nbsp; &nbsp;
            <br />
            <p>
            </div>
            </div>
            <br>
            <?php
            $add_pesan = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_pesan);
            echo "<span></span>";
            $cancel_message = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('pesan') . '\''
            );
            echo form_button($cancel_message);
            echo form_close();
            ?>

            </div>
            <label>&nbsp;</label>

            <div class="spacer" align="center"></div>
            
        </div>
            
    <br style="clear: both;" />
</div>
