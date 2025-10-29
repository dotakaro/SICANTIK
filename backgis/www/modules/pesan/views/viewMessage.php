<div id="content">
    <div class="post">
        <div class="title">
            <h1><?php echo $page_name ?></h1>
        </div>
          <div class="entry" >
              <div id="statusMain">
                  <fieldset style="background:whitesmoke">
        <table>
            <tr>
                <td colspan="3" >
                    <fieldset  id="half" style="background:#0054a5">
<!--                        <legend style="border-bottom-color: white" ><font color="Black"><strong>Data Pengirim</strong></font></legend>-->
            <?php
            echo form_open('pesan/pesanpengiriman/' . $save_method);
            echo form_hidden('id', $id);
            ?><br>

            <label class="label-wrc" style="color: white">Nama</label>
            <?php
            echo "<font color='white'>";
            echo $nama;
            echo form_hidden('nama', $nama);
            ?><p>

            <label class="label-wrc" style="color: white"">Alamat</label>
            <?php
             echo $alamat;
             echo form_hidden('alamat', $alamat);
             ?><p>

            <label class="label-wrc" style="color: white">Kelurahan</label>
            <?php
            echo $kelurahan;
            echo form_hidden('kelurahan', $kelurahan);
            ?><p>

             <label class="label-wrc" style="color: white">Kecamatan</label>
            <?php
            echo $kecamatan;
            echo form_hidden('kecamatan', $kecamatan);
            ?><p>

            <label class="label-wrc" style="color: white">No Telepon</label>
            <?php
            echo $telp;
            echo form_hidden('telp', $telp);
            ?><p>

           <label class="label-wrc" class="bg-grid" style="color: white">Tindak Lanjut </label>
            <?php
            echo $c_tindak_lanjut;
            echo form_hidden('c_tindak_lanjut', $c_tindak_lanjut);
            ?><p>
                
            <label class="label-wrc" style="color: white">Tanggal Penulisan Pesan </label>
            <?php
            echo $d_entry;
            echo form_hidden('d_entry', $d_entry);
            ?><p>

            <label>&nbsp;</label>
            </p>         
            </fieldset>
                </td>
                <td><strong></strong></td>
                <td>
                <fieldset id="half">
                    <legend title="readme please">Isi Pesan Pengaduan</legend>
                <div class="contentForm" align="center">
                    <?php
                    $e_pesan_input = array(
                        'name' => 'e_pesan',
                        'value' => $e_pesan,
                        'readonly' => '',
                        'class' => 'input-area-wrc',
                        'style' => 'width:98%'
                        );
                    echo form_textarea($e_pesan_input);
                    echo form_hidden('e_pesan', $e_pesan);
            ?>
            </div>
            </fieldset>
                <fieldset id="half">
                    <legend title="readme please">Isi Pesan Pengaduan</legend>
                <div class="contentForm" align="center">
                    <?php
                    $e_pesan_koreksi_input = array(
                        'name' => 'e_pesan_koreksi',
                        'value' => $e_pesan_koreksi,
                        'class' => 'input-area-wrc',
                        'style' => 'width:98%'
                        );
                    echo form_textarea($e_pesan_koreksi_input);
                    echo form_hidden('e_pesan_koreksi', $e_pesan_koreksi);
            ?>
            </div>
            </fieldset>
                </td>
            </tr>
        </table>
        </fieldset>   
<!--Pengisian data-->
<fieldset style="background: whitesmoke">
<table>
    <tr>
        <td><fieldset id="half" >
                <legend align="right">Detail Tindak Lanjut</legend>
            <label class="label-wrc">Dinas </label>
            <?php
            $c_skpd_tindaklanjut_input = array(
                'name' => 'c_skpd_tindaklanjut',
                'value' => $c_skpd_tindaklanjut,
                'class' => 'input-wrc'
            );
            echo form_input($c_skpd_tindaklanjut_input);
            ?><p>

             <label class="label-wrc">Tanggal </label>
            <?php
            $d_tindak_lanjut_input = array(
                'name' => 'd_tindak_lanjut',
                'value' => $d_tindak_lanjut,
                'class' => 'input-wrc',
                'class' => 'pesan'
            );
            echo form_input($d_tindak_lanjut_input);
            ?><p>

            <label class="label-wrc">Tanggal Tindak Lanjut selesai </label>
            <?php
            $d_tindaklanjut_selesai_input = array(
                'name' => 'd_tindaklanjut_selesai',
                'value' => $d_tindaklanjut_selesai,
                'class' => 'input-wrc',
                'class' => 'pesan'
            );
            echo form_input($d_tindaklanjut_selesai_input);
            ?><p>
            <label class="label-wrc">Nama Penangung Jawab</label>
            <?php
            $nama_penanggungjawab_input = array(
                'name' => 'nama_penanggungjawab',
                'value' => $nama_penanggungjawab,
                'class' => 'input-wrc',
                'id' => 'pesan'
            );
            echo form_input($nama_penanggungjawab_input);
            ?>
            </fieldset>
        </td>
         <td>
             <fieldset id="half">
            <legend>Isi Balasan Pengaduan</legend>
                <div class="contentForm" align="center">

            <?php
            $e_tindak_lanjut_input = array(
                'name' => 'e_tindak_lanjut',
                'value' => $e_tindak_lanjut,
                'class' => 'input-area-wrc',
                'style' => 'width:98%'
            );
            echo form_textarea($e_tindak_lanjut_input);
            ?>
                </div>
            </fieldset>
        </td>
    
    </tr> 
</table>
    </fieldset>
</div>
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
                'onclick' => 'parent.location=\''. site_url('pesan/pesanpengiriman') . '\''
            );
            echo form_button($cancel_message);
            echo form_close();
            ?>

        </div>                
    </div>

    <br style="clear: both;" />
</div>
