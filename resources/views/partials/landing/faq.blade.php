{{-- resources/views/partials/landing/faq.blade.php --}}
<section id="faq" style="padding:80px 24px; background:#f5f4ff;">
    <div style="max-width:720px; margin:0 auto;">

        <div style="text-align:center; margin-bottom:48px;">
            <x-tag icon="help-circle" style="margin:0 auto 16px; display:inline-flex;">FAQ</x-tag>
            <h2 style="font-size:34px; font-weight:700; color:#1e1b4b; margin:12px 0; letter-spacing:-0.5px;">Pertanyaan umum</h2>
            <p style="font-size:16px; color:#6b7280;">Hal-hal yang sering ditanyakan tentang JurusIn.</p>
        </div>

        <div x-data="faqAccordion()" style="background:#fff; border-radius:20px; border:1px solid #e0e0f0; overflow:hidden;">
            @foreach($faqs as $i => $faq)
            <div style="border-bottom:{{ !$loop->last ? '1px solid #e8e8f0' : 'none' }};">
                <button @click="toggle({{ $i }})"
                    style="width:100%; display:flex; justify-content:space-between; align-items:center; padding:20px 24px; background:transparent; border:none; cursor:pointer; text-align:left; gap:16px;">
                    <span style="font-size:15px; font-weight:600; color:#1e1b4b;">{{ $faq['question'] }}</span>
                    <div :style="`width:28px; height:28px; border-radius:50%; background:${isOpen({{ $i }}) ? '#4f46e5' : '#eef2ff'}; flex-shrink:0; display:flex; align-items:center; justify-content:center; transition:background 0.2s, transform 0.2s; transform:${isOpen({{ $i }}) ? 'rotate(45deg)' : 'none'}`">
                        <div :style="`color:${isOpen({{ $i }}) ? '#fff' : '#4f46e5'}; display:flex;`">
                            <x-icon name="plus" color="currentColor" size="13" stroke="2.5" />
                        </div>
                    </div>
                </button>
                <div x-show="isOpen({{ $i }})" x-collapse style="padding:0 24px 20px;">
                    <p style="font-size:14px; color:#6b7280; line-height:1.75; margin:0;">{{ $faq['answer'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>