<footer style="background:#1e1b4b; color:#a5b4fc; padding:56px 24px 24px;">
    <div style="max-width:1100px; margin:0 auto;">

        <div style="display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:40px; padding-bottom:40px; border-bottom:1px solid rgba(165,180,252,0.15);">

            {{-- Brand --}}
            <div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
                    <div style="width:32px; height:32px; border-radius:10px; background:#4f46e5; display:flex; align-items:center; justify-content:center;">
                        <x-icon name="layers" color="#fff" size="16" />
                    </div>
                    <span style="font-size:17px; font-weight:700; color:#fff;">
                        Jurus<span style="color:#818cf8;">In</span>
                    </span>
                </div>
                <p style="font-size:14px; line-height:1.75; max-width:260px; color:#818cf8; margin:0 0 20px;">
                    Membantu kamu menemukan jurusan yang sesuai dengan minat dan potensi diri melalui teknologi AI.
                </p>
                <div style="display:flex; gap:10px;">
                    <a href="{{ config('jurusin.social.instagram', '#') }}" class="footer-social-icon">
                        <x-icon name="instagram" color="currentColor" size="16" />
                    </a>
                    <a href="{{ config('jurusin.social.twitter', '#') }}" class="footer-social-icon">
                        <x-icon name="twitter" color="currentColor" size="16" />
                    </a>
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <p style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6366f1; margin:0 0 16px;">Navigasi</p>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;">
                    <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
                    <li><a href="{{ route('quiz.start') }}" class="footer-link">Mulai Tes</a></li>
                    <li><a href="#cara-kerja" class="footer-link">Cara Kerja</a></li>
                    <li><a href="#faq" class="footer-link">FAQ</a></li>
                </ul>
            </div>

            {{-- Jurusan --}}
            <div>
                <p style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6366f1; margin:0 0 16px;">Jurusan</p>
                {{-- 
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;">
                    @foreach($footerCategories ?? [] as $category)
                    <li><a href="{{ $category['url'] }}" class="footer-link">{{ $category['label'] }}</a></li>
                    @endforeach
                </ul>
                --}}

                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;">
                    <li><a href="#" class="footer-link">IPA &amp; Teknologi</a></li>
                    <li><a href="#" class="footer-link">IPS &amp; Bisnis</a></li>
                    <li><a href="#" class="footer-link">Seni &amp; Desain</a></li>
                    <li><a href="#" class="footer-link">Kesehatan</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <p style="font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#6366f1; margin:0 0 16px;">Kontak</p>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px;">
                    <li style="display:flex; align-items:center; gap:7px; font-size:14px; color:#818cf8;">
                        <x-icon name="mail" color="currentColor" size="14" />
                        {{ config('jurusin.contact.email', 'support@jurusin.id') }}
                    </li>
                    <li style="display:flex; align-items:center; gap:7px; font-size:14px; color:#818cf8;">
                        <x-icon name="instagram" color="currentColor" size="14" />
                        {{ config('jurusin.contact.instagram', '@jurusin.id') }}
                    </li>
                </ul>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div style="padding-top:24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <p style="font-size:13px; color:#4f4d7a; margin:0;">
                &copy; {{ date('Y') }} JurusIn. All rights reserved.
            </p>
            <div style="display:flex; gap:20px;">
                <a href="{{ route('privacy') }}" class="footer-bottom-link">Kebijakan Privasi</a>
                <a href="{{ route('terms') }}" class="footer-bottom-link">Syarat &amp; Ketentuan</a>
            </div>
        </div>

    </div>
</footer>