<script>
function conf()
{
    var x = confirm('Apakah anda yakin akan menghapusnya?');
    if (x==true)
    {
        return true;
    } else if (x==false) {
        return false;
    }
}

</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset id="half">
                <legend>Aktivitas Pembayaran Retribusi Per Periode</legend>
            <?php echo form_open('penerimaan');
                  
            ?>
                <div id="statusRail">
                    <div id="leftRail">
            <?php
                  echo form_label('Tgl Pengecekan Awal','d_tahun');
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
                    echo form_label('Tgl Pengecekan Akhir','d_tahun');
                ?>
                    </div>

                    <div id="rightRail">
           <?php
                $periodeakhir_input = array(
                'name'  => 'tglb',
                'value' => $tglb,
                'class' => 'input-wrc',
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
                        'value' => 'Filter'
                    );

                     $reset_data = array(
                                    'name' => 'button',
                                    'content' => 'Reset Filter',
                                    'value' => 'Reset Filter',
                                    'class' => 'button-wrc',
                                    );

                     $cetak_data = array(
                                    'name' => 'button',
                                    'content' => 'Cetak All',
                                    'value' => 'Cetak',
                                    'class' => 'button-wrc',
                                    'onclick' => 'parent.location=\''. site_url('rekapitulasi/realisasi/cetak_reportAll') . '\''
                    );

                    echo form_submit($filter_data);
                    echo form_reset($reset_data);
                    //echo form_button($cetak_data);
                    ?>
              </div>
            </div>
         
        </fieldset>
            <?php
            echo form_close();
        ?>
       <br>
<div class="entry">
           <table cellpadding="0" cellspacing="0" border="0" class="display" id="monitoring">
                <thead>
                    <tr>
                       <th>No</th>
                        <th>ID Permohonan</th>
                        <th>Nama Pemohon</th>
                        <th>Nama Perusahaan</th>
                        <th>Alamat Izin</th>
                        <th>Jumlah Retribusi</th>
						<th>Tanggal Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
               
                    <?php
              $i = NULL;
                $img_delete = array(
                                    'src' => 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                );
                  foreach ($user as $dt)
                            {
                          $i++;
                        ?>
                    <tr>

                        <td align="center"><?php echo $i; ?></td>
                        <td ><?php
					  	echo $dt->pendaftaran_id;
                        ?></td>
                        <td><?php echo $dt->n_pemohon; ?></td>
                        <td ><?php echo $dt->n_perusahaan; ?></td>
                        <td ><?php echo $dt->a_izin; ?></td>
                        <td ><?php echo "Rp. " . $this->terbilang->nominal($dt->nilai_retribusi); ?></td>
                        <td><?php 
                        $pecah = explode ("-",$dt->date_time);
                        $jam_ex = explode(" ",$pecah[2]);
                        echo ($jam_ex[0].'-'.$pecah[1].'-'.$pecah[0].' '.$jam_ex[1]);?></td>
                        <td>
                        
                        </td>


                    </tr><?php }?>

<!---------------------------------------------------------------------------------------------------.-->


                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>ID Permohonan</th>
                        <th>Nama Pemohon</th>
                        <th>Nama Perusahaan</th>
                        <th>Alamat Izin</th>
                        <th>Jumlah Retribusi</th>
						<th>Tanggal Pembayaran</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        </div>
    </div>
    <br style="clear: both;" />
</div>
