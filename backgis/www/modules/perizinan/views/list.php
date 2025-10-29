<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
              <center><?php echo $this->session->flashdata('pesan'); ?></center>
            <?php
                $add_syaratijin = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Jenis Perizinan',
                    'onclick' => 'parent.location=\''. site_url('perizinan/create') . '\''
                );
                echo form_button($add_syaratijin);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="perizinan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Izin</th>
                        <th>Kelompok</th>
                        <th>Durasi Pengerjaan (Hari)</th>
                        <th>Berlaku Surat</th>
<!--                        <th>Status Layanan</th>-->
                        <th>Ttd Pemohon</th>
                        <th>SK</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $ok=array();
                if ($list_izin){
                    foreach ($list_izin as $dt_urg) {
                        $ok[]=$dt_urg->trperizinan_id;
                    }
                }
                
                    $i = NULL;
                    foreach ($list as $data){
                    $i++;


                    $data->trkelompok_perizinan->get();
                    $data->trunitkerja->get();
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo $data->trkelompok_perizinan->n_kelompok; ?></td>
                        <td align="center"><?php echo $data->v_hari; ?></td>
                        <td align="center"><?php echo ($data->v_berlaku_satuan!='selamanya')?$data->v_berlaku_tahun.'&nbsp;&nbsp;'.$data->v_berlaku_satuan:$data->v_berlaku_satuan; ?></td>
<!--                        <td>
                            <?php
                                if($data->is_open === '0') {
                                    echo "Izin Tertutup";
                                } else {
                                    echo "Izin Terbuka";
                                }
                            ?>
                        </td>-->
                        <td><?php if($data->c_foto == 1) echo "Ya"; else echo "Tidak"; ?></td>
                        <td><?php if($data->c_keputusan == 1) echo "Ya"; else echo "Tidak"; ?></td>
                        <td width="50">
                            <center>
                                <?php
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                ?>
                                <a class="page-help" href="<?php echo site_url('perizinan/edit'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                            </center>
                        </td>
                        <td width="50">
                            <center>

                                <?php
                                if (in_array($data->id, $ok)){
                                    
                                }else{
                                ?>
                                <a class="page-help" href="<?php echo site_url('perizinan/delete'."/".$data->id) ?>"
                                ><?php echo img($img_delete); ?></a>
                                <?php
                                }
                                ?>
                            </center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>