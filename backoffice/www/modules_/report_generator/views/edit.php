<?php echo $this->load->view('add_edit_script');?>
<?php
	$group_types=json_decode($group_types);
	$direct_query_list=json_decode($direct_query_list);
?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
		<?php
            $attr = array('id' => 'mainform');//Indra
            echo form_open_multipart('report_generator/edit/', $attr);
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
					'type'=>'hidden',
					'value'=>$report_data->id,
					'readonly'=>true
                );				
                $report_code = array(
                    'name' => 'data[ReportGenerator][report_code]',
                    'value' => $report_data->report_code,
                    'class' => 'input-wrc required',
					'id'=>'ReportGeneratorReportCode'
                );	
                $short_desc = array(
                    'name' => 'data[ReportGenerator][short_desc]',
                    'value' => $report_data->short_desc,
                    'class' => 'input-wrc required',
					'id'=>'ReportGeneratorShortDesc'
                );	
                $long_desc = array(
                    'name' => 'data[ReportGenerator][long_desc]',
                    'value' => $report_data->long_desc,
                    'class' => 'input-area-wrc',
					'id'=>'ReportGeneratorLongDesc'
                );	
                $report_layout = array(
                    'name' => 'data[ReportGenerator][layout]',
                    'value' => $report_data->layout,
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
				$img_edit = array(
                    'src' => base_url().'assets/images/icon/property.png',
                    'alt' => 'Edit',
                    'title' => 'Edit',
                    'border' => '0',
                );
                $display_type = array(
                    'name' => 'data[ReportGenerator][display_type]',
                    'id'=>'ReportGeneratorDisplayType',
                    'type'=>'hidden',
                    'value'=>$report_data->display_type,
                    'readonly'=>true
                );
                //echo form_button($data);
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
				<?php foreach($report_subreports['rows'] as $index=>$subreport){?>
				<div class="subreport-block">
					<input id="ReportSubreportLayout_<?php echo $index;?>" type="text" name="data[ReportSubreport][<?php echo $index;?>][subreport_layout]" value="<?php echo $subreport->subreport_layout;?>" class="input-wrc required">
					<input type="image" name="btn_download_subreport_<?php echo $index;?>" id="btn_download_subreport_<?php echo $index;?>" src="<?php echo base_url();?>assets/images/icon/navigation-down.png">
					<input id="btn_remove_<?php echo $index;?>" type="image" name="btn_remove_<?php echo $index;?>" src="<?php echo base_url();?>assets/images/icon/cross.png" class="btn-remove-subreport">
					<br style="clear: both">
					<input id="fileSubreport<?php echo $index;?>" class="valid" type="file" name="fileSubreport<?php echo $index;?>">
					<input id="btn_upload_subreport_<?php echo $index;?>" name="btn_upload_subreport_<?php echo $index;?>" type="image" src="<?php echo base_url();?>assets/images/icon/navigation.png">
					<img id="loading_subreport_0" src="<?php echo base_url();?>assets/css/ajaxfileupload/loading.gif" style="display: none;">
				</div>
				<?php } ?>
				</div>
				
				<!--End Subreport Section-->
				
				<br style="clear: both"  class="report-integrated"/>
	            <?php
				echo form_label('Perizinan',null,array('class'=>'report-integrated'));
				echo form_dropdown('data[ReportGenerator][trperizinan_id]', $option_perizinan,$report_data->trperizinan_id,'id="ReportGeneratorTrperizinanId" class="input-select-wrc required-option report-integrated"');
				?>

                <div class="report-integrated">
                    <br style="clear: both"/>
                    <?php
    //                $opsiUnitKerja = array();
                    echo form_label('Unit Kerja','unit_kerja');
                    echo form_dropdown('data[ReportGenerator][trunitkerja_id]', $opsiUnitKerja, $report_data->trunitkerja_id,'class = "input-select-wrc required-option report-integrated" id="ReportGeneratorTrunitkerjaId"');
                    ?>
                </div>

                <br style="clear: both" />

                <?php
                echo form_label('Tipe Tampilan');
//                echo form_dropdown('data[ReportGenerator][display_type]', $display_type,$report_data->display_type,'id="ReportGeneratorDisplayType" class="input-select-wrc required-option"');
                echo form_input($display_type);
                echo $report_data->display_type;
                ?>
                <br style="clear: both" />

                <?php
				echo form_label('Tipe Laporan',null,array('class'=>'report-integrated'));
				echo form_dropdown('data[ReportGenerator][report_type]', $option_report_type,$report_data->report_type,'id="ReportGeneratorReportType" class="input-select-wrc required-option report-integrated"');
				?>
				<br style="clear: both" />
				
				<?php
				//echo form_label('Report Component');
				//echo form_dropdown('data[ReportGenerator][report_component_id]', $option_report_component,$report_data->report_component_id,'id="ReportGeneratorReportComponentId" class="required-option input-select-wrc"');
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
							<?php 
							$x=0;
							foreach($report_group_datas['rows'] as $report_group_data):
								$typeList='<option value="-1">-please select-</option>';
								foreach($group_types as $key=>$value){
									if($report_group_data->type == $key){
										$selected=' selected="true" ';	
									}else{
										$selected="";
									}
									$typeList .= '<option value ="'.$key.'" '.$selected.'>'.$value.'</option>';
								}
								
								$directQueryList='<option value="-1">-please select-</option>';
								foreach($direct_query_list as $key=>$value){
									
									if($key==$report_group_data->use_direct_query){
										$selected=' selected="true" ';
									}else{
										$selected='';
									}
									if($report_group_data->use_direct_query==0){
										$disable_detail='';
										$disable_query=' disabled="true" ';
									}elseif($report_group_data->use_direct_query==1){
										$disable_detail=' disabled="true" ';
										$disable_query='';
									}
									$directQueryList .= '<option value ="'.$key.'" '.$selected.'>'.$value.'</option>';
								}
							?>
							<tr>
							<td>
								<input class="required" name="data[ReportGroupData][<?php echo $x;?>][report_group_code]" value="<?php echo $report_group_data->report_group_code;?>"/></td><td><input style="width:100%" name="data[ReportGroupData][<?php echo $x;?>][short_desc]" value="<?php echo $report_group_data->short_desc;?>"/>
							</td>
							<td>
								<select class="required" name="data[ReportGroupData][<?php echo $x;?>][type]"><?php echo $typeList;?></select>
							</td>
							<td>
								<select class="use_direct_query required" name="data[ReportGroupData][<?php echo $x;?>][use_direct_query]"><?php echo $directQueryList;?></select>
                                <input type="button" <?php echo $disable_query;?> value="Query" class="btn_query ui-widget-content"/>
                                <input type="hidden" name="data[ReportGroupData][<?php echo $x;?>][direct_query]" value="<?php echo str_replace('"','',$report_group_data->direct_query);?>"/>
							</td>
							<td>
								<input type="button" value="Detail" class="btn_detail ui-widget-content" <?php echo $disable_detail;?>/>
								<input type="button" value="Filter" class="btn_filter ui-widget-content"/>
                                <input type="button" value="Hapus" class="btn_delete ui-widget-content"/>
                                <input type="hidden" class="report_group_data_id" name="data[ReportGroupData][<?php echo $x;?>][id]" value="<?php echo $report_group_data->id;?>"/>
								</td>
							</tr>
							<?php 
								$x++;
								endforeach;
							?>
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