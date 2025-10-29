<div class="breaking-news">
	<span class="the-title">Info Singkat</span>
	<div class="slide_container" style="position: relative; overflow: hidden; height: 18px;">
		<ul style="height: 24px; width: 3050px; position: absolute; left: -165.955px;">				
		<?php foreach($widget_info as $info_widget): ?>
		<li>
		<a href="#"><?php echo $info_widget->isi_info; ?></a>
		</li>
		<?php endforeach;?>
		</ul></div>
</div>