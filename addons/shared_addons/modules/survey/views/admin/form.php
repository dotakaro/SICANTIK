<section class="title">
	<!-- We'll use $this->method to switch between survey.create & survey.edit -->
	<h4><?php echo lang('survey:'.$this->method); ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">

			<ul class="fields">
                <li>
                    <label for="description">Description</label>
                <div class="input">
                    <?php echo form_input("description", set_value("description", $description),'id="description"'); ?>
                </div>
                </li>
                <li>
                    <label for="slug">Slug</label>
                    <div class="input">
                        <?php echo form_input("slug", set_value("slug", $slug),'id="slug"'); ?>
                    </div>
                </li>
                <li>
                    <label for="open_date">Open Date</label>
                <div class="input">
                    <?php echo form_input("open_date", set_value("open_date", $open_date),'id="open_date"'); ?>
                </div>
                </li>
                <li>
                        <label for="close_date">Close Date</label>
                    <div class="input">
                    <?php echo form_input("close_date", set_value("close_date", $close_date),'id="close_date"'); ?>
                </div>
                </li>
                <li>
                    <label for="active">Active</label>
                <div class="input">
                    <?php $checked = ($active == 1) ? true : false;?>
                    <?php echo form_checkbox("active", set_value("active", $active), $checked); ?>
                </div>
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