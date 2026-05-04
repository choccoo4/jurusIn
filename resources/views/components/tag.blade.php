@props([
    'color' => '#5b21b6',
    'bg'    => '#ede9fe',
    'icon'  => null,
])

<span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:99px; font-size:12px; font-weight:500; background:{{ $bg }}; color:{{ $color }};">
    @if($icon)
        <x-icon :name="$icon" :color="$color" size="13" />
    @endif
    {{ $slot }}
</span>
