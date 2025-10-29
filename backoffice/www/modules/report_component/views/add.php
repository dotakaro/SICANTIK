<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
		<?php
            $attr = array('id' => 'mainform');//Indra
            echo form_open('report_component_code/add/', $attr);
		?>
		<div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>No Surat</b></a></li>
                    <li><a href="#tabs-2"><b>Penandatangan</b></a></li>
                </ul>
                <div id="tabs-1">
	            <?php
                $id_report_component = array(
                    'name' => 'data[ReportComponent][id]',
                    'id'=>'ReportGeneratorId',
					'type'=>'hidden'
                );				
                $report_component_code = array(
                    'name' => 'data[ReportComponent][report_component_code]',
                    'value' => $report_component_code,
                    'class' => 'input-wrc required',
					'id'=>'ReportGeneratorReportCode'
                );	
                $short_desc = array(
                    'name' => 'data[ReportComponent][short_desc]',
                    'value' => null,
                    'class' => 'input-wrc',
					'id'=>'ReportComponentShortDesc'
                );	
                $format_nomor = array(
                    'name' => 'data[ReportComponent][format_nomor]',
                    'value' => null,
                    'class' => 'input-area-wrc required',
					'id'=>'ReportComponentFormatNomor'
                );
				$last_num_seq = array(
                    'name' => 'data[ReportComponent][last_num_seq]',
                    'value' => null,
                    'class' => 'input-wrc required',
					'id'=>'ReportComponentLastNumSeq'
                );
                $nama_penandatangan = array(
                    'name' => 'data[ReportComponent][nama_penandatangan]',
                    'value' => null,
                    'class' => 'input-wrc required',
					'id'=>'ReportComponentNamaPenandatangan'
                );
				$jabatan = array(
                    'name' => 'data[ReportComponent][jabatan]',
                    'value' => null,
                    'class' => 'input-wrc required',
					'id'=>'ReportComponentJabatan'
                );
				$nip = array(
                    'name' => 'data[ReportComponent][nip]',
                    'value' => null,
                    'class' => 'input-wrc required',
					'id'=>'ReportComponentNip'
                );
				$nama_kantor = array(
                    'name' => 'data[ReportComponent][nama_kantor]',
                    'value' => null,
                    'class' => 'input-wrc',
					'id'=>'ReportComponentNamaKantor'
                );
				  
				?>
	            <?php
				echo form_input($id_report_component);
				echo form_label('ID <i>Report Component</i>');
				echo form_input($report_component_code);
				?>
				<br style="clear: both" />
	            <?php
				echo form_label('Deskripsi Singkat');
				echo form_input($short_desc);
				?>
				<br style="clear: both" />
	            <?php
				echo form_label('Format Nomor');
				echo form_textarea($format_nomor);
				?>
				<br style="clear: both" />
	            <?php
				echo form_label('Nomor Urut Terakhir');
				echo form_input($last_num_seq);
				?>
				<br style="clear: both" />				
	            <?php
				echo form_label('Perizinan');
				echo form_dropdown('data[ReportComponent][trperizinan_id]', $option_perizinan,null,'id="ReportComponentTrperizinanId" class="input-select-wrc required-option"');
				?>
				<br style="clear: both" />

                <?php
                //                $opsiUnitKerja = array();
                echo form_label('Unit Kerja','unit_kerja');
                echo form_dropdown('data[ReportComponent][trunitkerja_id]', $opsiUnitKerja, '', 'class = "input-select-wrc required-option" id="ReportGeneratorTrunitkerjaId"');
                ?>
                <br style="clear: both" />

				<?php
				echo form_label('Tipe Laporan');
				echo form_dropdown('data[ReportComponent][report_type]', $option_report_type,null,'id="ReportComponentReportType" class="input-select-wrc required-option"');
				?>
				<br style="clear: both" />						
				</div>
				<div id="tabs-2">
				<?php
				echo form_label('Nama Penandatangan');
				echo form_input($nama_penandatangan);
				?>
				<br style="clear: both" />				
	            <?php
				echo form_label('Jabatan');
				echo form_input($jabatan);
				?>
				<br style="clear: both" />				
	            <?php
				echo form_label('NIP');
				echo form_input($nip);
				?>
				<br style="clear: both" />				
	            <?php
				echo form_label('Kantor');
				echo form_input($nama_kantor);
				?>							
				</div>
				
            </div>
            <?php
            $add_koefisien = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_koefisien);
            echo "<span></span>";
            $cancel_koefisien = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('report_component').'\''
            );
            echo form_button($cancel_koefisien);
			
			?>
        </div>
		<?php echo form_close();?>
    </div>
	
	<!--Dialog Popup-->
	<div id="query_form" style="display:none ;">
        <div class="input text">
			<?php echo form_textarea(array('name'=>'query_text','id'=>'query_text'));?>
        </div>
 	</div>
	<!---->
	
    <br style="clear: both;" />
</div>