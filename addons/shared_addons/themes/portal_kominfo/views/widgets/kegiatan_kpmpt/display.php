<div class="block-content">
	<?php foreach($blog_widget as $post_widget): ?>
	<div class="wide-article">
		<div class="article-photo">
			<a class="hover-effect delegate" href="{{url:site}}blog/<?php echo date('Y/m', $post_widget->created_on) .'/'.$post_widget->slug; ?>">
				<span style="font-size:20px;" class="cover"><i></i>
				<?php echo img('files/thumb/'.$post_widget->thumb_image.'/160x117/fit');?>
				</span>
			</a>
		</div>
		<div class="article-content">
			<h2><?php echo anchor('blog/'.date('Y/m', $post_widget->created_on) .'/'.$post_widget->slug, $post_widget->title); ?>
			<!--<a class="h-comment" href="post.html#comments">94</a>-->
			</h2>
			<span class="meta">
				<a href="{{url:site}}blog/<?php echo date('Y/m', $post_widget->created_on) .'/'.$post_widget->slug; ?>"><span class="icon-text">🕔</span>
				<?php echo date('H:i, d M Y',$post_widget->created_on);?></a>
				<span class="tag">Updated</span>
			</span>
			<p><?php echo $post_widget->intro;?>...</p>
		</div>
	</div>
	<?php endforeach ?>
</div>