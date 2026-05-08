{{-- resources/views/partials/results/insight-card.blade.php --}}
<div style="background:#fff; border:1.5px solid #e0e0f0; border-radius:20px; padding:24px;">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
        <div style="width:36px; height:36px; border-radius:12px; background:#ede9fe; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <x-icon name="zap" color="#7c3aed" size="18" />
        </div>
        <div>
            <h4 style="font-size:15px; font-weight:700; color:#1e1b4b; margin:0;">Profil Kepribadianmu</h4>
            <p style="font-size:12px; color:#9ca3af; margin:0;">Analisis berdasarkan jawaban kamu</p>
        </div>
    </div>

    <p style="font-size:14px; color:#374151; line-height:1.8; margin:0 0 20px;" x-text="insight"></p>

    {{-- Trait bars --}}
    <div style="display:flex; flex-direction:column; gap:12px;">
        <template x-for="trait in traits" :key="trait.label">
            <div>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                    <div style="display:flex; align-items:center; gap:7px;">
                        <x-icon name="check-circle" color="#6366f1" size="13" />
                        <span style="font-size:13px; font-weight:500; color:#374151;" x-text="trait.label"></span>
                    </div>
                    <span style="font-size:12px; color:#6366f1; font-weight:600;" x-text="trait.val + '%'"></span>
                </div>
                <div style="height:5px; background:#f0f0f8; border-radius:99px; overflow:hidden;">
                    <div :style="`height:100%; border-radius:99px; background:linear-gradient(90deg, #6366f1, #8b5cf6); width:${trait.val}%; animation:growBar 1s cubic-bezier(0.4,0,0.2,1) both; animation-delay:0.2s`"></div>
                </div>
            </div>
        </template>
    </div>

</div>
