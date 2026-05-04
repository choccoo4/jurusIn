{{-- resources/views/partials/landing/stats-bar.blade.php --}}
<section style="background:#4f46e5; padding:40px 24px;">
    <div style="max-width:1100px; margin:0 auto; display:grid; grid-template-columns:repeat(4,1fr); gap:24px;">
        @foreach($stats as $stat)
            <x-stat-card :value="$stat['value']" :label="$stat['label']" />
        @endforeach
    </div>
</section>
