{{-- resources/views/partials/questionnaire/option-list.blade.php --}}
<div style="display:flex; flex-direction:column; gap:12px;">

    {{-- Label skala --}}
    <div style="display:flex; justify-content:space-between; padding:0 4px;">
        <span style="font-size:12px; color:#9ca3af; font-weight:500;">1 — Sangat Tidak Setuju</span>
        <span style="font-size:12px; color:#9ca3af; font-weight:500;">5 — Sangat Setuju</span>
    </div>

    {{-- Horizontal radio pills --}}
    <div style="display:flex; gap:8px; justify-content:center;">
        <template x-for="(scaleItem, si) in scale" :key="si">
            <button
                @click="selectOption(scaleItem.value)"
                class="opt-pill"
                style="
                    padding: 10px 20px;
                    flex-shrink: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 999px;
                    border: 2px solid #e5e7eb;
                    background: #ffffff;
                    cursor: pointer;
                    transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
                    font-size: 18px;
                    font-weight: 700;
                    color: #6b7280;
                    line-height: 1;
                "
                :style="answers[currentIndex] === scaleItem.value
                    ? 'border-color:#4f46e5; background:#4f46e5; color:#ffffff; box-shadow:0 4px 12px rgba(79,70,229,0.25); transform:scale(1.05);'
                    : ''"
                @mouseover="if(answers[currentIndex] !== scaleItem.value) { $el.style.borderColor='#c4b5fd'; $el.style.background='#faf9ff'; $el.style.color='#4f46e5'; $el.style.transform='scale(1.05)'; }"
                @mouseout="if(answers[currentIndex] !== scaleItem.value) { $el.style.borderColor='#e5e7eb'; $el.style.background='#ffffff'; $el.style.color='#6b7280'; $el.style.transform='scale(1)'; }"
                x-text="scaleItem.value">
            </button>
        </template>
    </div>
</div>