<div class="block">
    <div class="block-title">
        <a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
        <h2>Dasar Hukum</h2>
    </div>

    <div class="block-content">
        <ol>
            {{ dasar_hukum }}
            {{ if pdf_dasar_hukum == '' }}
            <li>{{ nama_dasar_hukum }}</li>
            {{ else }}
            <li><a href="{{ url:site }}files/download/{{ pdf_dasar_hukum }}">{{ nama_dasar_hukum }}</a></li>
            {{ endif }}
            {{ /dasar_hukum }}
        </ol>
    </div>
</div>