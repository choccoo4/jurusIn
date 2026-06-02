{{-- resources/views/partials/questionnaire/quiz-header.blade.php --}}
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">

    <div style="flex:1; min-width:0;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
            <div style="width:clamp(32px, 4vw, 36px); height:clamp(32px, 4vw, 36px); border-radius:12px; background:#4f46e5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <x-icon name="file-text" color="#fff" size="18" />
            </div>
            <h1 style="font-size:clamp(18px, 3vw, 22px); font-weight:700; color:#1e1b4b; letter-spacing:-0.3px; margin:0;">
                Kuesioner Minat
            </h1>
        </div>
        <p style="font-size:clamp(12px, 1.5vw, 14px); color:#9ca3af; margin:0 0 0 46px;"
            class="quiz-header-desc">
            Jawab dengan jujur untuk hasil rekomendasi yang paling akurat
        </p>
    </div>

    <x-button href="{{ route('home') }}" variant="outline" size="sm" class="quiz-header-btn">
        <x-icon name="chevron-left" color="#4f46e5" size="15" />
        <span class="quiz-header-btn-text">Beranda</span>
    </x-button>

</div>