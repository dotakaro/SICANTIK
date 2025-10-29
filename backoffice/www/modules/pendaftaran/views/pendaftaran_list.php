<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <?php
                echo form_open('pendaftaran/create');
                echo form_hidden('id_jenis', $id_jenis);
                ?>
                <legend>Data Permohonan</legend>
                <div class="contentForm">
                    <div class="contentForm" id="show_list_izin">
                        <?php
                        echo form_label($label_text);
                        $text_field = array(
                            'class' => 'input-wrc required',
                            'name' => 'no_daftar'
                        );
                        //if ($id_jenis === "4") {
                            echo anchor(site_url('pelayanan/pendaftaran/pick_list_other/' . $id_jenis), 'Daftar', 'class="link-wrc" rel="pick_list"');
                        /*} else {
                            echo anchor(site_url('pelayanan/pendaftaran/pick_list/' . $id_jenis), 'Daftar', 'class="link-wrc" rel="pick_list"');
                        }*/
                        ?>
                    </div>
                </div>
                <?php
                        echo form_close();
                ?>
                    </fieldset>
                <?php
                if($ket_syarat){
                    echo "<div class='entry' align=center><b style='color: #FF0000;'>Persyaratan tidak lengkap !!</b></div>";
                }
                ?>
                    <br style="clear:both;" />
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendaftaran">
                        <thead>
                            <tr>
                                <th width="2%">No</th>
                                <th width="18%">No Pendaftaran</th>
                                <th width="20%">Jenis Izin</th>
                                <th width="15%">Pemohon</th>
                                <th width="25%">Alamat</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        $i = 0;
                        foreach ($list as $data) {
                            $i++;
                            $data->trperizinan->get();
                            $izin_kelompok = $data->trperizinan->trkelompok_perizinan->get();
                            $data->tmpemohon->get();
                    ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                <?php
                              
                                echo $data->pendaftaran_id;
                                ?>
                                </td>
                                <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                                <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                                <td><?php echo $data->tmpemohon->a_pemohon; ?></td>
                                <td>
                            <?php
                            if ($data->c_paralel == 0)
                                $status = "Satu Izin";
                            else
                                $status = "Izin Paralel";
                            echo $status;
                            ?>
                        </td>
                        <td>
                            <?php
                            $img_bukti = array(
                                'src' => base_url() . 'assets/images/icon/clipboard.png',
                                'alt' => 'Cetak Bukti',
                                'title' => 'Cetak Bukti',
                                'border' => '0',
                            );
                            $img_edit = array(
                                'src' => base_url() . 'assets/images/icon/property.png',
                                'alt' => 'Edit',
                                'title' => 'Edit',
                                'border' => '0',
                            );
                            $confirm_text = 'Apakah Anda yakin permohonan izin telah selesai?';
                            $img_ok = array(
                                'src' => base_url() . 'assets/images/icon/tick.png',
                                'alt' => 'Daftar Selesai',
                                'title' => 'Daftar Selesai',
                                'border' => '0',
                                'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
                            );
                            echo anchor(site_url('pelayanan/pendaftaran/cetak_bukti') . '/' . $data->id, img($img_bukti)) . "&nbsp;";
                            echo anchor(site_url('pendaftaran/edit') . '/' . $id_jenis . '/' . $data->id, img($img_edit)) . "&nbsp;";
                            $img_recom = array(
                                'src' => base_url() . 'assets/images/icon/clipboard-doc.png',
                                'alt' => 'Buat Permohonan Rekomendasi',
                                'title' => 'Buat Permohonan Rekomendasi',
                                'border' => '0',
                            );
                            echo anchor(site_url('pendaftaran/selesai') . '/' . $data->id, img($img_ok)) . "&nbsp;";
                            $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                            $img_delete = array(
                                'src' => base_url() . 'assets/images/icon/cross.png',
                                'alt' => 'Delete',
                                'title' => 'Delete',
                                'border' => '0',
                                'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
                            );
                            echo anchor(site_url('pendaftaran/delete') . '/' . $data->id.'/'.$id_jenis, img($img_delete));
                            ?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
