<?php 
$config['prev_tag_open'] = "<a>";
$config['prev_tag_close'] = "</a>"; 
$this->pagination->initialize($config); 
?>
<div class="block">
	<div class="block-title">
		<a href="{{ url:site }}" class="right">Kembali ke halaman utama</a>
		<h2>{{ category:title }}</h2>
	</div>
	<div class="block-content">
	{{ if posts }}
		{{ posts }}
		
			<div class="article-big">
				<div class="article-photo">
					{{if thumb_image:filename }}
						<a href="{{ url }}"><img src="{{ url:site }}files/thumb/{{thumb_image:filename}}/210x140/fit"></a>
					{{else}}
						<a href="{{ url }}"><img src="{{ url:site }}files/thumb/7343d2cde135a487ebcfdf50bd5e24f9.jpg/210x140/fit"></a>
					{{ endif }}
				</div>
				<div class="article-content">
					<h2><a href="{{ url }}">{{ title }}</a></h2>
					<span class="meta">
						<a href="{{ url }}"><span class="icon-text">&#128340;</span>{{ helper:date timestamp=created_on }}</a>
					</span>
					<p>{{ preview }}</p>
					<span class="meta">
						<a href="{{ url }}" class="more">Baca selengkapnya<span class="icon-text">&#9656;</span></a>
					</span>
				</div>
			</div>

		{{ /posts }}

		{{ pagination }}

	{{ else }}
		<div class="article-big">
			{{ helper:lang line="blog:currently_no_posts" }}
		</div>
	{{ endif }}
	</div>
</div>