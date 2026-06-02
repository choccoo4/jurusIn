{{-- resources/views/partials/chatbot/chat-header.blade.php --}}
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">

    <div style="flex:1; min-width:0;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
            <div style="width:clamp(32px, 4vw, 36px); height:clamp(32px, 4vw, 36px); border-radius:12px; background:#4f46e5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <x-icon name="zap" color="#fff" size="18" />
            </div>
            <h1 style="font-size:clamp(18px, 3vw, 22px); font-weight:700; color:#1e1b4b; letter-spacing:-0.3px; margin:0;">
                Rekomendasi Jurusan
            </h1>
        </div>
        <p style="font-size:clamp(12px, 1.5vw, 14px); color:#9ca3af; margin:0 0 0 46px;"
            class="chat-header-desc">
            Ceritakan minatmu dan AI akan menemukan jurusan yang cocok
        </p>
    </div>

    <x-button href="{{ route('home') }}" variant="outline" size="sm" class="chat-header-btn">
        <x-icon name="chevron-left" color="#4f46e5" size="15" />
        <span class="chat-header-btn-text">Kembali</span>
    </x-button>

</div>