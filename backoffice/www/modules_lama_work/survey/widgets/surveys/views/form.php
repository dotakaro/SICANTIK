<ol>
	<li class="even">
		<label for="survey_id"><?php echo lang('survey:survey_name') ?></label>
        <?php echo form_dropdown('survey_id', $list_survey, $options['survey_id'], 'id="survey_id"');?>
	</li>
</ol>