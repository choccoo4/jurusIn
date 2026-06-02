{{-- resources/views/partials/landing/step-card.blade.php --}}
<div class="step-card" style="background:{{ $step['card_bg'] }}; border:1px solid {{ $step['card_border'] }}; border-radius:20px; padding:clamp(20px, 3vw, 28px); display:flex; flex-direction:column; height:100%;">

    <div style="width:clamp(38px, 4vw, 44px); height:clamp(38px, 4vw, 44px); border-radius:14px; background:{{ $step['icon_bg'] }}; display:flex; align-items:center; justify-content:center; margin-bottom:20px; flex-shrink:0;">
        <x-icon :name="$step['icon']" color="#fff" size="20" />
    </div>

    <div style="display:inline-block; font-size:11px; font-weight:700; color:{{ $step['accent'] }}; background:{{ $step['badge_bg'] }}; border-radius:6px; padding:2px 8px; margin-bottom:10px; width:fit-content;">
        {{ $step['badge'] }}
    </div>

    <h3 style="font-size:clamp(16px, 2vw, 18px); font-weight:700; color:#1e1b4b; margin:0 0 10px;">
        {{ $step['title'] }}
    </h3>
    <p style="font-size:clamp(13px, 1.3vw, 14px); color:#6b7280; line-height:1.7; margin:0; flex:1;">
        {{ $step['description'] }}
    </p>

    <div style="margin-top:20px; display:flex; align-items:center; gap:6px; font-size:13px; color:{{ $step['accent'] }}; font-weight:500;">
        <x-icon :name="$step['meta_icon']" :color="$step['accent']" size="14" />
        {{ $step['meta'] }}
    </div>

</div>