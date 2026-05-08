{{-- DONE OVERLAY --}}
<div x-show="done" x-transition.opacity class="modal-overlay">

    <div :class="done ? 'modal-box show' : 'modal-box'">

        <div class="icon-badge">
            <x-icon name="check-circle" color="#fff" size="28" />
        </div>

        <x-tag icon="check-circle" bg="#dcfce7" color="#166534" style="margin-bottom:16px;">
            Kuesioner selesai
        </x-tag>

        <h3 style="font-size:22px; font-weight:700; color:#1e1b4b; margin-bottom:10px;">
            Jawaban kamu sudah tersimpan!
        </h3>

        <p style="font-size:14px; color:#6b7280; line-height:1.7; margin-bottom:28px;">
            Sekarang lanjut ke chatbot untuk eksplorasi lebih dalam. AI akan menganalisis jawabanmu dan memberikan rekomendasi yang lebih personal.
        </p>

        <x-button href="{{ route('quiz.chat') }}" variant="primary" style="width:100%; justify-content:center;">
            <x-icon name="message" color="#fff" size="16" />
            Lanjut ke Chatbot AI
        </x-button>

    </div>
</div>