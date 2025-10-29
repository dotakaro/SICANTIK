<section class="title">
	<h4><?php echo lang('pengaduan_online:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/pengaduan_online/delete');?>
	<?php if (!empty($pengaduan_online)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('pengaduan_online:nama'); ?></th>
                                        <th><?php echo lang('pengaduan_online:alamat'); ?></th>
                                        <th><?php echo lang('pengaduan_online:provinsi'); ?></th>
                                        <th><?php echo lang('pengaduan_online:kabupaten'); ?></th>
                                        <th><?php echo lang('pengaduan_online:kelurahan'); ?></th>
                                        <th><?php echo lang('pengaduan_online:kecamatan'); ?></th>
                                        <th><?php echo lang('pengaduan_online:deskripsi_pengaduan'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $pengaduan_online as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->nama; ?></td>
                                        <td><?php echo $item->alamat; ?></td>
                                        <td><?php echo $item->provinsi_text; ?></td>
                                        <td><?php echo $item->kabupaten_text; ?></td>
                                        <td><?php echo $item->kelurahan_text; ?></td>
                                        <td><?php echo $item->kecamatan_text; ?></td>
                                        <td><?php echo $item->deskripsi_pengaduan; ?></td>
					<td class="actions">
						<?php echo
						//anchor('perizinan_online', lang('perizinan_online:view'), 'class="button" target="_blank"').' '.
						//anchor('admin/pengaduan_online/edit/'.$item->id, lang('pengaduan_online:edit'), 'class="button"').' '.
						anchor('admin/pengaduan_online/delete/'.$item->id, 	lang('pengaduan_online:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('pengaduan_online:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>