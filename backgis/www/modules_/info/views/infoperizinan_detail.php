<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
        <fieldset id="half">
            <legend>Data Permohonan</legend>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Nama Izin');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    echo $data_izin->n_perizinan;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail"  class="bg-grid">
                <?php
                    echo form_label('Kelompok Izin');
                ?>
              </div>
              <div id="rightRail"  class="bg-grid">
                <?php
                    $data_izin->trkelompok_perizinan->get();
                    echo $data_izin->trkelompok_perizinan->n_kelompok;
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Durasi Pengerjaan');
                ?>
              </div>
              <div id="rightRail">
                <?php
                    echo $data_izin->v_hari.' hari';
                ?>
              </div>
            </div>
            <div id="statusRail">
              <div id="leftRail" class="bg-grid">
                <?php
                    echo form_label('Masa Berlaku');
                ?>
              </div>
              <div id="rightRail" class="bg-grid">
                <?php
                    echo ($data_izin->v_berlaku_tahun!='')?$data_izin->v_berlaku_tahun.' ':'';
					echo $data_izin->v_berlaku_satuan;
                ?>
              </div>
            </div>
            <div style="text-align:right">
                <?php
                    $img_back = array(
                        'src' => 'assets/images/icon/back_alt.png',
                        'alt' => 'Back',
                        'title' => 'Back',
                        'border' => '0',
                    );
                    echo anchor(site_url('info/infoperizinan'), img($img_back))."&nbsp;";
                ?>
            </div>
        </fieldset>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="perizinandetail">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Syarat Izin</th>
                        <th>Status</th>
                        <th>Izin Baru</th>
                        <th>Perpanjangan</th>
                        <th>Perubahan</th>
                       
                    </tr>
                </thead>
                <tbody>
                <?php

                    foreach ($list as $data){
                        $i = null;
                        $data->trsyarat_perizinan->order_by('status', 'asc');
                        $data->trsyarat_perizinan->get();
                        foreach ($data->trsyarat_perizinan as $list_syarat) {
                            $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $list_syarat->v_syarat; ?></td>
                        <td>
                            <?php
                                if($list_syarat->status == "1") $status_data = "Wajib";
                                else $status_data = "Tidak Wajib";
                                echo form_label($status_data);
                            ?>
                        </td>
                        <td><?php
                            $show_syarat = new trperizinan_syarat();
                            $show_syarat
                            ->where('trsyarat_perizinan_id', $list_syarat->id)
                            ->where('trperizinan_id', $data->id)->get();
                            $var = $show_syarat->c_show_type;

                            $rule = strval(decbin($var));
                            if(strlen($rule) < 4) {
                                $len = 4 - strlen($rule);
                                $rule = str_repeat("0",$len) . $rule;
                            }
                            $arr_rule = str_split($rule);
                            $c_daftar_ulang = $arr_rule[0];
                            $c_baru = $arr_rule[1];
                            $c_perpanjangan = $arr_rule[2];
                            $c_ubah = $arr_rule[3];
                            $opsi_ya = "Ya";
                            $opsi_tidak = "Tidak";
                            if($c_baru == "1") echo $opsi_ya;
                            else echo $opsi_tidak;
                        ?></td>
                        <td><?php
                            if($c_perpanjangan == "1") echo $opsi_ya;
                            else echo $opsi_tidak;
                        ?></td>
                        <td><?php
                            if($c_ubah == "1") echo $opsi_ya;
                            else echo $opsi_tidak;
                        ?></td>
                       
                    </tr>
                <?php
                    } }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Syarat Izin</th>
                        <th>Status</th>
                        <th>Izin Baru</th>
                        <th>Perpanjangan</th>
                        <th>Perubahan</th>
                        
                    </tr>
                </tfoot>
            </table>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
