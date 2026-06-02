{{-- resources/views/partials/landing/how-it-works.blade.php --}}
<section id="cara-kerja" style="padding:clamp(40px, 8vw, 80px) 20px; background:#fff;">
    <div style="max-width:1100px; margin:0 auto;">

        <div style="text-align:center; margin-bottom:clamp(32px, 6vw, 60px);">
            <x-tag icon="layers" style="margin:0 auto 16px; display:inline-flex;">Cara kerja</x-tag>
            <h2 style="font-size:clamp(24px, 4vw, 36px); font-weight:700; color:#1e1b4b; margin:12px 0; letter-spacing:-0.5px;">
                Sederhana, cepat, akurat
            </h2>
            <p style="font-size:clamp(14px, 1.5vw, 16px); color:#6b7280; max-width:480px; margin:0 auto; line-height:1.7;">
                Tiga langkah untuk menemukan jurusan yang benar-benar cocok dengan dirimu.
            </p>
        </div>

        {{-- Desktop: Grid 3 kolom --}}
        <div class="steps-grid-desktop">
            @foreach($steps as $step)
            @include('partials.landing.step-card', ['step' => $step])
            @endforeach
        </div>

        {{-- Mobile: Horizontal scroll --}}
        <div class="steps-scroll">
            @foreach($steps as $step)
            <div class="steps-scroll-card">
                @include('partials.landing.step-card', ['step' => $step])
            </div>
            @endforeach
        </div>

    </div>
</section>