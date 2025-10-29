<div class="block">
	<div class="block-title">
		<a class="right" href="{{ url:site }}">Kembali ke halaman utama</a>
		<h2>Pegawai</h2>
	</div>
	<ul class="block-content photo-gallery-grid-2">
		{{ pegawai }}
		<li class="photo-gallery-block-2">
			<div class="gallery-photo">
				<a class="hover-effect delegate" href="#">
					<span style="font-size:28.333333333333332px;" class="cover">
						<i></i>
                        <a class="ajax" href="{{ url:site }}pegawai/view/{{ id }}" title="{{ nama_pegawai }}">
                            <img src="{{ url:site }}files/thumb/{{ foto }}/110x165/fit">
                        </a>
					</span>
				</a>
			</div>
			<div class="gallery-content">
				<p>{{ nama_pegawai }}<br>
				{{ jabatan }}</p>
			</div>
		</li>			
		{{ /pegawai }}
	</ul>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".ajax").colorbox({height:'75%'});
    });
</script>