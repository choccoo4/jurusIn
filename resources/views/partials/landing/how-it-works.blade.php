{{-- resources/views/partials/landing/how-it-works.blade.php --}}
<section id="cara-kerja" style="padding:80px 24px; background:#fff;">
    <div style="max-width:1100px; margin:0 auto;">

        <div style="text-align:center; margin-bottom:60px;">
            <x-tag icon="layers" style="margin:0 auto 16px; display:inline-flex;">Cara kerja</x-tag>
            <h2 style="font-size:36px; font-weight:700; color:#1e1b4b; margin:12px 0; letter-spacing:-0.5px;">Sederhana, cepat, akurat</h2>
            <p style="font-size:16px; color:#6b7280; max-width:480px; margin:0 auto; line-height:1.7;">
                Tiga langkah untuk menemukan jurusan yang benar-benar cocok dengan dirimu.
            </p>
        </div>

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
            @foreach($steps as $step)
                @include('partials.landing.step-card', ['step' => $step])
            @endforeach
        </div>

    </div>
</section>
