<header
    x-data="navbar()"
    @scroll.window="onScroll()"
    :style="scrolled ? 'background:rgba(255,255,255,0.92); box-shadow:0 1px 12px rgba(0,0,0,0.07);' : 'background:rgba(245,244,255,0.7);'"
    style="position:sticky; top:0; z-index:50; backdrop-filter:blur(12px); border-bottom:1px solid #e0e0f0; transition: background 0.3s, box-shadow 0.3s;">

    <div style="max-width:1100px; margin:0 auto; padding:0 24px; height:64px; display:flex; align-items:center; justify-content:space-between;">

        {{-- Logo --}}
        <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:8px; text-decoration:none;">
            <div style="width:32px; height:32px; border-radius:10px; background:#4f46e5; display:flex; align-items:center; justify-content:center;">
                <x-icon name="layers" color="#fff" size="18" />
            </div>
            <span style="font-size:18px; font-weight:700; color:#1e1b4b; letter-spacing:-0.3px;">
                Jurus<span style="color:#6366f1;">In</span>
            </span>
        </a>

        {{-- Nav Links --}}
        <nav style="display:flex; align-items:center; gap:28px;">
            <a href="#cara-kerja" style="font-size:14px; color:#6b7280; font-weight:500; text-decoration:none; transition:color 0.15s;"
               onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#6b7280'">
                Cara Kerja
            </a>
            <a href="#faq" style="font-size:14px; color:#6b7280; font-weight:500; text-decoration:none; transition:color 0.15s;"
               onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#6b7280'">
                FAQ
            </a>
            <x-button href="{{ route('quiz.start') }}" variant="primary" size="sm" :icon="true">
                <x-icon name="chevron-right" color="#fff" size="15" />
                Mulai Tes
            </x-button>
        </nav>

    </div>
</header>
