<script type="text/javascript">
function validasi()
	{
                                    var	first=document.getElementById("tgl1");
		var	second=document.getElementById("tgl2");
			
		if(first.value=="")
		{
                                        first.focus();
                                        alert("Periode awal mohon diisi");
                                        return false;
		}
		
		else if(second.value=="")
		{
                                        second.focus();
                                        alert("Periode akhir mohon diisi");
                                        return false;
		}		
		else
		{			
                                        return true;	
		}
		
	}
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset id="half">
                <legend>Daftar Per Periode</legend>
            <?php 
            $attr = array(
                'class' => 'searchForm',
                'id' => 'searchForm'
            );
            echo form_open('rekapitulasi/realisasi/view',$attr);
                  
            ?>

            <div id="statusRail">
              <div id="leftRail">
            <?php
                  echo form_label('Tgl Daftar Awal','d_tahun');
            ?>
              </div>
              <div id="rightRail">
                 <?php
                $periodeawal_input = array(
                'name'  => 'tgla',
                'value' => $this->lib_date->set_date(date('Y-m-d'), -2),
                'id' => 'tgl1',
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
                    echo form_label('Tgl Daftar Akhir','d_tahun');
                ?>
              </div>
              <div id="rightRail">
                 <?php
                $periodeakhir_input = array(
                'name'  => 'tglb',
                'value' => $this->lib_date->set_date(date('Y-m-d'), 0),
                'id' => 'tgl2',
                'readOnly'=>TRUE,
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
                        'value' => 'Filter',
                        'onclick' => 'return validasi()'
                    );

                     $reset_data = array(
                                    'name' => 'button',
                                    'content' => 'Reset Filter',
                                    'value' => 'Reset Filter',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/realisasi') . '\''
                    );

                     $cetak_data = array(
                                    'name' => 'button',
                                    'content' => 'Cetak All',
                                    'value' => 'Cetak',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/realisasi/cetak_reportAll') . '\''
                    );

                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                    //echo form_button($cetak_data);
                    ?>
              </div>
            </div>
         
        </fieldset>
            <?php
            echo form_close();
        ?>
       <br>
<!--             <table cellpadding="0" cellspacing="0" border="0" class="display" id="realisasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Pelayanan Izin</th>
                        <th>Target Anggaran</th>
                        <th>Realisasi Pendapatan</th>

                    </tr>
                </thead>
                <tbody>-->
                <?php
//                   $i = NULL;
//                    foreach ($list as $data){
//                        $i++;
//                        $izin = new trperizinan();
//                        $izin->get_by_id($data->id);
//                        $permohonan = new tmpermohonan();
//                        $pm = $permohonan->where('c_status_bayar', 1)
//                                ->where_related($izin)->get();
//                        $jumlah = 0;
//                        foreach ($pm as $real){
//                            $baprelasi = new tmbap_tmpermohonan();
//                            $baprelasi->where('tmpermohonan_id', $real->id)->get();
//                            $bap = new tmbap();
//                            $bap->get_by_id($baprelasi->tmbap_id);
//                            if ($bap->id){
//                            $jumlah = $jumlah+$bap->nilai_retribusi;
//                            }
//
//                        }

                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td align="right"><?php if ($data->v_perizinan){
                        echo 'Rp '.$data->v_perizinan;
                        }else{
                            echo "Rp 0,00";}?></td>
                        <td align="right"><?php
                        echo 'Rp '. $jumlah.',00';
                     ?></td>
                    </tr>-->
                <?php
//                    }
                ?>
<!--                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Jenis Pelayanan Izin</th>
                        <th>Target Anggaran</th>
                        <th>Realisasi Pendapatan</th>
                    </tr>
                </tfoot>
            </table>-->
        </div>
    </div>
    <br style="clear: both;" />
</div>
