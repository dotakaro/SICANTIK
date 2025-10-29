<div class="block-content">											
	<ul class="article-block-big">
		<?php foreach($blog_widget as $post_widget): ?>
			<li>
				<div class="article-photo">
					<?php echo img('files/thumb/'.$post_widget->gallery_file.'/200x130/fit');?>
				</div>
				<div class="article-content">
					<h4><a href="#"><?php echo $post_widget->gallery_desc;?></a></h4>
					<span class="meta">
						<a href="blog.html"><span class="icon-text">&#128340;</span><?php echo date('H:i, d M Y',strtotime($post_widget->created));?></a>
					</span>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
</div>