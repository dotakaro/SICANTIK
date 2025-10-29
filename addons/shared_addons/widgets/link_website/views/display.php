<h2 class="list-title">Link ke Website Terkait</h2>
<ul class="article-list">
    <?php foreach($blog_widget as $post_widget): 
	?>		
        <li>
            <?php echo anchor($post_widget->url_link, $post_widget->nama_link, 'title="'.$post_widget->desc_link.'" target="_blank"');?>
        </li>
    <?php endforeach ?>
</ul>