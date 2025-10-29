<section class="title">
	<h4><?php echo lang('dasar_hukum:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/dasar_hukum/delete');?>
	<?php if (!empty($dasar_hukum)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th>Nama Dasar Hukum</th>
					<th>PDF Dasar Hukum</th>
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
				<?php foreach( $dasar_hukum as $item ): 
					$file = Files::get_file($item->pdf_dasar_hukum);
				?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->nama_dasar_hukum; ?></td>
					<td><?php echo ($item->pdf_dasar_hukum) ? anchor('files/download/'.$item->pdf_dasar_hukum, $file['data']->name, ''): '' ;?></td>
					<td><?php echo ($item->published==1) ? lang('dasar_hukum:yes') : lang('dasar_hukum:no'); ?></td>
					<td class="actions">
						<?php echo
						//anchor('dasar_hukum', lang('dasar_hukum:view'), 'class="button" target="_blank"').' '.
						anchor('admin/dasar_hukum/edit/'.$item->id, lang('dasar_hukum:edit'), 'class="button"').' '.
						anchor('admin/dasar_hukum/delete/'.$item->id, 	lang('dasar_hukum:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('dasar_hukum:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>