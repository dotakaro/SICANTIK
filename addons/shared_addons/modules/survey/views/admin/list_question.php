<section class="title">
    <h4><?php echo lang('survey_question:question_list'); ?></h4>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open('admin/survey_question/delete');?>
        <?php if (!empty($survey_question)): ?>
            <table border="0" class="table-list" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
                    <th><?php echo lang('survey_question:question_desc'); ?></th>
                    <th><?php echo lang('survey_question:question_type'); ?></th>
                    <th><?php echo lang('survey_question:multiple_votes'); ?></th>
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
                <?php foreach( $survey_question as $item ): ?>
                    <tr id="item_<?php echo $item->id; ?>">
                        <td><?php echo form_checkbox('action_to[]', $item->id); ?></td>
                        <td><?php echo $item->question_desc; ?></td>
                        <td><?php echo $list_question_type[$item->question_type]; ?></td>
                        <td><?php echo ($item->multiple_votes==1)?lang('survey_question:yes'):lang('survey_question:no'); ?></td>
                        <td class="actions">
                            <?php echo
                                //anchor('survey_question', lang('survey_question:view'), 'class="button" target="_blank"').' '.
                                anchor('admin/survey/edit_question/'.$item->id, lang('survey_question:edit'), 'class="button"').' '.
                                anchor('admin/survey/delete_question/'.$item->id, 	lang('survey_question:delete'), array('class'=>'button')); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="table_action_buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
            </div>
        <?php else: ?>
            <div class="no_data"><?php echo lang('survey_question:no_items'); ?></div>
        <?php endif;?>
        <?php echo form_close(); ?>
    </div>
</section>