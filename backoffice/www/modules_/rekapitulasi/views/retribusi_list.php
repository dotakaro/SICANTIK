<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
             <?php
            echo form_open(site_url('rekapitulasi/retribusi/FilterData'));
             ?>
                    <fieldset id="half">
                <legend>Daftar Per Periode</legend>
             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Tgl Daftar Awal','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                $periodeawal_input = array(
                'name' => 'tgla',
                'value' => $this->lib_date->set_date(date('Y-m-d'), -2),
                     'readOnly'=>TRUE,
                'class' => 'input-wrc',
                'class'=>'tarif'
            );
            echo form_input($periodeawal_input);
            ?>
              </div>
            </div>

             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Tgl Daftar Akhir','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                <?php
                $periodeakhir_input = array(
                'name' => 'tglb',
                'value' => $this->lib_date->set_date(date('Y-m-d'), 0),
                     'readOnly'=>TRUE,
                'class' => 'input-wrc',
                'class'=>'tarif'
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

                    $reset_data = array(
                                    'name' => 'button',
                                    'content' => 'Reset Filter',
                                    'value' => 'Reset Filter',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/retribusi') . '\''
                    );
                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                echo form_close();
                ?>
              </div>
            </div>
        </fieldset>
            <?php
            echo form_close();
        ?>
       <br>
<!--            <table cellpadding="0" cellspacing="0" border="0" class="display" id="retribusi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Izin</th>
                        <th>Izin Jadi</th>
                        <th>Retribusi Izin</th>
                        <th>Terbayar</th>
                        <th>Terhutang</th>
                    </tr>
                </thead>
                <tbody>-->
                <?php
//               $i = 0;
//                    foreach ($list as $data){
//                        $i++;
//                        $a = 0;$juma = 0;
//
//                        $mohonstatus = new tmpermohonan_trstspermohonan();
//                        $permohonan  = new tmpermohonan();
//                        $mohonizin   = new tmpermohonan_trperizinan();
//                        $retribusi   = new trretribusi();
//                        $retizin     = new trperizinan_trretribusi();
//
//
//                        $jumlah = $mohonizin->where('trperizinan_id',$data->id)->count();
//                        $pharga = $retizin->where('trperizinan_id',$data->id)->get();
//                        $harga  = $retribusi->where('id',$pharga->trretribusi_id)->get();
//
//                        $list_jumlah = $mohonizin->where('trperizinan_id',$data->id)->limit(0)->get();
//
//                         foreach ($list_jumlah as $data_relasi){  //8.11.19.20.24
//
//                            $permohonan->get_by_id($data_relasi->tmpermohonan_id);
//                            $iddaftar = $permohonan->id; //8.11.19.20.24
//                            $status = $mohonstatus->where('tmpermohonan_id',$iddaftar)->get();
//
//                            if($status->id)
//                            {
//                                 if($status->trstspermohonan_id === '14'){
//
//                                        $juma = $juma;
//                                        $juma++;
//
//
//                                }
//                            }
//
//                         }
//                            $totalanggaran = $harga->v_retribusi * $jumlah;
//                            $terbayar      = $juma * $harga->v_retribusi;
//                            $terhutang     = $totalanggaran - $terbayar;


                     
                        ?>
<!--                    <tr>
                        <td><?php  echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td align="right"> <?php echo anchor(site_url('rekapitulasi/retribusi/pick_retribusi_list'.'/'.$data->id), $jumlah, 'class="link2-wrc" rel="pendaftar_box"');?></td>
                        <td align="right"><?php 
                        if($harga->id){
                        echo "Rp".number_format($totalanggaran,2,',','.');
                        }else
                        { echo "Rp".number_format(0,2,',','.');}?></td>
                        <td align="right"><?php
                        if($harga->id){
                        echo "Rp".number_format($terbayar,2,',','.');
                        
                        }else
                        { echo "Rp".number_format(0,2,',','.');}

                        ?></td>
                        <td align="right">
                            <?php
                        if($harga->id){
                        echo "Rp".number_format($terhutang,2,',','.');
                        }else
                        { echo "Rp".number_format(0,2,',','.');}

                        ?>
                        </td>

                    </tr>-->
                <?php 
//                }
                ?>
<!--                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Jumlah</th>
                        <th>Retribusi izin</th>
                        <th>Terbayar</th>
                        <th>Terhutang</th>
                    </tr>
                </tfoot>
            </table>-->
        </div>
    </div>
    <br style="clear: both;" />
</div>
