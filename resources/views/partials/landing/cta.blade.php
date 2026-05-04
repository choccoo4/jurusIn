{{-- resources/views/partials/landing/cta.blade.php --}}
<section style="padding:80px 24px; background:linear-gradient(135deg, #312e81 0%, #4f46e5 50%, #7c3aed 100%);">
    <div style="max-width:640px; margin:0 auto; text-align:center;">

        <div style="width:64px; height:64px; border-radius:20px; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; margin:0 auto 24px; position:relative;" class="pulse-ring">
            <x-icon name="zap" color="#fff" size="28" />
        </div>

        <h2 style="font-size:clamp(26px,4vw,40px); font-weight:700; color:#fff; margin:0 0 16px; letter-spacing:-0.5px; line-height:1.2;">
            Siap menemukan<br>jurusan yang tepat?
        </h2>
        <p style="font-size:16px; color:#c7d2fe; line-height:1.7; max-width:420px; margin:0 auto 36px;">
            Ribuan siswa sudah menemukan arah mereka. Sekarang giliran kamu — hanya butuh 5 menit.
        </p>

        <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
            <x-button href="{{ route('quiz.start') }}" variant="white">
                <x-icon name="zap" color="#4f46e5" size="16" />
                Mulai Tes Sekarang
            </x-button>
            <x-button href="mailto:{{ config('jurusin.contact.email') }}" variant="ghost-light">
                <x-icon name="message" color="#c7d2fe" size="16" />
                Hubungi Kami
            </x-button>
        </div>

    </div>
</section>
