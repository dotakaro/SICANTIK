<section class="title">
	<h4><?php echo lang('pegawai:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/pegawai/delete');?>
	<?php if (!empty($pegawai)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th>Nama Pegawai</th>
					<th>NIP</th>
					<th>Jabatan</th>
					<th>Alamat</th>
					<th>No Telp</th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $pegawai as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->nama_pegawai; ?></td>
					<td><?php echo $item->nip; ?></td>
					<td><?php echo $item->jabatan; ?></td>
					<td><?php echo $item->alamat; ?></td>
					<td><?php echo $item->no_telp; ?></td>
					<td class="actions">
						<?php echo
						//anchor('pegawai', lang('pegawai:view'), 'class="button" target="_blank"').' '.
						anchor('admin/pegawai/edit/'.$item->id, lang('pegawai:edit'), 'class="button"').' '.
						anchor('admin/pegawai/delete/'.$item->id, 	lang('pegawai:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('pegawai:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>