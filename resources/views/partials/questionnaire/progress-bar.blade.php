{{-- resources/views/partials/questionnaire/progress-bar.blade.php --}}

{{-- Step dots --}}
<div style="padding:24px 32px 0; display:flex; gap:6px;">
    @foreach($questions as $i => $q)
    <div
        style="flex:1; height:4px; border-radius:99px; transition:background 0.3s;"
        :style="{
            background: currentIndex > {{ $i }} 
                ? '#4f46e5' 
                : (currentIndex === {{ $i }} ? '#a5b4fc' : '#e0e0f0')
        }">
    </div>
    @endforeach
</div>