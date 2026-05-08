{{-- resources/views/partials/results/other-cards.blade.php --}}
<div>
    <p style="font-size:13px; font-weight:600; color:#9ca3af; letter-spacing:0.5px; text-transform:uppercase; margin:0 0 12px;">
        Jurusan lainnya yang cocok
    </p>

    <div style="display:flex; flex-direction:column; gap:10px;">
        <template x-for="(item, i) in others" :key="i">

            <div x-transition
                 style="background:#fff; border:1.5px solid #e0e0f0; border-radius:18px; padding:20px; transition:box-shadow 0.2s, border-color 0.2s; cursor:default;"
                 onmouseover="this.style.borderColor='#a5b4fc'; this.style.boxShadow='0 4px 20px rgba(99,102,241,0.08)'"
                 onmouseout="this.style.borderColor='#e0e0f0'; this.style.boxShadow='none'">

                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; gap:12px;">

                    {{-- Rank badge + name --}}
                    <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                        <div style="width:32px; height:32px; border-radius:10px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#4f46e5; flex-shrink:0;"
                             x-text="'#' + (i + 2)">
                        </div>
                        <div style="min-width:0;">
                            <h3 style="font-size:15px; font-weight:700; color:#1e1b4b; margin:0 0 2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="item.name"></h3>
                            <p style="font-size:12px; color:#9ca3af; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="item.description"></p>
                        </div>
                    </div>

                    {{-- Score badge --}}
                    <div style="flex-shrink:0; display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                        <span style="font-size:16px; font-weight:700; color:#4f46e5;" x-text="item.score + '%'"></span>
                    </div>

                </div>

                {{-- Bar --}}
                <div style="height:6px; background:#f0f0f8; border-radius:99px; overflow:hidden;">
                    <div :style="`height:100%; border-radius:99px; background:${item.color}; width:${item.score}%; animation:growBar 1.2s cubic-bezier(0.4,0,0.2,1) both; animation-delay:${i * 0.1 + 0.3}s`"></div>
                </div>

                {{-- Tags --}}
                <div style="display:flex; gap:6px; margin-top:12px; flex-wrap:wrap;">
                    <template x-for="tag in (item.tags || [])" :key="tag">
                        <span style="font-size:11px; font-weight:500; padding:3px 10px; border-radius:99px; background:#f5f4ff; color:#6366f1;" x-text="tag"></span>
                    </template>
                </div>

            </div>

        </template>
    </div>
</div>
