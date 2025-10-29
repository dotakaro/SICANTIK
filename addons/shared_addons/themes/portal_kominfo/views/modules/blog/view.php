{{ post }}

<div class="block">
	<div class="block-title">
		<h2>{{ title }}</h2>
	</div>
	
	<div class="block-content">
		<div class="author left">
			{{ helper:lang line="blog:written_by_label" }}
			<span><a href="{{ url:site }}user/{{ created_by:user_id }}">{{ created_by:display_name }}</a></span>
		</div>
		<div class="author right">
			<span class="icon-text">&#128340;</span><span>&nbsp; {{ helper:date timestamp=created_on }}</span>
		</div><br><br>
		<div class="shortcode-content">
			<p>{{ body }}</p>
		</div>
	</div>
</div>

{{ /post }}

<?php if (Settings::get('enable_comments')): ?>

<div id="comments">

	<div id="existing-comments">
		<h4><?php echo lang('comments:title') ?></h4>
		<?php echo $this->comments->display() ?>
	</div>

	<?php if ($form_display): ?>
		<?php echo $this->comments->form() ?>
	<?php else: ?>
	<?php echo sprintf(lang('blog:disabled_after'), strtolower(lang('global:duration:'.str_replace(' ', '-', $post[0]['comments_enabled'])))) ?>
	<?php endif ?>
</div>

<?php endif ?>
