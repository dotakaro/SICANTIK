<section class="title">
	<!-- We'll use $this->method to switch between discussion.create & discussion.edit -->
	<h4><?php echo lang('discussion:topic').' : '.$topic; ?></h4>
</section>

<section class="item">
	<div class="content">
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

		<div class="form_inputs">
            <ul class="fields">
                <?php
                if(!empty($comments)){
                    foreach($comments as $comment){
                        echo '<li>';
                        echo '<div class="input">';
                            echo '<div class="comment">';
                            echo '<span class="commenter">'.$list_users[$comment->created_by].'</span>';
                            echo '<div class="comment-block">'.$comment->comment.'</div>';
                        echo '<div class="post-date">'.$comment->created.'</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                }
                ?>
                <li>
                    <label for="topic">Comment</label>
                    <div class="input">
                    <?php echo form_textarea("comment", null); ?>
                    <?php echo form_hidden("discussion_id", set_value("discussion_id", $discussion_id)); ?>
                    <?php echo form_hidden("created_by", set_value("created_by", $current_user_id)); ?>
                    </div>
                </li>
            </ul>

        </div>

	<div class="buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>

	<?php echo form_close(); ?>
    </div>
</section>