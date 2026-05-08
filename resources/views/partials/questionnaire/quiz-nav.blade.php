<div class="quiz-nav">

    {{-- Back --}}
    <button @click="prevStep()"
        :disabled="currentIndex === 0"
        class="btn-ghost">
        <x-icon name="chevron-left" size="16" />
        Kembali
    </button>

    {{-- Right --}}
    <div style="display:flex; align-items:center; gap:14px;">

        <span x-show="!answers[currentIndex]" x-transition class="hint">
            Pilih salah satu dulu
        </span>

        {{-- Next --}}
        <template x-if="!isLast">
            <button @click="nextStep()"
                :disabled="!answers[currentIndex]"
                :class="answers[currentIndex] ? 'btn-primary btn-active' : 'btn-primary btn-disabled'">
                Lanjut
                <x-icon name="chevron-right" size="16" />
            </button>
        </template>

        {{-- Submit --}}
        <template x-if="isLast">
            <button @click="submit()"
                :disabled="!answers[currentIndex]"
                :class="answers[currentIndex] 
                    ? 'btn-primary btn-active' 
                    : 'btn-primary btn-disabled'"
                style="background: linear-gradient(135deg,#4f46e5,#7c3aed);">

                <x-icon name="zap" size="16" />
                Selesai
            </button>
        </template>

    </div>
</div>