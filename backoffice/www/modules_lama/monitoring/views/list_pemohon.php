<script type="text/javascript">
function validasi()
	{
		var	first=document.forms[0].first_date.value;
		var	second=document.forms[0].second_date.value;
			
		if(first.length==0)
		{
		document.forms[0].first_date.focus();
		alert("Periode awal mohon diisi");
		return false;
		}
		
		else if(second.length==0)
		{
		document.forms[0].second_date.focus();
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
            <?php
            $attr = array(
                'class' => 'searchForm',
                'id' => 'searchForm'
            );
            echo form_open("monitoring/pemohon", $attr);
			
			$periodeawal_input = array(
                        'name' => 'first_date',
                        'class' => 'monbulan',
                        'id' => 'firstDateInput',
                              'readOnly'=>TRUE,
                        'value' => $first_date
                    );
					
			$periodeakhir_input = array(
                        'name' => 'second_date',
                        'class' => 'monbulan',
                        'id' => 'secondDateInput',
                              'readOnly'=>TRUE,
                        'value' => $second_date
                    );
			
			$cari = array(
                        'name' => 'submit',
                        'class' => 'button-wrc',
                        'content' => 'Cari',
                        'type' => 'submit',
                        'onclick' => 'return validasi()'
                    );
                    
			?>
			<table>
			<tr>
				<td> <?php echo form_label('Nama Pemohon', 'label_izin'); ?></td>
				<td><!--select class="input-select-wrc" id="selector" name="nama">
                    <option value="xx" selected="selected">------Pilih salah satu------</option>
                     <?php
                    for ($i = 65; $i < 91; $i++) {
                    ?>
                     
                      <option value='<?php echo chr($i);?>'
                        <?php if ($nama == chr($i)){echo "selected";}?>
                        ><?php echo chr($i);?> </option>
                    <?php
                    
                    }
                 
                    ?>

                </select!-->
                                    <input type="text" class="input-wrc required" name="nama" id="nama" value="<?php echo $nama; ?>" />
				</td>
			
			</tr>
			<tr>
				<td><?php echo form_label('Periode Awal', 'd_tahun'); ?></td>
				<td><?php echo form_input($periodeawal_input); ?></td>
			</tr>
			<tr>
				<td><?php echo form_label('Periode Akhir', 'd_tahun'); ?></td>
				<td> <?php echo form_input($periodeakhir_input); ?></td>
			</tr>
			</table>
			<table>
			<tr>
				<td><?php echo form_button($cari); echo form_close(); ?></td>
<?php             
  echo form_open('monitoring/monitoring_pemohon/cetak_monitoring_ambil');
  echo form_hidden('nama',$nama);
  echo form_hidden('first_date',$first_date);
  echo form_hidden('second_date',$second_date); 

     $cetak = array(
     	'name' => 'cetak',
        'class' => 'button-wrc',
        'id' => 'cetak',
        'content' => 'Cetak',
        'type' => 'submit',
        'onclick' => 'return validasi()'
        		);
?>
				
				<td><?php
                                if ($jumlah > 0){
                                echo form_button($cetak);
                                }
                                
                                echo form_close();
                                ?></td>
			</tr>
			</table>
         
        </fieldset>

        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="monitoring">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Status Permohonan</th>
                        <th>Alamat Pemohon</th>
                        <th>Kelurahan</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="dataTables_empty">Tidak ada  data..</td>
                    </tr>
                </tbody>
               
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
