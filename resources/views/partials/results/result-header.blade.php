{{-- resources/views/partials/results/result-header.blade.php --}}
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">

    <div style="flex:1; min-width:0;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
            <div style="width:clamp(32px, 4vw, 36px); height:clamp(32px, 4vw, 36px); border-radius:12px; background:#4f46e5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <x-icon name="star" color="#fff" size="18" />
            </div>
            <h1 style="font-size:clamp(18px, 3vw, 22px); font-weight:700; color:#1e1b4b; letter-spacing:-0.3px; margin:0;">
                Hasil Rekomendasi
            </h1>
        </div>
        <p style="font-size:clamp(12px, 1.5vw, 14px); color:#9ca3af; margin:0 0 0 46px;"
            class="result-header-desc">
            Berdasarkan jawaban kuesioner dan percakapan AI kamu
        </p>
    </div>

    <x-button href="{{ route('questionnaire') }}" variant="outline" size="sm" class="result-header-btn">
        <x-icon name="refresh" color="#4f46e5" size="15" />
        <span class="result-header-btn-text">Ulangi Tes</span>
    </x-button>

</div>