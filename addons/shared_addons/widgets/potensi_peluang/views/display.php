<ul class="navigation">
	<?php foreach($blog_widget as $post_widget): ?>
		<li>
			<?php echo img('files/thumb/'.$post_widget->thumb_image.'/59x42/fit');?>
			<?php echo anchor('blog/'.date('Y/m', $post_widget->created_on) .'/'.$post_widget->slug, $post_widget->title) ?>
		</li>
	<?php endforeach ?>
</ul>