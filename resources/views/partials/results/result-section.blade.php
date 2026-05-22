{{-- resources/views/partials/results/result-section.blade.php --}}
<section style="min-height:calc(100vh - 64px); background:#f5f4ff; padding:40px 24px;">
    <div style="max-width:760px; margin:0 auto; display:flex; flex-direction:column; gap:20px;">

        {{-- Page header --}}
        @include('partials.results.result-header')

        {{-- Main content --}}
        <div x-data="resultPage({{ json_encode($recommendations) }}, {{ json_encode($insight) }})"
            style="display:flex; flex-direction:column; gap:16px;">

            {{-- Top recommendation hero card --}}
            @include('partials.results.top-card')

            {{-- Other recommendations --}}
            @include('partials.results.other-cards')

            {{-- Profile insight --}}
            @include('partials.results.insight-card')

            {{-- Action buttons --}}
            @include('partials.results.result-actions')

        </div>

    </div>
</section>