{{-- resources/views/partials/chatbot/chat-section.blade.php --}}
<section style="min-height:calc(100vh - 64px); background:#f5f4ff; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:32px 24px;">

    <div style="width:100%; max-width:720px; display:flex; flex-direction:column; gap:16px;">

        {{-- Page header --}}
        @include('partials.chatbot.chat-header')

        {{-- Chat box --}}
        <div x-data="chatbot()"
            x-init="init()"
            style="background:#fff; border-radius:24px; border:1px solid #e0e0f0; overflow:hidden; display:flex; flex-direction:column; height:620px;">

            {{-- Messages area --}}
            <div x-ref="messageArea"
                style="flex:1; overflow-y:auto; padding:24px; display:flex; flex-direction:column; gap:12px; scroll-behavior:smooth;">

                <template x-for="(msg, i) in messages" :key="i">
                    @include('partials.chatbot.message-bubble')
                </template>

                {{-- Typing indicator --}}
                <div x-show="typing" x-transition style="display:flex; justify-content:flex-start;">
                    <div style="display:flex; align-items:center; gap:8px; background:#f5f4ff; border:1px solid #e0e0f0; border-radius:18px 18px 18px 4px; padding:12px 16px;">
                        <x-icon name="layers" color="#6366f1" size="14" />
                        <div style="display:flex; gap:4px; align-items:center;">
                            <span style="width:6px; height:6px; border-radius:50%; background:#6366f1; animation:typingDot 1.2s infinite 0s;"></span>
                            <span style="width:6px; height:6px; border-radius:50%; background:#6366f1; animation:typingDot 1.2s infinite 0.2s;"></span>
                            <span style="width:6px; height:6px; border-radius:50%; background:#6366f1; animation:typingDot 1.2s infinite 0.4s;"></span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Suggestion chips --}}
            <div x-show="showSuggestions" x-transition
                style="padding:0 24px 12px; display:flex; gap:8px; flex-wrap:wrap;">
                <template x-for="chip in suggestions" :key="chip">
                    <button @click="sendChip(chip)"
                        style="font-size:12px; font-weight:500; padding:6px 14px; border-radius:99px; background:#eef2ff; color:#4f46e5; border:1px solid #c7d2fe; cursor:pointer; transition:background 0.15s, transform 0.1s;"
                        onmouseover="this.style.background='#e0e7ff'" onmouseout="this.style.background='#eef2ff'"
                        x-text="chip">
                    </button>
                </template>
            </div>

            {{-- Input area --}}
            @include('partials.chatbot.chat-input')

        </div>

    </div>

</section>