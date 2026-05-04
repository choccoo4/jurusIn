{{-- resources/views/partials/chatbot/message-bubble.blade.php --}}
<div :style="msg.sender === 'user' ? 'display:flex; justify-content:flex-end;' : 'display:flex; justify-content:flex-start;'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0">

    {{-- Bot avatar --}}
    <div x-show="msg.sender === 'bot'" style="width:30px; height:30px; border-radius:10px; background:#4f46e5; display:flex; align-items:center; justify-content:center; margin-right:8px; flex-shrink:0; align-self:flex-end; margin-bottom:2px;">
        <x-icon name="zap" color="#fff" size="14" />
    </div>

    <div style="max-width:75%; display:flex; flex-direction:column; gap:4px;"
        :style="msg.sender === 'user' ? 'align-items:flex-end;' : 'align-items:flex-start;'">

        {{-- Bubble --}}
        <div :style="msg.sender === 'user'
            ? 'background:#4f46e5; color:#fff; border-radius:18px 18px 4px 18px; padding:12px 16px; font-size:14px; line-height:1.6;'
            : 'background:#f5f4ff; color:#1e1b4b; border:1px solid #e0e0f0; border-radius:18px 18px 18px 4px; padding:12px 16px; font-size:14px; line-height:1.6;'">
            <p style="margin:0;" x-text="msg.text"></p>
        </div>

        {{-- Result cards (if any) --}}
        <template x-if="msg.results && msg.results.length">
            <div style="display:flex; flex-direction:column; gap:8px; width:100%; margin-top:4px;">
                <template x-for="(result, ri) in msg.results" :key="ri">
                    <div style="background:#fff; border:1px solid #e0e0f0; border-radius:16px; padding:14px 16px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:13px; font-weight:600; color:#1e1b4b;" x-text="result.major"></span>
                            <span style="font-size:12px; font-weight:700; color:#4f46e5;" x-text="result.pct + '%'"></span>
                        </div>
                        <div style="height:6px; background:#f0f0f8; border-radius:99px; overflow:hidden;">
                            <div :style="`height:100%; border-radius:99px; background:#4f46e5; width:${result.pct}%; animation:growBar 1s cubic-bezier(0.4,0,0.2,1) both; animation-delay:${ri * 0.1}s`"></div>
                        </div>
                        <p style="font-size:11px; color:#9ca3af; margin:6px 0 0;" x-text="result.unis"></p>
                    </div>
                </template>
            </div>
        </template>

        {{-- Timestamp --}}
        <span style="font-size:11px; color:#9ca3af;" x-text="msg.time"></span>

    </div>

    {{-- User avatar --}}
    <div x-show="msg.sender === 'user'" style="width:30px; height:30px; border-radius:10px; background:#e0e7ff; display:flex; align-items:center; justify-content:center; margin-left:8px; flex-shrink:0; align-self:flex-end; margin-bottom:2px;">
        <x-icon name="user" color="#4f46e5" size="14" />
    </div>

</div>