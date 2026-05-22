{{-- resources/views/partials/results/university-card.blade.php --}}

{{-- sementara ga pakai ini dulu, fokus rekomendasi jurusan saja --}}

<div style="background:#fff; border:1.5px solid #e0e0f0; border-radius:20px; padding:24px;">

    <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
        <div style="width:36px; height:36px; border-radius:12px; background:#dcfce7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <x-icon name="layers" color="#16a34a" size="18" />
        </div>
        <div>
            <h4 style="font-size:15px; font-weight:700; color:#1e1b4b; margin:0;">Universitas yang Direkomendasikan</h4>
            <p style="font-size:12px; color:#9ca3af; margin:0;">Berdasarkan jurusan terbaik untukmu</p>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:10px;">
        <template x-for="(uni, i) in universities" :key="i">
            <div style="display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:14px; background:#f9f9ff; border:1px solid #e8e0ff; transition:background 0.15s;"
                onmouseover="this.style.background='#eef2ff'" onmouseout="this.style.background='#f9f9ff'">
                <div style="width:28px; height:28px; border-radius:8px; background:#4f46e5; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:11px; font-weight:700; color:#fff;"
                    x-text="uni.abbr">
                </div>
                <div style="min-width:0;">
                    <p style="font-size:13px; font-weight:600; color:#1e1b4b; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="uni.name"></p>
                    <p style="font-size:11px; color:#9ca3af; margin:0;" x-text="uni.location"></p>
                </div>
            </div>
        </template>
    </div>

</div>