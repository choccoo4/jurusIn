{{-- resources/views/partials/chatbot/chat-section.blade.php --}}
<section style="min-height:calc(100vh - 64px); background:#f5f4ff; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; padding:32px 20px;">

    <div style="width:100%; max-width:720px; display:flex; flex-direction:column; gap:16px;">

        {{-- Page header --}}
        @include('partials.chatbot.chat-header')

        {{-- Chat box --}}
        <div x-data="chatbot()"
            style="background:#fff; border-radius:24px; border:1px solid #e0e0f0; overflow:hidden; display:flex; flex-direction:column; height:calc(100vh - 200px); min-height:500px;">

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

            {{-- Suggestion chips - Bungkus luar untuk masking --}}
            <div x-show="showSuggestions" x-transition style="position: relative;padding: 4px 0 12px 0;">
                
                <p style="font-size:11px; color:#9ca3af; margin:0 24px 10px; line-height:1.5;">
                    💡 Ceritakan secara singkat dan fokus pada pengalaman yang paling menggambarkan diri kamu.
                </p>

                {{-- Gradient fade kanan --}}
                <div style="position: absolute;right: 0;top: 0;bottom: 0;width: 40px;background: linear-gradient(to right, transparent, #fff);z-index: 2;pointer-events: none;"></div>

                {{-- Scroll container --}}
                <div style="
                    overflow-x: auto;
                    overflow-y: hidden;
                    white-space: nowrap;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-width: none;
                    -ms-overflow-style: none;
                    padding: 0 24px;
                ">
                    <div style="
                        display: inline-flex;
                        gap: 8px;
                    ">
                        <template x-for="chip in currentSuggestions" :key="chip">
                            <button @click="sendChip(chip)"
                                style="
                                    flex-shrink: 0;
                                    font-size: 12px;
                                    font-weight: 500;
                                    padding: 8px 16px;
                                    border-radius: 20px;
                                    background: #eef2ff;
                                    color: #4f46e5;
                                    border: 1px solid #c7d2fe;
                                    cursor: pointer;
                                    transition: all 0.15s ease;
                                    white-space: nowrap;
                                "
                                onmouseover="this.style.background='#ddd6fe'; this.style.transform='translateY(-1px)';"
                                onmouseout="this.style.background='#eef2ff'; this.style.transform='translateY(0)';"
                                x-text="chip">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Input area --}}
            <div style="background:#fff; border-top:1px solid #f0f0f8; padding:8px 12px;">
                @include('partials.chatbot.chat-input')
            </div>
        </div>

    </div>

</section>