<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                $add_role = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Tambah Nilai Retribusi',
                    'onclick' => 'parent.location=\''. site_url('retribusi/create') . '\''
                );
                 echo "<p align='center' style='color:red; font-weight:bold;'>".$this->session->flashdata('warning')."</p>";
                 echo form_button($add_role);
            ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="retribusi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Izin</th>
                        <th>Nilai Dasar Retribusi</th>
                        <th>Biaya Formulir</th>
                        <th>Tanggal Mulai Berlaku</th>
                        <th>Tanggal Akhir Berlaku</th>
                        <th>Model Perhitungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    foreach ($list as $data){
                        $i++;
                        $data->trperizinan->get();
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php 
                            echo $data->trperizinan->n_perizinan;
                        ?></td>
                        <td><?php echo $data->v_retribusi; ?></td>
                        <td><?php echo $data->v_denda; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_sk_terbit); ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_sk_berakhir); ?></td>
                        <td><?php $metode =  $data->m_perhitungan;
                        if($metode=="1")
                        {
                            echo "Manual";
                        }
                        else
                        {
                            echo "Otomatis";
                        }
                        ?></td>

                        <td width="10%">
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
                                <a class="page-help" href="<?php echo site_url('retribusi/edit'."/".$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <a class="page-help" href="<?php echo site_url('retribusi/delete'."/".$data->id) ?>"
                                ><?php echo img($img_delete); ?></a>
                            </center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Izin</th>
                        <th>Nilai Dasar Retribusi</th>
                        <th>Biaya Formulir</th>
                        <th>Tanggal Mulai Berlaku</th>
                        <th>Tanggal Akhir Berlaku</th>
                        <th>Model Perhitungan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
