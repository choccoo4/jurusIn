{{-- resources/views/partials/results/top-card.blade.php --}}
<div style="background:linear-gradient(135deg, #312e81 0%, #4f46e5 60%, #7c3aed 100%); border-radius:24px; padding:32px; position:relative; overflow:hidden;">

    {{-- Decorative blobs --}}
    <div style="position:absolute; top:-40px; right:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.06); pointer-events:none;"></div>
    <div style="position:absolute; bottom:-20px; left:60px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.04); pointer-events:none;"></div>

    <div style="position:relative; z-index:1;">

        {{-- Label --}}
        <x-tag icon="star" bg="rgba(255,255,255,0.15)" color="#fff" style="margin-bottom:20px;">
            #1 Rekomendasi Terbaik
        </x-tag>

        {{-- Score ring + name --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:20px;">

            <div>
                <h2 style="font-size:28px; font-weight:700; color:#fff; margin:0 0 8px; letter-spacing:-0.5px;" x-text="top.name"></h2>
                <p style="font-size:14px; color:#c7d2fe; line-height:1.7; margin:0; max-width:420px;" x-text="top.description"></p>
            </div>

            {{-- Score circle --}}
            <div style="flex-shrink:0; width:80px; height:80px; border-radius:50%; border:3px solid rgba(255,255,255,0.3); background:rgba(255,255,255,0.1); display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <span style="font-size:22px; font-weight:700; color:#fff; line-height:1;" x-text="top.score + '%'"></span>
                <span style="font-size:10px; color:#c7d2fe; margin-top:2px;">cocok</span>
            </div>

        </div>

        {{-- Bar --}}
        <div style="height:8px; background:rgba(255,255,255,0.15); border-radius:99px; overflow:hidden;">
            <div :style="`height:100%; border-radius:99px; background:#fff; width:${top.score}%; animation:growBar 1.4s cubic-bezier(0.4,0,0.2,1) both;`"></div>
        </div>

        {{-- Meta tags --}}
        <div style="display:flex; gap:8px; margin-top:20px; flex-wrap:wrap;">
            <template x-for="tag in (top.tags || [])" :key="tag">
                <span style="font-size:12px; font-weight:500; padding:4px 12px; border-radius:99px; background:rgba(255,255,255,0.12); color:#e0e7ff;" x-text="tag"></span>
            </template>
        </div>

    </div>
</div>
