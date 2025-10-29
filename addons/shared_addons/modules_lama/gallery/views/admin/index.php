<section class="title">
	<h4><?php echo lang('gallery:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/gallery/delete');?>
	<?php if (!empty($gallery)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('gallery:file'); ?></th>
					<th><?php echo lang('gallery:description'); ?></th>
					<th><?php echo lang('gallery:published'); ?></th>
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
				<?php foreach( $gallery as $item ): 
                                $file = Files::get_file($item->gallery_file);
                                ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
                                        <td><?php echo img('files/thumb/'.$item->gallery_file.'/50x50/fit');?>&nbsp;<?php echo anchor('files/download/'.$item->gallery_file, $file['data']->name, 'class="button"');?></td>
					<td><?php echo $item->gallery_desc; ?></td>
					<td><?php echo ($item->published==1) ? lang('gallery:yes') : lang('gallery:no'); ?></td>
					<td class="actions">
						<?php echo
						//anchor('gallery', lang('gallery:view'), 'class="button" target="_blank"').' '.
						anchor('admin/gallery/edit/'.$item->id, lang('gallery:edit'), 'class="button"').' '.
						anchor('admin/gallery/delete/'.$item->id, 	lang('gallery:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('gallery:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>