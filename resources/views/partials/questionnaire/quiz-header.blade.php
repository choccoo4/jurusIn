{{-- resources/views/partials/questionnaire/quiz-header.blade.php --}}
<div style="display:flex; align-items:center; justify-content:space-between;">

    <div>
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
            <div style="width:36px; height:36px; border-radius:12px; background:#4f46e5; display:flex; align-items:center; justify-content:center;">
                <x-icon name="file-text" color="#fff" size="18" />
            </div>
            <h1 style="font-size:22px; font-weight:700; color:#1e1b4b; letter-spacing:-0.3px; margin:0;">
                Kuesioner Minat
            </h1>
        </div>
        <p style="font-size:14px; color:#9ca3af; margin:0 0 0 46px;">
            Jawab dengan jujur untuk hasil rekomendasi yang paling akurat
        </p>
    </div>

    <x-button href="{{ route('home') }}" variant="outline" size="sm">
        <x-icon name="chevron-left" color="#4f46e5" size="15" />
        Beranda
    </x-button>

</div>
