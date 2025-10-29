<section class="title">
	<h4><?php echo lang('discussion:item_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/discussion/delete');?>
	<?php if (!empty($discussion)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('discussion:topic'); ?></th>
                    <th><?php echo lang('discussion:from'); ?></th>
                    <th><?php echo lang('discussion:to'); ?></th>
                    <th><?php echo lang('discussion:post_date'); ?></th>
                    <th><?php echo lang('discussion:last_comment'); ?></th>
                    <th><?php echo lang('discussion:num_comment'); ?></th>
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
				<?php foreach( $discussion as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->topic; ?></td>
                    <td><?php echo $list_users[$item->created_by]; ?></td>
                    <td><?php echo $list_users[$item->message_to]; ?></td>
                    <td><?php echo $item->created; ?></td>
                    <td><?php echo $list_users[$item->created_by]; ?></td>
                    <td><?php echo $list_users[$item->created_by]; ?></td>
                    <td class="actions">
						<?php
                        echo anchor('admin/discussion/view/'.$item->id, lang('discussion:discuss'), 'class="button"').' ';
                        if($item->created_by == $current_user_id){
                            echo anchor('admin/discussion/edit/'.$item->id, lang('discussion:edit'), 'class="button"').' ';
                            echo anchor('admin/discussion/delete/'.$item->id, lang('discussion:delete'), array('class'=>'button','onclick'=>'return window.confirm(\'Are you sure want to delete this Topic?\')'));
                        }
                        ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('discussion:no_items'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>