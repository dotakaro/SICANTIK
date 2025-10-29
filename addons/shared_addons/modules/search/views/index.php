<div class="page-heading clearfix">
	<h2 class="headline">{{ helper:lang line="search:results" }}</h2>

	{{ search:form class="search-form ps-searchbox clearfix" }}
		<div class="col left">
			<div class="ps-box">
				<input type="text" name="q" placeholder="Cari..." />
				<input type="submit" value="Cari" class="btn gray" />
			</div>	
		</div>
	{{ /search:form }}
</div>

{{ search:results }}
	
    {{ total }} hasil untuk pencarian "{{ query }}".
    <hr />
	<section class="searchList">
    {{ entries }}
        <article class="clearfix">
			{{ if image_id }}
			<a class="featuredImg floatL" href="{{ url }}">
				<img width="120" height="90" src="{{ url:base }}files/thumb/{{ image_id }}/120x90/fit" alt="{{ title }}">
			</a>
			{{ endif }}
			<div class="summary" {{ if image_id }}style="margin-left: 130px;"{{ endif }}>
				<h3 itemprop="headline">{{ singular }}: 
					<a href="{{ url }}">
						{{ if image_id }}
							{{ excerpt:generate string=title limit="45" }}
						{{ else }}
							{{ title }}
						{{ endif }}
					</a>
				</h3>
				<p>{{ excerpt:generate string=description limit="300" }}</p>
			</div>
        </article>
    {{ /entries }}
	</section>
    {{ pagination }}
{{ /search:results }}