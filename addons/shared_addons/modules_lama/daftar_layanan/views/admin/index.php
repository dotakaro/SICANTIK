<section class="title">
	<h4><?php echo lang('daftar_layanan:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/daftar_layanan/delete');?>
	<?php if (!empty($daftar_layanan)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th style="width:20%">Jenis Izin</th>
					<th style="width:30%">Description</th>
					<th style="width:20%">File</th>
                    <th style="width:10%">Type</th>
					<th style="width:5%">Published</th>
					<th style="width:15%"></th>
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
				<?php foreach( $daftar_layanan as $item ): 
					$file = Files::get_file($item->file_download);
				?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->nama_perizinan; ?></td>
					<td><?php echo $item->file_desc; ?></td>
					<td><?php echo anchor('files/download/'.$item->file_download, $file['data']->name, '');?></td>
					<td><?php echo $item->jenis_file; ?></td>
                    <td><?php echo ($item->published==1) ? lang('daftar_layanan:yes') : lang('daftar_layanan:no'); ?></td>
					<td class="actions">
						<?php echo
						//anchor('daftar_layanan', lang('daftar_layanan:view'), 'class="button" target="_blank"').' '.
						anchor('admin/daftar_layanan/edit/'.$item->id, lang('daftar_layanan:edit'), 'class="button"').' '.
						anchor('admin/daftar_layanan/delete/'.$item->id, 	lang('daftar_layanan:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('daftar_layanan:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>