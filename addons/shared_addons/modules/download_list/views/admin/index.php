<section class="title">
	<h4><?php echo lang('download_list:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/download_list/delete');?>
	<?php if (!empty($download_list)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th>Description</th>
					<th>File</th>
					<th>Published</th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $download_list as $item ): 
					$file = Files::get_file($item->file_download);
				?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->file_desc; ?></td>
					<td><?php echo anchor('files/download/'.$item->file_download, $file['data']->name, '');?></td>
					<td><?php echo ($item->published==1) ? lang('download_list:yes') : lang('download_list:no'); ?></td>
					<td class="actions">
						<?php echo
						//anchor('download_list', lang('download_list:view'), 'class="button" target="_blank"').' '.
						anchor('admin/download_list/edit/'.$item->id, lang('download_list:edit'), 'class="button"').' '.
						anchor('admin/download_list/delete/'.$item->id, 	lang('download_list:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('download_list:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>