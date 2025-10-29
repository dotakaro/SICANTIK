<?php //echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('rekomendasi/save', $attr);
        ?>
        <div class="entry">
            <?php
            echo '<label class="label-wrc">No Pendaftaran</label>';
            echo $trtanggal_survey->tmpermohonan_pendaftaran_id;
            echo "<br style='clear:both' />";

            echo '<label class="label-wrc">Nama Izin</label>';
            echo $trtanggal_survey->tmpermohonan_trperizinan_n_perizinan;
            echo "<br style='clear:both' />";

            echo '<label class="label-wrc">Nama Pemohon</label>';
            echo $trtanggal_survey->tmpermohonan_tmpemohon_n_pemohon;
            echo "<br style='clear:both' />";

            echo '<label class="label-wrc">Instansi Teknis</label>';
            echo $instansi_teknis;
            echo "<br style='clear:both' />";


            $id_tim_teknis = array(
                'name' => 'id',
                'id'=>'id',
                'type'=>'hidden',
                'value'=>$id
            );
            echo form_input($id_tim_teknis);

            $rekomendasi_input_1 = array(
                'name' => 'rekomendasi',
                'id' => 'rekomendasi_1',
                'class' => 'required',
                'value'=>'Direkomendasikan',
            );
            $rekomendasi_input_2 = array(
                'name' => 'rekomendasi',
                'id' => 'rekomendasi_2',
                'class' => 'required',
                'value'=>'Tidak Direkomendasikan'
            );
            $rekomendasi_input_3 = array(
                'name' => 'rekomendasi',
                'id' => 'rekomendasi_3',
                'class' => 'required',
                'value'=>'Direkomendasikan dengan Catatan'
            );
            $rekomendasi_input_4 = array(
                'name' => 'rekomendasi',
                'id' => 'rekomendasi_4',
                'class' => 'required',
                'value'=>'Perlu Pembahasan Lebih Lanjut'
            );

            $checked_rekomendasi_1 = '';
            $checked_rekomendasi_2 = '';
            $checked_rekomendasi_3 = '';
            $checked_rekomendasi_4 = '';
            switch($rekomendasi){
                case 'Direkomendasikan':
                    $checked_rekomendasi_1 = 'checked="checked"';
                    break;
                case 'Tidak Direkomendasikan':
                    $checked_rekomendasi_2 = 'checked="checked"';
                    break;
                case 'Direkomendasikan dengan Catatan':
                    $checked_rekomendasi_3 = 'checked="checked"';
                    break;
                case 'Perlu Pembahasan Lebih Lanjut':
                    $checked_rekomendasi_4 = 'checked="checked"';
                    break;
                default:
                    break;
            }

            echo '<label class="label-wrc">Rekomendasi</label>';
            echo form_radio($rekomendasi_input_1,null,$checked_rekomendasi_1).'direkomendasikan';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo form_radio($rekomendasi_input_2,null,$checked_rekomendasi_2).'tidak direkomendasikan';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo form_radio($rekomendasi_input_3,null,$checked_rekomendasi_3).'direkomendaskan dengan catatan';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo form_radio($rekomendasi_input_4,null,$checked_rekomendasi_4).'perlu pembahasan lebih lanjut';
            echo "<br style='clear:both' />";

            $jenis_kegiatan_input_1 = array(
                'name' => 'jenis_kegiatan',
                'id' => 'jenis_kegiatan_1',
                'class' => 'required',
                'value'=>'pemeriksaan berkas',
            );
            $jenis_kegiatan_input_2 = array(
                'name' => 'jenis_kegiatan',
                'id' => 'jenis_kegiatan_2',
                'class' => 'required',
                'value'=>'survey lapangan'
            );
            $jenis_kegiatan_input_3 = array(
                'name' => 'jenis_kegiatan',
                'id' => 'jenis_kegiatan_3',
                'class' => 'required',
                'value'=>'rapat tim teknis'
            );

            $checked_jenis_kegiatan_1 = '';
            $checked_jenis_kegiatan_2 = '';
            $checked_jenis_kegiatan_3 = '';
            switch($jenis_kegiatan){
                case 'pemeriksaan berkas':
                    $checked_jenis_kegiatan_1 = 'checked="checked"';
                    break;
                case 'survey lapangan':
                    $checked_jenis_kegiatan_2 = 'checked="checked"';
                    break;
                case 'rapat tim teknis':
                    $checked_jenis_kegiatan_3 = 'checked="checked"';
                    break;
                default:
                    break;
            }

            echo '<label class="label-wrc">Jenis Kegiatan</label>';
            echo form_radio($jenis_kegiatan_input_1,null,$checked_jenis_kegiatan_1).'pemeriksaan berkas';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo form_radio($jenis_kegiatan_input_2,null,$checked_jenis_kegiatan_2).'survey lapangan';
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo form_radio($jenis_kegiatan_input_3,null,$checked_jenis_kegiatan_3).'rapat tim teknis';
            echo "<br style='clear:both' />";

            $ket_rekomendasi_input = array(
                'name' => 'ket_rekomendasi',
                'id' => 'ket_rekomendasi',
                'class' => 'input-area-wrc required'
            );
            echo '<label class="label-wrc">Keterangan</label></td>';
            echo form_textarea($ket_rekomendasi_input,set_value('ket_rekomendasi',$ket_rekomendasi));
            echo "<br style='clear:both' />";

            //Disable berdasarkan request dari kominfo
            /*echo "<br style='clear:both' />";
            echo '<label class="label-wrc">Tim Teknis</label></td>';
            echo "<br style='clear:both' />";

            $nama_tim_input = array(
                'name' => 'nama_tim',
                'id' => 'nama_tim',
                'class' => 'required'
            );
            echo '<label class="label-wrc">Nama Tim</label></td>';
            echo form_input($nama_tim_input,set_value('nama_tim',$nama_tim));
            echo "<br style='clear:both' />";

            $nip_input = array(
                'name' => 'nip',
                'id' => 'nip',
                'class' => 'required'
            );
            echo '<label class="label-wrc">NIP</label></td>';
            echo form_input($nip_input,set_value('nip',$nip));
            echo "<br style='clear:both' />";

            $nama_atasan_tim_input = array(
                'name' => 'nama_atasan_tim',
                'id' => 'nama_atasan_tim',
                'class' => 'required'
            );
            echo '<label class="label-wrc">Nama Atasan Tim</label></td>';
            echo form_input($nama_atasan_tim_input,set_value('nama_atasan_tim',$nama_atasan_tim));
            echo "<br style='clear:both' />";

            $nip_atasan_tim_input = array(
                'name' => 'nip_atasan_tim',
                'id' => 'nip_atasan_tim',
                'class' => 'required'
            );
            echo '<label class="label-wrc">NIP Atasan Tim</label></td>';
            echo form_input($nip_atasan_tim_input,set_value('nip_atasan_tim',$nama_atasan_tim));
            echo "<br style='clear:both' />";

            $jabatan_atasan_tim_input = array(
                'name' => 'jabatan_atasan_tim',
                'id' => 'jabatan_atasan_tim',
                'class' => 'required'
            );
            echo '<label class="label-wrc">Jabatan Atasan Tim</label></td>';
            echo form_input($jabatan_atasan_tim_input,set_value('jabatan_atasan_tim',$jabatan_atasan_tim));
            echo "<br style='clear:both' />";*/

            echo "<br style='clear:both' />";

            $save_form = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($save_form);
            echo "<span></span>";
            $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('rekomendasi') . '\''
            );
            echo form_button($cancel);
            ?>
        </div>
        <?php echo form_close();?>
    </div>
    <br style="clear: both;" />
</div>
