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
            <h2 ><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'form','onSubmit'=>'return cekNilai();');
            echo form_open('pesan/' . $save_method, $attr);
            ?>
            <div style="padding-left: 14px">
            <fieldset id="half">
                <legend>Isi Biodata</legend>
                <div class="bg-grid">
            <label>Nama</label>
            <?php
            $pesan_nama = array(
                'name' => 'nama',
                'style'=>'margin-left:-10px',
                'value' => $nama,
                'class' => 'input-wrc required'
            );
            echo form_input($pesan_nama);
            ?><br />
            </div>
                
                    <label>No Telp</label>
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
            <label>Alamat</label>
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
<div class="contentForm"  id="show_propinsi_usaha">
                        <?php  echo form_label('Propinsi'); 
                                 $opsi_propinsi = array('0'=>'-------Pilih data-------');
                                foreach ($list_propinsi as $row)
                               {
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                    
                                }

                                echo form_dropdown('propinsi_usaha', $opsi_propinsi,'0 ','class = "input-select-wrc" id="propinsi_pemohon_id"');
                                echo "<span id='errorpropinsi'></span>";
                            ?>
                        </div>
<div style="clear: both" ></div>
                        <div class="contentForm" id="show_kabupaten_usaha">
                             <?php 
                             echo form_label('Kabupaten'); 
                                echo "<div id='show_kabupaten_pemohon'>Data Tidak Tersedia</div>";
                            ?>
                        </div>
<div style="clear: both" ></div>
                        <div class="contentForm" id="show_kecamatan_usaha">
                             <?php echo form_label('Kecamatan'); 
                                echo "<div id='show_kecamatan_pemohon'>Data Tidak Tersedia</div>";
                            ?>
                        </div>
<div style="clear: both" ></div>
    <div class="contentForm" id="show_kelurahan_usaha">
                            <?php  echo form_label('Kelurahan'); 
                            echo "<div id='show_kelurahan_pemohon'>Data Tidak Tersedia</div>";
                            ?>
                        </div>
<div style="clear: both" ></div>
<!--.................................................................................................-->

            </fieldset>
                </div>
                <div class="entry">
                  <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Pesan Pengaduan</a></li>
                        <li><a href="#tabs-2" title="isi sumber pesan, kategori pesan dan Tanggal Pembuatan Pesan">Info. Pengaduan</a></li>
                    </ul>
         <div id="tabs-1">
            <?php echo form_label('Pesan Pengaduan'); ?>
            <?php
            $e_pesan_input = array(
                'name' => 'e_pesan',
                'value' => $e_pesan,
                'style' => 'width:55%',
                'class' => 'input-area-wrc required'
            );
            echo form_textarea($e_pesan_input);
            ?><br />
         </div>
         <div id="tabs-2">
                <?php  echo form_label('Sumber Pengaduan'); ?>
                 <select class="input-select-wrc" name="sumber_pesan">
                <?php
                    foreach ($list_sumber as $row){
                        $text = null;
                        if($row->sop === "1")
                        { 
                                echo "<option value=".$row->id.">".$row->name.' (Tertulis)'."</option>";
                        }
                        else
                       {
                                echo "<option value=".$row->id.">".$row->name.' (Lisan)'."</option>";
                        }
                }
                 ?>
                  </select>

            <br style="clear: both" />
            <div class="bg-grid">
                <?php  echo form_label('Status Pengaduan'); ?>
                 <select class="input-select-wrc" name="status_pesan">
                <?php
                    foreach ($list_status as $row)
                    {
                        echo "<option value=".$row->id.">".$row->n_sts_pesan."</option>";
                    }
                 ?>
                  </select>

            <br style="clear: both" />
            </div>
            <?php  echo form_label('Tanggal Penulisan'); ?>
            <?php
            $d_entry_input = array(
                'name' => 'd_entry',
                'value' => $this->lib_date->get_date_now(),
                'class' => 'input-wrc',
                  'readOnly'=>TRUE,
                'id' => 'pesan'
            );
            echo form_input($d_entry_input);

            ?><br />
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
            <div class="spacer"></div>
            
            
            
        </div>
    </div>

    <br style="clear: both;" />
</div>
