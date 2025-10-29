<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">
                <legend>Filter Data</legend>
                <?php echo form_open('hitung_retribusi');

                ?>
                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo form_label('Tgl Permohonan Awal','d_tahun');
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        $periodeawal_input = array(
                            'name'  => 'tgla',
                            'value' => $tgla,
                            'class' => 'input-wrc',
                            'readOnly'=>TRUE,
                            'class' => 'monbulan'
                        );
                        echo form_input($periodeawal_input);
                        ?>

                    </div>

                </div>

                <div id="statusRail">
                    <div id="leftRail">
                        <?php
                        echo form_label('Tgl Permohonan Akhir','d_tahun');
                        ?>
                    </div>
                    <div id="rightRail">
                        <?php
                        $periodeakhir_input = array(
                            'name'  => 'tglb',
                            'value' => $tglb,
                            'class' => 'input-wrc',
                            'class' => 'monbulan'
                        );
                        echo form_input($periodeakhir_input);
                        ?>

                    </div>

                </div>

                <div id="statusRail">
                    <div id="leftRail"></div>
                    <div id="rightRail">
                        <?php
                        $filter_data = array(
                            'name' => 'button',
                            'class' => 'button-wrc',
                            'content' => 'Filter',
                            'value' => 'Filter'
                        );

                        echo form_submit($filter_data);
                        ?>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
            </fieldset>
        </div>

        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="hitung_retribusi">
                <thead>
                <tr>
                    <th style="width:5%">No</th>
                    <th style="width:10%">No Pendaftaran</th>
                    <th style="width:20%">Pemohon</th>
                    <th style="width:30%">Jenis Izin</th>
                    <th style="width:10%">Tanggal Permohonan</th>
                    <th style="width:15%">Nilai Retribusi</th>
                    <th style="width:10%">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($list as $key=>$data){
                    ?>
                    <tr>
                        <td><?php echo ($key+1);?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($data->d_terima_berkas);?></td>
                        <td><?php echo 'Rp. '.number_format($data->retribusi->nilai_retribusi,0,',','.');?></td>
                        <td style="text-align:center;">
                            <?php
                            if($data->retribusi->id){
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Hitung Ulang',
                                    'title' => 'Hitung Ulang',
                                    'border' => '0',
                                );
                                $img_view = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Lihat Hasil Perhitungan',
                                    'title' => 'Lihat Hasil Perhitungan',
                                    'border' => '0',
                                );
                            ?>
                                <a class="page-help" href="<?php echo site_url('hitung_retribusi/hitung'."/".$data->id); ?>">
                                    <?php echo img($img_edit); ?>
                                </a>
                                <a class="page-help" href="<?php echo site_url('hitung_retribusi/view'."/".$data->id); ?>">
                                    <?php echo img($img_view); ?>
                                </a>
                            <?php }else{
                                $img_edit = array(
                                    'src' => 'assets/images/icon/property.png',
                                    'alt' => 'Hitung',
                                    'title' => 'Hitung',
                                    'border' => '0',
                                );
                            ?>
                                <a class="page-help" href="<?php echo site_url('hitung_retribusi/hitung'."/".$data->id); ?>">
                                    <?php echo img($img_edit); ?>
                                </a>
                            <?php }?>
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
