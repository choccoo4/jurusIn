<header
    x-data="navbar()"
    @scroll.window="onScroll()"
    style="position:sticky; top:0; z-index:50; backdrop-filter:blur(12px); border-bottom:1px solid #e0e0f0; transition: background 0.3s, box-shadow 0.3s;"
    :class="scrolled ? 'bg-white shadow-md' : 'bg-purple-50/70'">
    
    <div style="max-width:1100px; margin:0 auto; padding:0 20px; height:64px; display:flex; align-items:center; justify-content:space-between;">

        {{-- Logo --}}
        <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:8px; text-decoration:none; flex-shrink:0;">
            <div style="width:32px; height:32px; border-radius:10px; background:#4f46e5; display:flex; align-items:center; justify-content:center;">
                <x-icon name="layers" color="#fff" size="18" />
            </div>
            <span style="font-size:18px; font-weight:700; color:#1e1b4b; letter-spacing:-0.3px;">
                Jurus<span style="color:#6366f1;">In</span>
            </span>
        </a>

        {{-- Desktop Nav --}}
        <nav style="display:none; md:display:flex; align-items:center; gap:28px;" class="desktop-nav">
            <a href="#cara-kerja" style="font-size:14px; color:#6b7280; font-weight:500; text-decoration:none; transition:color 0.15s;"
                onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#6b7280'">
                Cara Kerja
            </a>
            <a href="#faq" style="font-size:14px; color:#6b7280; font-weight:500; text-decoration:none; transition:color 0.15s;"
                onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#6b7280'">
                FAQ
            </a>
            <x-button href="{{ route('questionnaire') }}" variant="primary" size="sm" :icon="true">
                <x-icon name="chevron-right" color="#fff" size="15" />
                Mulai Tes
            </x-button>
        </nav>

        {{-- Hamburger Button (Mobile) --}}
        <button @click="open = !open"
            style="display:flex; md:display:none; align-items:center; justify-content:center; width:40px; height:40px; border-radius:10px; border:none; background:transparent; cursor:pointer; z-index:9999;"
            class="hamburger-btn">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1e1b4b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                {{-- Garis atas → rotasi jadi \ --}}
                <path d="M4 6h16"
                    :style="open ? 'transform:rotate(45deg) translate(3px,3px); transform-origin:center;' : ''"
                    style="transition:transform 0.3s;" />

                {{-- Garis tengah → hilang --}}
                <path d="M4 12h16"
                    :style="open ? 'opacity:0' : ''"
                    style="transition:opacity 0.3s;" />

                {{-- Garis bawah → rotasi jadi / --}}
                <path d="M4 18h16"
                    :style="open ? 'transform:rotate(-45deg) translate(3px,-3px); transform-origin:center;' : ''"
                    style="transition:transform 0.3s;" />
            </svg>
        </button>

    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        style="position:absolute; top:64px; left:0; right:0; background:#fff; border-bottom:1px solid #e0e0f0; padding:16px 20px; display:flex; flex-direction:column; gap:8px; box-shadow:0 8px 24px rgba(0,0,0,0.08);z-index:9998;"
        class="mobile-menu">

        <a href="#cara-kerja" @click="open=false"
            style="display:block; padding:12px 16px; border-radius:12px; font-size:15px; font-weight:500; color:#1e1b4b; text-decoration:none; transition:background 0.15s;"
            onmouseover="this.style.background='#f5f4ff'" onmouseout="this.style.background='transparent'">
            Cara Kerja
        </a>
        <a href="#faq" @click="open=false"
            style="display:block; padding:12px 16px; border-radius:12px; font-size:15px; font-weight:500; color:#1e1b4b; text-decoration:none; transition:background 0.15s;"
            onmouseover="this.style.background='#f5f4ff'" onmouseout="this.style.background='transparent'">
            FAQ
        </a>
        <a href="{{ route('questionnaire') }}"
            style="display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; border-radius:14px; background:#4f46e5; color:#fff; font-size:15px; font-weight:600; text-decoration:none; margin-top:4px;">
            <x-icon name="chevron-right" color="#fff" size="15" />
            Mulai Tes
        </a>
    </div>
</header>