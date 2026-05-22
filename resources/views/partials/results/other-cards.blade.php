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

                <div style="display:flex; align-items:center; gap:14px;">

                    {{-- Rank badge --}}
                    <div style="width:40px; height:40px; border-radius:12px; background:#eef2ff; display:flex; align-items:center; justify-content:center; font-size:15px; font-weight:700; color:#4f46e5; flex-shrink:0;"
                        x-text="'#' + item.rank">
                    </div>

                    {{-- Major info --}}
                    <div style="flex:1; min-width:0;">
                        <h3 style="font-size:15px; font-weight:700; color:#1e1b4b; margin:0 0 3px;" x-text="item.major"></h3>
                        <p style="font-size:12px; color:#9ca3af; margin:0 0 8px; line-height:1.5;" x-text="item.description || ''"></p>

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