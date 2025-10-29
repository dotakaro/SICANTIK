<section class="title">
	<h4><?php echo lang('survey:survey_list'); ?></h4>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/survey/delete');?>
	<?php if (!empty($survey)): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('survey:description'); ?></th>
					<th><?php echo lang('survey:open_date'); ?></th>
					<th><?php echo lang('survey:close_date'); ?></th>
					<th><?php echo lang('survey:active'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $survey as $item ): ?>
				<tr id="item_<?php echo $item->id; ?>">
					<td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
					<td><?php echo $item->description; ?></td>
					<td><?php echo $item->open_date; ?></td>
					<td><?php echo $item->close_date; ?></td>
					<td><?php echo ($item->active==1)?lang('survey:yes'):lang('survey:no'); ?></td>
					<td class="actions">
						<?php echo
						//anchor('survey', lang('survey:view'), 'class="button" target="_blank"').' '.
						anchor('admin/survey/edit/'.$item->id, lang('survey:edit'), 'class="button"').' '.
						anchor('admin/survey/list_question/'.$item->id, lang('survey:question_list'), 'class="button"').' '.
						anchor('admin/survey/view_result/'.$item->slug, lang('survey:view_result'), 'class="button"').' '.
						anchor('admin/survey/delete/'.$item->id, 	lang('survey:delete'), array('class'=>'button')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
		</div>
	<?php else: ?>
		<div class="no_data"><?php echo lang('survey:no_survey'); ?></div>
	<?php endif;?>
	<?php echo form_close(); ?>
</div>
</section>