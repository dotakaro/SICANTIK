<div id="gallery">
  <h3>Gallery</h3>
  <ol>
    {{ gallery }}
    <li>
        <img src="{{ url:site }}files/thumb/{{ gallery_file }}/100x100/fit">
        {{ gallery_desc }}
    </li>
    {{ /gallery }}
  </ol>
</div>