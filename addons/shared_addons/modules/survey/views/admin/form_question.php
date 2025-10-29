<section class="title">
	<!-- We'll use $this->method to switch between survey_question.create & survey_question.edit -->
	<h4><?php echo lang('survey_question:'.$this->method); ?></h4>
</section>
<?php //echo "<pre>";print_r($survey_options);exit();?>
<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
                <li>
                    <label for="survey"><?php echo lang('survey_question:survey_name'); ?></label>
                    <div class="input">
                        <?php echo form_dropdown("survey_id", $list_survey, set_value("survey_id", $survey_id)); ?>
                    </div>
                </li>
				<li>
                    <label for="question_desc"><?php echo lang('survey_question:question_desc'); ?></label>
                    <div class="input">
                        <?php echo form_input("question_desc", set_value("question_desc", $question_desc),'style="width:100%;"'); ?>
                    </div>
                </li>
                <li>
                    <label for="question_type"><?php echo lang('survey_question:question_type'); ?></label>
                    <div class="input">
                        <?php echo form_dropdown("question_type", $list_question_type, set_value("question_type", $question_type),'id="question_type"'); ?>
                    </div>
                </li>
<!--                <li class="show-on-option">-->
<!--                    <label for="multiple_votes">--><?php //echo lang('survey_question:multiple_votes'); ?><!--</label>-->
<!--                    <div class="input">-->
<!--                        --><?php //echo form_checkbox("multiple_votes", set_value("multiple_votes", $multiple_votes)); ?>
<!--                    </div>-->
<!--                </li>-->
                <?php echo form_hidden("multiple_votes", set_value("multiple_votes", $multiple_votes));?>
                <li class="show-on-option">
                    <label for="question_type"><?php echo lang('survey_question:option'); ?></label>
                    <input class="button" type="button" id="add_new_option" value="<?php echo lang('survey_question:add_option'); ?>" />
                    <table id="question_options">
                        <?php
                        echo '<tr>';
                        echo '<th>Option Value</th>';
                        echo '<th>Weight</th>';
                        echo '<th>Action</th>';
                        echo '</tr>';
                        if(isset($survey_options) && !empty($survey_options)){
                            foreach($survey_options as $key=>$survey_option){
                                echo '<tr>';
                                    echo '<td><input type="text" name="survey_option['.$key.'][option_desc]" id="survey_option_'.$key.'_option_desc" value="'.$survey_option->option_desc.'"/></td>';
                                    echo '<td><input type="number" name="survey_option['.$key.'][weight]" id="survey_option_'.$key.'_weight" value="'.$survey_option->weight.'"/></td>';
                                    echo '<td><input class="button btn-del-option" type="button" value="Delete"/></td>';
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </li>

			<!-- <li>
				<label for="fileinput">Fileinput
					<?php if (isset($fileinput->data)): ?>
					<small>Current File: <?php echo $fileinput->data->filename; ?></small>
					<?php endif; ?>
					</label>
				<div class="input"><?php echo form_upload('fileinput', NULL, 'class="width-15"'); ?></div>
			</li> -->
		    </ul>

	    </div>

        <div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
        </div>

	<?php echo form_close(); ?>
</div>
</section>