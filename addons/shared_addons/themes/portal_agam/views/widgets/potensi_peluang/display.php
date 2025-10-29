<div class="widget-articles block-content">
	<ul>
		<?php foreach($blog_widget as $post_widget): ?>
			<li>	
				<div class="article-photo">
					<a href="{{ url:site }}<?php echo 'blog/'.date('Y/m', $post_widget->created_on).'/'.$post_widget->slug?>" class="hover-effect"><?php echo img('files/thumb/'.$post_widget->thumb_image.'/59x42/fit');?></a>
				</div>
				<div class="article-content">
					<h4><a href="{{ url:site }}<?php echo 'blog/'.date('Y/m', $post_widget->created_on).'/'.$post_widget->slug?>"><?php echo $post_widget->title;?></a>
					<!--<a href="post.html#comments" class="h-comment">201</a>-->
					</h4>
					<span class="meta">
						<a href="{{ url:site }}<?php echo 'blog/'.date('Y/m', $post_widget->created_on).'/'.$post_widget->slug?>"><span class="icon-text">&#128340;</span><?php echo date('H:i, d M Y', $post_widget->created_on);?></a>
					</span>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
</div>