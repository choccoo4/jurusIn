{{-- resources/views/partials/results/top-card.blade.php --}}
<div style="background:linear-gradient(135deg, #312e81 0%, #4f46e5 60%, #7c3aed 100%); border-radius:24px; padding:32px; position:relative; overflow:hidden;">

    {{-- Decorative blobs --}}
    <div style="position:absolute; top:-40px; right:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.06); pointer-events:none;"></div>
    <div style="position:absolute; bottom:-20px; left:60px; width:120px; height:120px; border-radius:50%; background:rgba(255,255,255,0.04); pointer-events:none;"></div>

    <div style="position:relative; z-index:1;">

        {{-- Rank badge --}}
        <div style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:99px; background:rgba(255,255,255,0.15); margin-bottom:20px;">
            <x-icon name="star" color="#fbbf24" size="14" />
            <span style="font-size:12px; font-weight:600; color:#fff;">#<span x-text="top.rank"></span> Rekomendasi Terbaik</span>
        </div>

        {{-- Major name + description --}}
        <div style="margin-bottom:20px;">
            <h2 style="font-size:28px; font-weight:700; color:#fff; margin:0 0 8px; letter-spacing:-0.5px;" x-text="top.major"></h2>
            <p style="font-size:14px; color:#c7d2fe; line-height:1.7; margin:0; max-width:480px;" x-text="top.description"></p>
        </div>

        {{-- Meta tags --}}
        <div style="display:flex; gap:8px; margin-top:20px; flex-wrap:wrap;">
            <template x-for="tag in (top.tags || [])" :key="tag">
                <span style="font-size:12px; font-weight:500; padding:4px 12px; border-radius:99px; background:rgba(255,255,255,0.12); color:#e0e7ff;" x-text="tag"></span>
            </template>
        </div>

    </div>
</div>