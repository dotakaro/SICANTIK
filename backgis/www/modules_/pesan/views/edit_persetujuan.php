<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <div style="padding-left: 14px">
            <fieldset id="half">
                <legend>Data Pengirim</legend>
            <?php
            echo form_open('pesan/pesanpersetujuan/' . $save_method);
            echo form_hidden('id', $id);
            ?>
                 <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <label class="label-wrc">Nama</label>
                      </div>
                      <div id="rightRail" class="bg-grid">
                         <?php
            echo $nama;
            echo form_hidden('nama', $nama);
            ?>
                      </div>
                    </div>
   <br style="clear: both">
   
    <div id="statusRail">
                      <div id="leftRail">
                         <label class="label-wrc">Alamat</label>
                      </div>
                      <div id="rightRail">
                         <?php
             echo $alamat;
             echo form_hidden('alamat', $alamat);
             ?>
                      </div>
                    </div>
 <br style="clear: both">

 <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
                        <label class="label-wrc" >Kelurahan</label>
                      </div>
                        <div id="rightRail" class="bg-grid">
                         <?php
            $kel = new trkelurahan();
            $kel->where('id', $kelurahan)->get();
            echo $kel->n_kelurahan;
            echo form_hidden('kelurahan', $kelurahan);
            ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail">
                         <label class="label-wrc">Kecamatan</label>
                      </div>
                        <div id="rightRail">
                       <?php
            $kec = new trkecamatan();
            $kec->where('id', $kecamatan)->get();
            echo $kec->n_kecamatan;
            echo form_hidden('kecamatan', $kecamatan);
            ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail" class="bg-grid">
  <label class="label-wrc" title="isi pesan dan tanggal pembuatan pesan">Isi Pesan</label>

                      </div>
                        <div id="rightRail" class="bg-grid">
                        <?php
            echo $e_pesan ;
            echo " ";
            echo "(";
            echo $d_entry;
            echo ")";
            echo form_hidden('d_entry', $d_entry);
            ?>
                      </div>
                    </div>
                    <br style="clear: both">

                    <div id="statusRail">
                        <div id="leftRail">
                        <label class="label-wrc">Koreksi Pesan</label>
                      </div>
                        <div id="rightRail">
                       <?php
            echo $e_pesan_koreksi;
            echo form_hidden('e_pesan_koreksi', $e_pesan_koreksi);
            ?>
                      </div>
                    </div>
                    <br style="clear: both">
        <div style="float:left;" class="contentForm">
          
            <p>
                </div>
                </fieldset>
            </div>
        </div>
            <div class="entry">
            <div id="tabs">
                <ul>
            <li><a herf="#tabs-1">Respon Persetujuan Pengaduan</a></li>
                </ul>
                <div id="tabs-1">
<!--...................Respon Pengaduan............................-->
<br />
            <?php echo form_label('Ditunjukan untuk'); ?>
             
                <?php
                    
                    foreach ($list_unit as $row)
                    {
                         $opsi_unit[$row->n_unitkerja] = $row->n_unitkerja;                                    
                    }
                    echo form_dropdown('c_skpd_tindaklanjut', $opsi_unit,$c_skpd_tindaklanjut,'class = "input-select-wrc"');
                 ?>
            <p>
            
            <?php $cek = "checked"; ?>
            <?php echo form_label('Disetujui');
            
            if($sts_pengajuan=="Tidak")
            {
                $pilihan1="";
                $pilihan2="checked";
            }
            else
            {
                $pilihan1="checked";
                $pilihan2="";
            }
?>
            <input type="radio" name="RbPersetujuan" value="Ya" <?php echo $pilihan1; ?>  />
	      Ya &nbsp; &nbsp; &nbsp;


	      <input type="radio" name="RbPersetujuan" value="Tidak" <?php echo $pilihan2; ?>  />
	      Tidak &nbsp; &nbsp; &nbsp;
            <br />
            <p>
<!--..............................................................-->
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
                'onclick' => 'parent.location=\''. site_url('pesan/pesanpersetujuan') . '\''
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
