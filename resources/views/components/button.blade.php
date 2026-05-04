@props([
    'variant' => 'primary',   // primary | outline | ghost-light | white
    'size'    => 'md',        // sm | md | lg
    'href'    => null,
    'type'    => 'button',
])

@php
    $tag = $href ? 'a' : 'button';

    $baseStyle = "display:inline-flex; align-items:center; gap:8px; font-weight:500; border-radius:14px; cursor:pointer; transition:background 0.18s, transform 0.15s, box-shadow 0.18s; border:none; text-decoration:none;";

    $sizeStyle = match($size) {
        'sm' => 'padding:9px 20px; font-size:14px;',
        'lg' => 'padding:15px 32px; font-size:16px;',
        default => 'padding:12px 24px; font-size:15px;',
    };

    $variantStyle = match($variant) {
        'outline'     => 'background:transparent; color:#4f46e5; border:1.5px solid #6366f1;',
        'ghost-light' => 'background:transparent; color:#c7d2fe; border:1.5px solid rgba(199,210,254,0.4);',
        'white'       => 'background:#fff; color:#4f46e5; box-shadow:0 4px 16px rgba(0,0,0,0.1);',
        default       => 'background:#4f46e5; color:#fff; box-shadow:0 4px 16px rgba(99,102,241,0.25);',
    };

    $hoverJs = match($variant) {
        'outline'     => "this.style.background='#eef2ff'; this.style.transform='translateY(-1px)'",
        'ghost-light' => "this.style.background='rgba(255,255,255,0.08)'",
        'white'       => "this.style.boxShadow='0 8px 28px rgba(0,0,0,0.15)'; this.style.transform='translateY(-1px)'",
        default       => "this.style.background='#4338ca'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 8px 24px rgba(99,102,241,0.3)'",
    };

    $outHoverJs = match($variant) {
        'outline'     => "this.style.background='transparent'; this.style.transform='none'",
        'ghost-light' => "this.style.background='transparent'",
        'white'       => "this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'; this.style.transform='none'",
        default       => "this.style.background='#4f46e5'; this.style.transform='none'; this.style.boxShadow='0 4px 16px rgba(99,102,241,0.25)'",
    };
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    style="{{ $baseStyle }} {{ $sizeStyle }} {{ $variantStyle }}"
    onmouseover="{{ $hoverJs }}"
    onmouseout="{{ $outHoverJs }}"
    {{ $attributes }}
>
    {{ $slot }}
</{{ $tag }}>
