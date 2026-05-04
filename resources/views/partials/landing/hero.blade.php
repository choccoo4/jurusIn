{{-- resources/views/partials/landing/hero.blade.php --}}
<section style="min-height:calc(100vh - 64px); display:flex; align-items:center; padding:60px 24px 80px;">
    <div style="max-width:1100px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; width:100%;">

        {{-- Left: Copy --}}
        <div>
            <x-tag icon="clock" class="mb-5">Rekomendasi berbasis AI</x-tag>

            <h1 style="font-size:clamp(32px, 4vw, 52px); font-weight:700; line-height:1.18; letter-spacing:-1px; color:#1e1b4b; margin:0 0 20px;">
                Temukan jurusan<br>
                <span style="background:linear-gradient(135deg, #4f46e5, #8b5cf6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">yang cocok</span>
                <br>untukmu
            </h1>

            <p style="font-size:16px; color:#6b7280; line-height:1.75; margin:0 0 36px; max-width:420px;">
                JurusIn menganalisis minat dan cara berpikirmu melalui kuesioner singkat, lalu memberikan rekomendasi jurusan berbasis analisis AI yang personal.
            </p>

            {{-- CTA Buttons --}}
            <div x-data="heroCta()" style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                <x-button variant="primary" @click="start()">
                    <x-icon name="zap" color="#fff" size="16" />
                    <span x-text="loading ? 'Menyiapkan...' : 'Mulai Sekarang'"></span>
                </x-button>
                <x-button variant="outline" @click="scrollTo('cara-kerja')">
                    Pelajari cara kerjanya
                </x-button>
            </div>

            {{-- Social proof --}}
            <div style="display:flex; align-items:center; gap:20px; margin-top:36px; flex-wrap:wrap;">
                @include('partials.landing.social-proof')
            </div>
        </div>

        {{-- Right: Preview Card --}}
        <div class="float" style="position:relative;">
            @include('partials.landing.result-preview')
        </div>

    </div>
</section>
