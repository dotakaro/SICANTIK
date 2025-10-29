<script type="text/javascript">
function validasi()
	{
            
                                    var	first=document.getElementById("firstDateInput");
		var	second=document.getElementById("secondDateInput")
			
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
            <legend>Filter Per Bulan Masuk</legend>
                <?php
                $attr = array('id' => 'form');
                echo form_open(site_url('pesan/pesanbalasan/filterdata'),$attr );
                ?>
            

            <div id="statusRail">
                <div id="leftRail">
                    
                     <?php
                    echo form_label('Periode Awal','d_tahun');
                ?>
                    
                </div>
                <div id="rightRail">
                    <?php
                $periodeawal_input = array(
                'name'  => 'tgla',
                'value' => $tgla,
                'class' => 'input-wrc required',
                'id' => 'firstDateInput',
                'readOnly'=>TRUE,
                'class' => 'cetak'
            );
            echo form_input($periodeawal_input);
            ?>
                    
                </div>
            </div>

            
            <div id="statusRail">
                <div id="leftRail">
                    
                     <?php
                    echo form_label('Periode Akhir','d_tahun');
                ?>

                    
                </div>
                <div id="rightRail">
                 <?php
                $periodeakhir_input = array(
                'name'  => 'tglb',
                'value' => $tglb,
                'class' => 'input-wrc required',
                 'id' => 'secondDateInput',
                'readOnly'=>TRUE,
                'class' => 'cetak'
            );
            echo form_input($periodeakhir_input);
            ?>
                    
                </div>
            </div>

           
              
            <div id="statusRail">

              <div id="leftRail">

              </div>
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
                                    'onclick' => 'parent.location=\''. site_url('pesan/pesanbalasan') . '\''
                    );
                    echo form_submit($filter_data);
                    echo form_button($reset_data);
                ?>

                </div>
            </div>
       </fieldset>

        <div class="entry" id="centre">
             <table cellpadding="0" cellspacing="0" border="0" class="display" id="pesan">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama Pengirim</th>
                        <th>Surat Pengaduan</th>
                        <th>Surat Pengaduan Koreksi</th>
                        <th>Media</th>
                        <th>Surat Balasan</th>
                        <th>Dinas</th>
                        <th>Penanggung Jawab</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = Null;
                    foreach ($list as $data){
                    $data->trstspesan->get();
                    $data->trsumber_pesan->get();
                    $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->nama; ?></td>
                        <td><?php echo $data->e_pesan; ?></td>
                        <td><?php echo $data->e_pesan_koreksi; ?></td>
                        <td><?php echo $data->trsumber_pesan->name; ?></td>
                        <td><?php echo $data->e_tindak_lanjut; ?></td>
                        <td><?php echo $data->c_skpd_tindaklanjut; ?></td>
                        <td><?php echo $data->nama_penanggungjawab; ?></td>
                        <td><center>
                            <?php
                                $cetak_surat = array(
                                    'src' => base_url().'assets/images/icon/clipboard.png',
                                    'alt' => 'Cetak Surat Balasan',
                                    'title' => 'Cetak Surat Balasan Pengaduan',
                                    'border' => '0',
                                );
                                echo anchor(site_url('pesan/pesanbalasan/cetak_jawaban') .'/'. $data->id, img($cetak_surat))."&nbsp;";
                            ?></center>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>NO</th>
                        <th>Nama Pengirim</th>
                        <th>Surat Pengaduan</th>
                        <th>Surat Pengaduan Koreksi</th>
                        <th>Media</th>
                        <th>Surat Balasan</th>
                        <th>Dinas</th>
                        <th>Penanggung Jawab</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
