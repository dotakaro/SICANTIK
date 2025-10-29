<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
		<?php
            $attr = array('id' => 'mainform');//Indra
            echo form_open('report_generator/add/', $attr);
		?>
		<div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Report Generator</b></a></li>
                    <li><a href="#tabs-2"><b>Report Group Data</b></a></li>
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
                    'class' => 'input-wrc required',
					'id'=>'ReportGeneratorLayout'
                );
				$download_layout = array(
				    'name' => 'download_layout',
				    'id' => 'download_layout',
				    'type' => 'button',
				    'content' => 'Download'
				);
				$upload_layout = array(
				    'name' => 'upload_layout',
				    'id' => 'upload_layout',
				    'type' => 'button',
				    'content' => 'Upload'
				);
				$fileToUpload = array(
				    'name' => 'fileToUpload',
				    'id' => 'fileToUpload',
				    'type' => 'file'
				);  
				?>
	            <?php
				echo form_input($id_report);
				echo form_label('Report ID');
				echo form_input($report_code);
				?>
				<br style="clear: both" />
	            <?php
				echo form_label('Short Desc');
				echo form_input($short_desc);
				?>
				<br style="clear: both" />
	            <?php
				echo form_label('Long Desc');
				echo form_textarea($long_desc);
				?>
				<br style="clear: both" />				
	            <?php
				echo form_label('Report Layout');
				echo form_input($report_layout);
				echo form_button($download_layout);
				?>
				<br style="clear: both" />
				<?php
				echo form_label('');
				echo form_upload($fileToUpload);
				echo form_button($upload_layout);
				?>
				<img id="loading" src="<?php echo base_url();?>assets/css/ajaxfileupload/loading.gif" style="display:none;">
				<br style="clear: both" />
				
				<!--Subreport section-->
				<?php
				echo form_label('Subreport Layout');
				?>
				<input id="btn_add_subreport" type="button" value="Add Sub Report" name="btn_add_subreport"/>
				<br style="clear: both" />
				<div id="container_subreport">
				</div>
				<br style="clear: both" />
				<!--End Subreport Section-->
								
	            <?php
				echo form_label('Perizinan');
				echo form_dropdown('data[ReportGenerator][trperizinan_id]', $option_perizinan,null,'id="ReportGeneratorTrperizinanId" class="input-select-wrc required-option"');
				?>
				<br style="clear: both" />		
				<?php
				echo form_label('Report Type');
				echo form_dropdown('data[ReportGenerator][report_type]', $option_report_type,null,'id="ReportGeneratorReportType" class="input-select-wrc required-option"');
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
					echo form_button($add_group);
					?>
					<table id="tbl_group" class="display" cellspacing="0" cellpadding="0" border="1">
						<thead>
							<tr>
							<th width="100px">Report Group ID</th>
							<th width="220px">Short Desc</th>
							<th width="150px">Type</th>
							<th width="250px">Direct Query</th>
							<th width="180px">Detail</th>
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