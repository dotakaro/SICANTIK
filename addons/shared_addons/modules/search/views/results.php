<h2>{{ helper:lang line="search:results" }}</h2>

{{ search:form class="search-form" }}
    <input name="q" placeholder="Search terms..." />
{{ /search:form }}

{{ search:results }}
    {{ total }} results for "{{ query }}".
    <hr />
    {{ entries }}
        <article>
        <h4>{{ singular }}: <a href="{{ url }}">{{ title }}</a></h4>
        <p>{{ description }}</p>
        </article>
    {{ /entries }}
    {{ pagination }}
{{ /search:results }}