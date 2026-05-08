{{-- resources/views/partials/landing/result-preview.blade.php --}}

{{-- Decorative blobs --}}
<div style="position:absolute; top:-30px; right:-30px; width:200px; height:200px; border-radius:50%; background:radial-gradient(circle, #c7d2fe 0%, transparent 70%); opacity:0.5; pointer-events:none;"></div>
<div style="position:absolute; bottom:-20px; left:-20px; width:150px; height:150px; border-radius:50%; background:radial-gradient(circle, #ddd6fe 0%, transparent 70%); opacity:0.4; pointer-events:none;"></div>

<div x-data="resultPreview()" x-intersect="animate()"
    style="background:#fff; border-radius:24px; padding:28px; border:1px solid #e0e0f0; position:relative; z-index:1;">

    {{-- Card header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <p style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#9ca3af; margin:0 0 4px;">Hasil analisis kamu</p>
            <p style="font-size:16px; font-weight:600; color:#1e1b4b; margin:0;">Top rekomendasi jurusan</p>
        </div>
        <x-tag icon="check-circle" bg="#dcfce7" color="#166534">Selesai</x-tag>
    </div>

    {{-- Bar items --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        <template x-for="(item, i) in items" :key="i">
            <div>
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                    <div :style="`width:32px; height:32px; border-radius:10px; background:${item.bg}; flex-shrink:0; display:flex; align-items:center; justify-content:center;`">
                        <div :style="`color:${item.color}; display:flex;`">
                            <x-icon name="align-left" color="currentColor" size="15" stroke="2" />
                        </div>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="font-size:14px; font-weight:600;" x-text="item.label"></span>
                            <span style="font-size:14px; font-weight:600;" :style="`color:${item.color}`" x-text="item.pct + '%'"></span>
                        </div>
                        <p style="font-size:11px; color:#9ca3af; margin:2px 0 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="item.sub"></p>
                    </div>
                </div>
                <div style="height:7px; background:#f0f0f8; border-radius:99px; overflow:hidden;">
                    <div :style="`height:100%; border-radius:99px; background:${item.color}; width:${item.pct}%; animation:growBar 1.2s cubic-bezier(0.4,0,0.2,1) both; animation-delay:${i * 0.15}s`"></div>
                </div>
            </div>
        </template>
    </div>

    {{-- Card footer --}}
    <div style="margin-top:20px; padding-top:16px; border-top:1px solid #f0f0f8; display:flex; align-items:center; gap:8px;">
        <x-icon name="clock" color="#9ca3af" size="14" />
        <p style="font-size:12px; color:#9ca3af; margin:0;">Diperbarui berdasarkan 42 jawaban kamu</p>
    </div>

</div>