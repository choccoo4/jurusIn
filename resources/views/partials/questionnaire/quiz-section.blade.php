{{-- resources/views/partials/questionnaire/quiz-section.blade.php --}}
<section style="min-height:calc(100vh - 64px); background:#f5f4ff; display:flex; align-items:center; justify-content:center; padding:40px 24px;">

    <div style="width:100%; max-width:600px; display:flex; flex-direction:column; gap:20px;">

        {{-- Page header --}}
        @include('partials.questionnaire.quiz-header')

        {{-- Quiz card --}}
        <div
            x-data="questionnaire({{ json_encode($questions) }})"
            style="background:#fff; border-radius:24px; border:1px solid #e0e0f0; overflow:hidden;">

            {{-- Progress bar --}}
            @include('partials.questionnaire.progress-bar')

            {{-- Question area --}}
            <div style="padding:32px 32px 24px;">

                {{-- Step label --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                    <x-tag icon="align-left" bg="#eef2ff" color="#4f46e5">
                        Pertanyaan <span x-text="currentIndex + 1"></span> dari {{ count($questions) }}
                    </x-tag>
                    <span style="font-size:13px; color:#9ca3af;" x-text="Math.round(progress) + '% selesai'"></span>
                </div>

                {{-- Question text --}}
                <div style="min-height:64px; margin-bottom:24px;">
                    <h2 style="font-size:18px; font-weight:700; color:#1e1b4b; line-height:1.5; margin:0;"
                        x-text="currentQuestion.question">
                    </h2>
                </div>

                {{-- Options --}}
                @include('partials.questionnaire.option-list')

            </div>

            {{-- Navigation --}}
            @include('partials.questionnaire.quiz-nav')

            @include('partials.questionnaire.done-overlay')

        </div>

    </div>

</section>