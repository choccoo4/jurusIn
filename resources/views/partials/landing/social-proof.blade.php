{{-- resources/views/partials/landing/social-proof.blade.php --}}

{{-- Avatar stack + count --}}
<div style="display:flex; align-items:center; gap:6px;">
    <div style="display:flex;">
        @foreach(['R','F','A'] as $i => $letter)
            <div style="width:28px; height:28px; border-radius:50%; background:{{ ['#c7d2fe','#ddd6fe','#ede9fe'][$i] }}; border:2px solid #f5f4ff; {{ $i > 0 ? 'margin-left:-8px;' : '' }} display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:#4f46e5;">
                {{ $letter }}
            </div>
        @endforeach
    </div>
    <span style="font-size:13px; color:#9ca3af;">+2.4k siswa sudah mencoba</span>
</div>

{{-- Stars + rating --}}
<div style="display:flex; align-items:center; gap:5px;">
    @for($i = 0; $i < 5; $i++)
        <x-icon name="star" color="#f59e0b" size="13" />
    @endfor
    <span style="font-size:13px; color:#9ca3af; margin-left:4px;">4.9 rating</span>
</div>
