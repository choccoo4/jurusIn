{{-- resources/views/partials/chatbot/chat-input.blade.php --}}

{{-- Locked state --}}
<div x-show="locked" x-transition class="px-5 py-5 flex flex-col items-center gap-3">

    <p class="text-[13px] text-gray-500 text-center">
        Analisis selesai — rekomendasi jurusan lengkapmu siap dilihat
    </p>

    <x-button href="{{ route('results') }}" variant="primary">
        <x-icon name="star" color="#fff" size="16" />
        Lihat Hasil Rekomendasi
    </x-button>
</div>


{{-- Active input --}}
<div x-show="!locked" class="px-3 py-2">

    <div style="width:100%; display:flex; justify-content:center;">
        <div style="display:flex; align-items:flex-end; gap:16px; width:100%; max-width:660px;">
            {{-- Textarea multi-line --}}
            <textarea
                x-model="input"
                @keydown.enter.prevent="if(input.length <= 500) { if($event.shiftKey) { input += '\n'; } else { send(); } }"
                @input="
        if(input.length > 500) { 
            input = input.substring(0, 500); 
        }
        autoResize($el);
    "
                :disabled="typing || locked"
                placeholder="Ceritakan secara singkat dan fokus..."
                rows="1"
                style="flex:1; min-width:0; padding:12px 18px; font-size:14px; border-radius:14px; border:1px solid #e0e0f0; background:#fafafa; color:#1e1b4b; outline:none; transition:all 0.15s ease; resize:none; font-family:inherit; line-height:1.5; max-height:120px; scrollbar-width:none; -ms-overflow-style:none;"
                onfocus="this.style.borderColor='#6366f1'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.1)';"
                onblur="this.style.borderColor='#e0e0f0'; this.style.background='#fafafa'; this.style.boxShadow='none';"></textarea>

            {{-- Tombol send dengan spacing jelas --}}
            <button
                @click="send()"
                :disabled="input.trim() === '' || typing || _sending"
                style="flex-shrink:0; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:14px; border:none; transition:all 0.15s ease; disabled:background:#e0e0f0; disabled:cursor:not-allowed; background:#4f46e5; color:white; box-shadow:0 4px 12px rgba(79, 70, 229, 0.3);"
                onmouseover="if(!this.disabled){this.style.background='#4338ca'; this.style.transform='translateY(-1px)';}"
                onmouseout="if(!this.disabled){this.style.background='#4f46e5'; this.style.transform='translateY(0)';}"
                onmousedown="if(!this.disabled){this.style.transform='scale(0.95)';}">
                <x-icon name="send" color="#fff" size="18" />
            </button>
        </div>
    </div>

    {{-- Counter + warning --}}
    <div style="width:100%; max-width:660px; margin:6px auto 0; display:flex; justify-content:space-between; align-items:center; min-height:16px; padding:0 6px; box-sizing:border-box;">

        {{-- Warning kiri --}}
        <span
            x-show="input.length >= 480"
            x-transition.opacity
            style="font-size:10px; color:#ef4444; font-weight:500; line-height:1;">
            Maksimal 500 karakter
        </span>

        {{-- Spacer biar counter tetap kanan --}}
        <div style="flex:1;"></div>

        <span
            x-text="input.length + ' / 500'"
            style="font-size:16px; transform:scale(.72); transform-origin:right center; line-height:1; letter-spacing:0; white-space:nowrap; display:inline-block; opacity:.55;"
            :class="input.length >= 280 ? 'text-red-500' : 'text-gray-400'">
        </span>

    </div>
</div>