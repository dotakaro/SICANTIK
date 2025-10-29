<section class="title">
	<h4><?php echo lang('link_website:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/link_website/delete');?>
	<?php if (!empty($link_website)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th>Nama Link</th>
					<th>URL</th>
					<th>Deskripsi</th>
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
				<?php foreach( $link_website as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->nama_link; ?></td>
					<td><?php echo anchor($item->url_link); ?></td>
					<td><?php echo $item->desc_link; ?></td>
					<td class="actions">
						<?php echo
						//anchor('link_website', lang('link_website:view'), 'class="button" target="_blank"').' '.
						anchor('admin/link_website/edit/'.$item->id, lang('link_website:edit'), 'class="button"').' '.
						anchor('admin/link_website/delete/'.$item->id, 	lang('link_website:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('link_website:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>