<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="sk">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
			<th>Pemohon</th>
                        <th>Jenis Izin</th>
                        <th>Tanggal Permohonan</th>
			<th>Tanggal SK</th>
			<th>Status</th>
			<th width="70">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i=1;

                    foreach ($list as $data){
                        $data->tmpemohon->get();
                        $data->trperizinan->get();
                        $data->tmsk->get();

                        $show = FALSE;
                        if($list_bap){
                            foreach ($list_bap as $data_bap){
                                $data_sk = new tmbap_tmpermohonan();
                                $data_sk->where('tmpermohonan_id', $data->id)
                                ->where('tmbap_id', $data_bap->id)->get();
                                if($data_sk->tmpermohonan_id){
                                    if($data_bap->status_bap == $c_bap){
                                        $show = TRUE;
                                        break;
                                    }else{
                                        $show = FALSE;
                                        break;
                                    }
                                }
                            }
                        }else{
                            $show = FALSE;
                            break;
                        }
                        if($show && $data->tmsk->id){
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id;?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon;?></td>
                        <td><?php echo $data->trperizinan->n_perizinan;?></td>
                        <td>
                        <?php
                        if($data->d_entry){
                            if($data->d_entry != '0000-00-00') echo $this->lib_date->mysql_to_human($data->d_entry);
                        }
                        ?>
                        </td>
                        <td>
                        <?php
                        if($data->tmsk->tgl_surat){
                            if($data->tmsk->tgl_surat != '0000-00-00') echo $this->lib_date->mysql_to_human($data->tmsk->tgl_surat);
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                                if($data->tmsk->c_status === "2") {
                                    echo "<b>Telah Aktifkan</b>";
                                } else {
                                    echo "Non Aktif";
                                }
                            ?>
                        </td>
                         <td><center>
                          <?php
                                $img_aktif = array(
                                    'src' => base_url().'assets/images/icon/tick.png',
                                    'alt' => 'Aktivasi',
                                    'title' => 'Aktivasi',
                                    'border' => '0',
                                );
                                echo anchor(site_url('permohonan/sk/aktivasisk') .'/'. $data->id, img($img_aktif))
                                     ."&nbsp;";
                            ?></center>
                        </td>
                    </tr>
                    <?php
                        $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
