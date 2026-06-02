{{-- resources/views/partials/landing/result-preview.blade.php --}}

{{-- Decorative blobs — DI BELAKANG card --}}
<div style="position:absolute; top:-30px; right:-30px; width:150px; height:150px; border-radius:50%; background:radial-gradient(circle, #c7d2fe 0%, transparent 70%); opacity:0.5; pointer-events:none; z-index:-1;" class="blob-top"></div>
<div style="position:absolute; bottom:-20px; left:-20px; width:120px; height:120px; border-radius:50%; background:radial-gradient(circle, #ddd6fe 0%, transparent 70%); opacity:0.4; pointer-events:none; z-index:-1;" class="blob-bottom"></div>
<div x-data="resultPreview()" x-intersect="animate()"
    style="background:#fff; border-radius:24px; padding:clamp(20px, 3vw, 28px); border:1px solid #e0e0f0; position:relative;">

    {{-- Card header --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        <div>
            <p style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#9ca3af; margin:0 0 4px;">Contoh Hasil Analisis</p>
            <p style="font-size:clamp(14px, 2vw, 16px); font-weight:600; color:#1e1b4b; margin:0;">Top rekomendasi jurusan</p>
        </div>
        <x-tag icon="check-circle" bg="#dcfce7" color="#166534">Contoh</x-tag>
    </div>

    {{-- Rank items --}}
    <div style="display:flex; flex-direction:column; gap:10px;">
        <template x-for="(item, i) in items" :key="i">
            <div style="display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:14px; background:#fafbff; border:1px solid #f0f0f8; transition:all 0.2s ease;"
                onmouseover="this.style.borderColor='#c7d2fe'; this.style.boxShadow='0 2px 8px rgba(79,70,229,0.06)';"
                onmouseout="this.style.borderColor='#f0f0f8'; this.style.boxShadow='none';">

                {{-- Rank badge --}}
                <div :style="`width:32px; height:32px; border-radius:10px; background:${item.bg}; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:${item.color};`"
                    x-text="'#' + item.rank">
                </div>

                {{-- Info --}}
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        <span style="font-size:clamp(13px, 1.5vw, 14px); font-weight:600; color:#1e1b4b;" x-text="item.label"></span>
                    </div>
                    <p style="font-size:11px; color:#9ca3af; margin:2px 0 0; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical;"
                        x-text="item.description"></p>
                </div>

                {{-- Tags — hide di mobile kecil --}}
                <div style="display:none; sm:display:flex; gap:4px; flex-shrink:0;" class="preview-tags">
                    <template x-for="tag in (item.tags || [])" :key="tag">
                        <span :style="`font-size:10px; font-weight:500; padding:2px 8px; border-radius:99px; background:${item.bg}; color:${item.color}; white-space:nowrap;`" x-text="tag"></span>
                    </template>
                </div>

            </div>
        </template>
    </div>

    {{-- Card footer --}}
    <div style="margin-top:20px; padding-top:14px; border-top:1px solid #f0f0f8; display:flex; align-items:center; gap:8px;">
        <x-icon name="clock" color="#9ca3af" size="14" />
        <p style="font-size:11px; color:#9ca3af; margin:0;">Ini hanya contoh — hasil asli berdasarkan jawaban kamu</p>
    </div>

</div>