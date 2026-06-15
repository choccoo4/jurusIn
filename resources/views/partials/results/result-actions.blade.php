{{-- resources/views/partials/results/result-actions.blade.php --}}
<div style="background:#fff; border:1.5px solid #e0e0f0; border-radius:20px; padding:24px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">

    <div>
        <p style="font-size:14px; font-weight:600; color:#1e1b4b; margin:0 0 4px;">Mau eksplorasi lebih lanjut?</p>
        <p style="font-size:13px; color:#9ca3af; margin:0;">Ulangi tes atau lanjut ke chatbot untuk pertanyaan lanjutan.</p>
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">

       <form action="{{ route('test.reset') }}" method="POST">
            @csrf
            <x-button type="submit" variant="outline" size="sm">
                <x-icon name="refresh" color="#4f46e5" size="15"/>
                Ulangi Tes
            </x-button>

            <x-button href="{{ route('quiz.chat') }}" variant="primary" size="sm">
            <x-icon name="message" color="#fff" size="15" />
            Tanya Lebih Lanjut
        </x-button>
       </form>

    </div>

</div>
