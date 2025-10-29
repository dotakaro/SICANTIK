<ul class="navigation">
    <?php foreach($blog_widget as $post_widget): ?>
        <li>
            <?php echo img('files/thumb/'.$post_widget->gallery_file.'/59x42/fit');?>
            <?php echo $post_widget->gallery_desc;?>
            <?php echo date('H:i, d M Y',strtotime($post_widget->created));?>
        </li>
    <?php endforeach ?>
</ul>