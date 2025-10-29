<section class="title">
	<h4><?php echo lang('perizinan_online:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/perizinan_online/delete');?>
	<?php if (!empty($perizinan_online)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('perizinan_online:no_pendaftaran'); ?></th>
                    <th><?php echo lang('perizinan_online:jenis_izin'); ?></th>
                    <th><?php echo lang('perizinan_online:id_pemohon'); ?></th>
                    <th><?php echo lang('perizinan_online:jenis_identitas'); ?></th>
                    <th><?php echo lang('perizinan_online:nama_pemohon'); ?></th>
                    <th><?php echo lang('perizinan_online:telp_pemohon'); ?></th>
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
				<?php foreach( $perizinan_online as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->no_pendaftaran; ?></td>
                    <td><?php echo $item->nama_perizinan; ?></td>
                    <td><?php echo $item->id_pemohon; ?></td>
                    <td><?php echo $item->jenis_identitas; ?></td>
                    <td><?php echo $item->telp_pemohon; ?></td>
					<td class="actions">
						<?php echo
						//anchor('perizinan_online', lang('perizinan_online:view'), 'class="button" target="_blank"').' '.
						//anchor('admin/perizinan_online/edit/'.$item->id, lang('perizinan_online:edit'), 'class="button"').' '.
                        anchor('admin/perizinan_online/view/'.$item->id, lang('perizinan_online:view'), 'class="button"').' '.
						anchor('admin/perizinan_online/delete/'.$item->id, 	lang('perizinan_online:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('perizinan_online:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>