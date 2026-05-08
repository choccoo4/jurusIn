{{-- resources/views/partials/questionnaire/option-list.blade.php --}}
<div style="display:flex; flex-direction:column; gap:8px;">
    <template x-for="(option, oi) in currentQuestion.options" :key="oi">

        <button
            @click="selectOption(option)"
            class="opt"
            :style="answers[currentIndex] === option
                ? 'border-color:#4f46e5; background:#ffffff; box-shadow:0 0 0 3px rgba(79,70,229,0.10);'
                : ''"
            @mouseover="if(answers[currentIndex] !== option) { $el.style.borderColor='#c4b5fd'; $el.style.background='#faf9ff'; }"
            @mouseout="if(answers[currentIndex] !== option) { $el.style.borderColor='#e5e7eb'; $el.style.background='#ffffff'; }">

            {{-- Badge huruf --}}
            <div
                class="opt-badge"
                :style="answers[currentIndex] === option
                    ? 'background:#4f46e5; color:#ffffff;'
                    : ''"
                x-text="['A','B','C','D'][oi]">
            </div>

            {{-- Label --}}
            <span
                style="flex:1; font-size:14px; line-height:1.4; transition:color 0.15s, font-weight 0.15s;"
                :style="answers[currentIndex] === option
                    ? 'color:#1e1b4b; font-weight:600;'
                    : 'color:#374151; font-weight:400;'"
                x-text="option">
            </span>
        </button>
    </template>
</div>