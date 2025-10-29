<h2 class="list-title">Daftar Download</h2>
<ul class="article-list">
	<?php foreach($blog_widget as $post_widget): 
		$file = Files::get_file($post_widget->file_download);
	?>		
        <li>
            <?php echo anchor('files/download/'.$post_widget->file_download, $post_widget->file_desc, '');?>
            <?php echo date('H:i, d M Y',strtotime($post_widget->created));?>
        </li>
    <?php endforeach ?>
	<?php echo anchor('daftar_layanan/daftar_download', 'Lihat Selengkapnya', 'class="more"');?>
</ul>
