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
			
			window.location =  "monitoringbulan/cetak_monitoring_bulan/<?php echo ($first_date."/".$second_date);?>"
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
            echo form_open("monitoring/perwaktu", $attr);
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
                    'value'=>'Cari',
                    'class' => 'button-wrc',
                    'content' => 'Cari',
                    'type' => 'submit',
                    'onclick' => 'return validasi()'
                );
			$cetak = array(
                    'name' => 'cetak',
                    'class' => 'button-wrc',
                     
                    'id' => 'cetak',
                    'content' => 'Cetak',
                    'type' => 'button',
                    'onclick' => 'return validasi()'
                );

           ?>
		   <table>
		   <tr>
		   		<td> <?php echo form_label('Periode Awal', 'd_tahun'); ?> </td>
				<td> <?php echo form_input($periodeawal_input); ?> </td>
           
		   </tr>
		   <tr>
		   		<td> <?php echo form_label('Periode Akhir', 'd_tahun'); ?> </td>
		   		<td><?php echo form_input($periodeakhir_input); ?> </td>
		   </tr>
		   <tr>
				<td><?php echo form_button($cari);
                                if($jumlah > 0){
                                echo form_button($cetak);
                                }
                                 echo form_close(); ?></td>
		   		<td>&nbsp;</td>
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
