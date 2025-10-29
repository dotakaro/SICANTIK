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
        <div class="entry">
               <fieldset id="half">
                <legend>Daftar Per Periode</legend>
             <?php
            echo form_open(site_url('rekapitulasi/view'));                 
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
                'readOnly'=>TRUE,
                'id' => 'tgl1',
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

                     
<!--             <div id="statusRail">
              <div id="leftRail">
                <?php
                    echo form_label('Berdasarkan Tahun','d_tahun');
                ?>
              </div>
              <div id="rightRail">
               <select class="input-select-wrc" name="d_tahun">

                <?php
                    echo "<option>-Pilih Tahun-</option>";
                    foreach ($list_tahun as $row){
                        echo "<option value=".$row->d_tahun.">".$row->d_tahun."</option>";
                    }

                ?>
                  </select>
              </div>
            </div>-->
             <div id="statusRail">
              <div id="leftRail"></div>
              <div id="rightRail">
                <?php
                
                   $filter_data = array(
                        'name' => 'submit',
                        'class' => 'submit-wrc',
                        'type' => 'submit',
                        'value' => 'Filter',
                       'onclick' => 'return validasi()'
                    );

                     $reset_data = array(
                                    'name' => 'button',
                                    'content' => 'Reset Filter',
                                    'value' => 'Reset Filter',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/rekapitulasi') . '\''
                    );

                     $cetak_data = array(
                                    'name' => 'button',
                                    'content' => 'Cetak All',
                                    'value' => 'Cetak',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/realisasi/cetak_LapAll') . '\''
                    );

                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                    //echo form_button($cetak_data);
            echo form_close();
                ?>
              </div>
            </div>
        </fieldset>
       <br>
<!--            <table cellpadding="0" cellspacing="0" border="0" class="display" id="rekapitulasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Izin</th>
                        <th>Jumlah Pemohon Izin</th>
                </thead>
                <tbody>-->
                <?php
//                    $i = NULL;
//                    foreach ($list as $data){
//                        $i++;
//                        $g = 0;
//                        $y = 0;
//                        $o = 0;
//                        $jumlah = 0;
//                        $z = 0;
//                        $relasi = new tmpermohonan_trperizinan();
//                        $list_relasi = $relasi->where('trperizinan_id',$data->id)->get();
//                        $jum1 = $relasi->where('trperizinan_id',$data->id)->count();
                        
                ?>
<!--                    <tr>
                        <td><?php  echo $i; ?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td align="right"><?php echo anchor(site_url('rekapitulasi/detail').'/'. $data->id, $jum1, 'class="link2-wrc" rel="rekapitulasi_box"'); ?></td>
                    </tr>-->
                <?php
//                     }
                ?>
<!--                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Jenis Izin</th>
                        <th>Jumlah Pemohon Izin</th>
                </tfoot>
            </table>-->
        </div>
    </div>
    <br style="clear: both;" />
</div>
