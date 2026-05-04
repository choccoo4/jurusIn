{{-- resources/views/partials/chatbot/chat-input.blade.php --}}
<div style="border-top:1px solid #f0f0f8; padding:16px 20px; display:flex; align-items:center; gap:10px; background:#fff;">

    <input
        type="text"
        x-model="input"
        @keydown.enter="send()"
        :disabled="typing"
        placeholder="Ceritakan minat atau hobimu..."
        style="flex:1; padding:11px 16px; border-radius:14px; border:1.5px solid #e0e0f0; font-size:14px; font-family:'Inter',sans-serif; color:#1e1b4b; outline:none; transition:border-color 0.15s; background:#fafafa;"
        onfocus="this.style.borderColor='#6366f1'; this.style.background='#fff'"
        onblur="this.style.borderColor='#e0e0f0'; this.style.background='#fafafa'" />

    <button @click="send()"
        :disabled="input.trim() === '' || typing"
        :style="(input.trim() === '' || typing)
            ? 'width:44px; height:44px; border-radius:14px; background:#e0e0f0; border:none; display:flex; align-items:center; justify-content:center; cursor:not-allowed;'
            : 'width:44px; height:44px; border-radius:14px; background:#4f46e5; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 12px rgba(99,102,241,0.3); transition:background 0.15s, transform 0.1s;'"
        onmouseover="if(!this.disabled) this.style.background='#4338ca'"
        onmouseout="if(!this.disabled) this.style.background='#4f46e5'">
        <x-icon name="send" color="#fff" size="18" />
    </button>

</div>