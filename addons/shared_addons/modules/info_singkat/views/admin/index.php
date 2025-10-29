<section class="title">
	<h4><?php echo lang('info_singkat:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/info_singkat/delete');?>
	<?php if (!empty($info_singkat)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('info_singkat:name'); ?></th>
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
				<?php foreach( $info_singkat as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->isi_info; ?></td>
					<td class="actions">
						<?php echo
						//anchor('info_singkat', lang('info_singkat:view'), 'class="button" target="_blank"').' '.
						anchor('admin/info_singkat/edit/'.$item->id, lang('info_singkat:edit'), 'class="button"').' '.
						anchor('admin/info_singkat/delete/'.$item->id, 	lang('info_singkat:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('info_singkat:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>