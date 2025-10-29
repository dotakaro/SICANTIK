<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
		<?php
            $attr = array('id' => 'mainform');//Indra
            echo form_open_multipart('report_generator/add/', $attr);
		?>
		<div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b><i>Report Generator</i></b></a></li>
                    <li><a href="#tabs-2"><b><i>Report Group Data</i></b></a></li>
                </ul>
                <div id="tabs-1">
	            <?php
                $id_report = array(
                    'name' => 'data[ReportGenerator][id]',
                    'id'=>'ReportGeneratorId',
					'type'=>'hidden'
                );				
                $report_code = array(
                    'name' => 'data[ReportGenerator][report_code]',
                    'value' => $report_code,
                    'class' => 'input-wrc required',
					'id'=>'ReportGeneratorReportCode'
                );	
                $short_desc = array(
                    'name' => 'data[ReportGenerator][short_desc]',
                    'value' => null,
                    'class' => 'input-wrc required',
					'id'=>'ReportGeneratorShortDesc'
                );	
                $long_desc = array(
                    'name' => 'data[ReportGenerator][long_desc]',
                    'value' => null,
                    'class' => 'input-area-wrc',
					'id'=>'ReportGeneratorLongDesc'
                );	
                $report_layout = array(
                    'name' => 'data[ReportGenerator][layout]',
                    'value' => null,
                    'class' => 'input-wrc required report-integrated',
					'id'=>'ReportGeneratorLayout'
                );
				$download_layout = array(
				    'name' => 'download_layout',
				    'id' => 'download_layout',
				    'type' => 'button',
				    'content' => 'Unduh',
                    'class'=>'report-integrated'
				);
				$upload_layout = array(
				    'name' => 'upload_layout',
				    'id' => 'upload_layout',
				    'type' => 'button',
				    'content' => 'Unggah',
                    'class'=>'report-integrated'
				);
				$fileToUpload = array(
				    'name' => 'fileToUpload',
				    'id' => 'fileToUpload',
				    'type' => 'file',
                    'class'=>'report-integrated'
				);  
				?>
	            <?php
				echo form_input($id_report);
				echo form_label('ID Laporan');
				echo form_input($report_code);
				?>
				<br style="clear: both" />
	            <?php
				echo form_label('Deskripsi Singkat');
				echo form_input($short_desc);
				?>
				<br style="clear: both" />
	            <?php
				echo form_label('Deskripsi Lengkap');
				echo form_textarea($long_desc);
				?>
				<br style="clear: both"  class="report-integrated"/>
	            <?php
				echo form_label('Rancangan Laporan',null,array('class'=>'report-integrated'));
				echo form_input($report_layout);
				echo form_button($download_layout);
				?>
				<br style="clear: both" class="report-integrated"/>
				<?php
				echo form_label('',null,array('class'=>'report-integrated'));
				echo form_upload($fileToUpload);
				echo form_button($upload_layout);
				?>
				<img id="loading" src="<?php echo base_url();?>assets/css/ajaxfileupload/loading.gif" style="display:none;">

                <br style="clear: both" class="report-integrated"/>
				<!--Subreport section-->
				<?php
				echo form_label('Rancangan <i>Subreport</i>', null, array('class'=>'report-integrated'));
				?>
				<input id="btn_add_subreport" type="button" value="Tambahkan subreport" name="btn_add_subreport" class="report-integrated"/>

                <br style="clear: both"/>
				<div id="container_subreport">
				</div>
				<!--End Subreport Section-->
		<br style="clear: both" class="report-integrated"/>
	            <?php
				echo form_label('Perizinan',null,array('class'=>'report-integrated'));
				echo form_dropdown('data[ReportGenerator][trperizinan_id]', $option_perizinan,null,'id="ReportGeneratorTrperizinanId" class="input-select-wrc required-option report-integrated"');
				?>

                <div class="report-integrated">
                    <br style="clear: both"/>
                    <?php
    //                $opsiUnitKerja = array();
                    echo form_label('Unit Kerja','unit_kerja');
                    echo form_dropdown('data[ReportGenerator][trunitkerja_id]', $opsiUnitKerja, '','class = "input-select-wrc required-option report-integrated"" id="ReportGeneratorTrunitkerjaId"');
                    ?>
                </div>

				<br style="clear: both" />

                <?php
                echo form_label('Tipe Tampilan');
                echo form_dropdown('data[ReportGenerator][display_type]', $display_type,null,'id="ReportGeneratorDisplayType" class="input-select-wrc required-option"');
                ?>
                <br style="clear: both" />

                <?php
				echo form_label('Tipe Laporan',null,array('class'=>'report-integrated'));
				echo form_dropdown('data[ReportGenerator][report_type]', $option_report_type,null,'id="ReportGeneratorReportType" class="input-select-wrc required-option report-integrated"');
				?>
				<br style="clear: both" />

				<?php
				//echo form_label('Report Component');
				//echo form_dropdown('data[ReportGenerator][report_component_id]', $option_report_component,null,'id="ReportGeneratorReportComponentId" class="required-option input-select-wrc"');
				?>
				<!--<br style="clear: both" />-->						
				</div>
				<div id="tabs-2">
					<?php 
					$add_group = array(
		                'name' => 'btn_add',
						'id'=>'btn_add',
		                'class' => 'button-wrc',
		                'content' => 'Tambah');
						
					$download_empty_xml = array(
					    'name' => 'download_empty_xml',
					    'id' => 'download_empty_xml',
					    'class'=>'button-wrc',
						'type' => 'button',
					    'content' => 'Unduh Contoh XML'
					);	
					echo form_button($add_group);
					echo form_button($download_empty_xml);
					?>
					<table id="tbl_group" class="display" cellspacing="0" cellpadding="0" border="1">
						<thead>
							<tr>
							<th width="100px">ID <i>Report Group</i></th>
							<th width="220px">Deskripsi Singkat</th>
							<th width="150px">Tipe</th>
							<th width="250px"><i>Query</i> Langsung</th>
							<th width="180px">Detil</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>												
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
                'onclick' => 'parent.location=\''. site_url('report_generator').'\''
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