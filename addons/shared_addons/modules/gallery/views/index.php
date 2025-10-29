<style>
	#lightbox{
		position:absolute;
	}
</style>
<div class="block">
	<div class="block-title">
		<a class="right" href="{{ url:site }}">Kembali ke halaman utama</a>
		<h2>Gallery</h2>
	</div>
	<ul class="block-content photo-gallery-grid-2">
		{{ gallery }}
		<li class="photo-gallery-block-2">
			<div class="gallery-photo">
				<a class="hover-effect delegate" href="#">
					<span style="font-size:28.333333333333332px;" class="cover">
						<i></i>
						<!--<a data-lightbox="Galeri" href="{{ url:site }}files/large/{{ gallery_file }}"><img src="{{ url:site }}files/thumb/{{ gallery_file }}/144x106/fit"></a>-->
                        <a class="Galeri" title="{{ gallery_desc }}" href="{{ url:site }}files/large/{{ gallery_file }}"><img src="{{ url:site }}files/thumb/{{ gallery_file }}/144x106/fit"></a>
					</span>
				</a>
			</div>
			<div class="gallery-content">
				<p>{{ gallery_desc }}</p>
			</div>
		</li>			
		{{ /gallery }}
	</ul>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".Galeri").colorbox({rel:'group2', transition:"fade",photo:true,maxWidth:"75%"});
    });
</script>