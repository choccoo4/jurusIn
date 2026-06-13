{{-- resources/views/partials/chatbot/message-bubble.blade.php --}}
<div :style="msg.sender === 'user' ? 'display:flex; justify-content:flex-end; align-items:flex-end;' : 'display:flex; justify-content:flex-start; align-items:flex-end;'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-3 scale-95"
    x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    {{-- Bot avatar --}}
    <div x-show="msg.sender === 'bot'"
        style="
            width: 30px; 
            height: 30px; 
            border-radius: 10px; 
            background: linear-gradient(135deg, #4f46e5, #6366f1); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-right: 8px; 
            flex-shrink: 0; 
            margin-bottom: 2px;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
            position: relative;
            line-height: 0;
        ">
        <div style="
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            line-height: 0;
        ">
            <x-icon name="zap" color="#fff" size="14" />
        </div>
    </div>

    {{-- Container wrapper --}}
    <div :style="msg.sender === 'user' 
        ? 'max-width: 65%; min-width: 0; display: flex; flex-direction: column; gap: 4px; align-items: flex-end;'
        : 'max-width: 75%; min-width: 0; display: flex; flex-direction: column; gap: 4px; align-items: flex-start;'">

        {{-- Bubble --}}
        <div style="
            display: inline-block;
            width: fit-content;
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            white-space: pre-wrap;
            padding: 10px 16px;
            font-size: 14px;
            line-height: 1.7;
            transition: all 0.2s ease;
        " :style="msg.sender === 'user'
            ? 'background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; border-radius: 18px 18px 4px 18px; box-shadow: 0 2px 10px rgba(79, 70, 229, 0.15);'
            : 'background: #f5f4ff; color: #1e1b4b; border: 1px solid #e0e0f0; border-radius: 18px 18px 18px 4px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);'"
            @mouseenter="if(msg.sender === 'user') { $el.style.transform = 'translateY(-1px)'; $el.style.boxShadow = '0 4px 14px rgba(79, 70, 229, 0.25)'; } else { $el.style.transform = 'translateY(-1px)'; $el.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.06)'; }"
            @mouseleave="if(msg.sender === 'user') { $el.style.transform = 'translateY(0)'; $el.style.boxShadow = '0 2px 10px rgba(79, 70, 229, 0.15)'; } else { $el.style.transform = 'translateY(0)'; $el.style.boxShadow = '0 1px 4px rgba(0, 0, 0, 0.04)'; }">

            {{-- Text --}}
            <span style="
                display: inline-block;
                margin: 10px 10px;
                word-wrap: break-word;
                overflow-wrap: break-word;
                word-break: break-word;
                white-space: pre-wrap;
            " x-text="msg.text"></span>
        </div>

        {{-- Subject Picker Form --}}
        <template x-if="msg.subjectInput">
            <div style="width:100%; margin-top:8px;" x-data="{
                available: msg.subjectInput.subjects,
                required: msg.subjectInput.required,
                selected: [],
                grades: {},
                submitted: false,
                error: '',
                toggleSubject(name) {
                    const idx = this.selected.indexOf(name);
                    if (idx >= 0) {
                        this.selected.splice(idx, 1);
                        delete this.grades[name];
                    } else {
                        if (this.selected.length < this.required) {
                            this.selected.push(name);
                            this.grades[name] = '';
                        }
                    }
                    this.error = '';
                },
                isSelected(name) { return this.selected.includes(name); },
                async submit() {
                    if (this.selected.length !== this.required) {
                        this.error = 'Pilih tepat ' + this.required + ' mata pelajaran ya!';
                        return;
                    }
                    for (const name of this.selected) {
                        const g = parseFloat(this.grades[name]);
                        if (this.grades[name] === '' || isNaN(g)) {
                            this.error = 'Isi nilai untuk ' + name;
                            return;
                        }
                        if (g < 0 || g > 100) {
                            this.error = 'Nilai ' + name + ' harus antara 0–100';
                            return;
                        }
                    }
                    this.submitted = true;
                    this.error = '';
                    const payload = this.selected.map(name => ({ name, grade: parseFloat(this.grades[name]) }));
                    $dispatch('subject-submit', payload);
                }
            }">
                {{-- Chip grid untuk pilih mapel --}}
                <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:12px;">
                    <template x-for="subj in available" :key="subj">
                        <button
                            @click="if(!submitted) toggleSubject(subj)"
                            :disabled="submitted || (!isSelected(subj) && selected.length >= required)"
                            :style="isSelected(subj)
                                ? 'padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; background:#4f46e5; color:#fff; border:1.5px solid #4f46e5; cursor:pointer; transition:all 0.15s;'
                                : (selected.length >= required && !isSelected(subj))
                                    ? 'padding:6px 14px; border-radius:20px; font-size:12px; font-weight:500; background:#f3f4f6; color:#9ca3af; border:1.5px solid #e5e7eb; cursor:not-allowed; transition:all 0.15s;'
                                    : 'padding:6px 14px; border-radius:20px; font-size:12px; font-weight:500; background:#eef2ff; color:#4f46e5; border:1.5px solid #c7d2fe; cursor:pointer; transition:all 0.15s;'"
                            x-text="subj">
                        </button>
                    </template>
                </div>

                {{-- Counter --}}
                <p style="font-size:12px; color:#6b7280; margin-bottom:10px;">
                    Dipilih: <span x-text="selected.length" style="font-weight:600; color:#4f46e5;"></span> / <span x-text="required"></span>
                </p>

                {{-- Input nilai untuk yang sudah dipilih --}}
                <template x-if="selected.length > 0">
                    <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px;">
                        <template x-for="name in selected" :key="name">
                            <div style="display:flex; align-items:center; gap:10px; background:#f5f4ff; border-radius:12px; padding:8px 12px; border:1px solid #e0e0f0;">
                                <span style="flex:1; font-size:13px; font-weight:500; color:#1e1b4b;" x-text="name"></span>
                                <input
                                    type="number"
                                    min="0" max="100"
                                    :disabled="submitted"
                                    x-model="grades[name]"
                                    placeholder="Nilai"
                                    style="width:70px; padding:6px 10px; border-radius:8px; border:1px solid #c7d2fe; font-size:13px; text-align:center; color:#1e1b4b; background:#fff; outline:none;"
                                    onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 2px rgba(99,102,241,0.15)';"
                                    onblur="this.style.borderColor='#c7d2fe'; this.style.boxShadow='none';"
                                >
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Error message --}}
                <p x-show="error" x-text="error" style="font-size:12px; color:#ef4444; margin-bottom:8px;"></p>

                {{-- Submit button --}}
                <button
                    @click="submit()"
                    :disabled="submitted || selected.length !== required"
                    :style="(submitted || selected.length !== required)
                        ? 'padding:10px 24px; border-radius:12px; font-size:13px; font-weight:600; background:#e5e7eb; color:#9ca3af; border:none; cursor:not-allowed; width:100%;'
                        : 'padding:10px 24px; border-radius:12px; font-size:13px; font-weight:600; background:linear-gradient(135deg,#4f46e5,#6366f1); color:#fff; border:none; cursor:pointer; width:100%; box-shadow:0 4px 12px rgba(79,70,229,0.3); transition:all 0.15s;'"
                    onmouseover="if(!this.disabled){this.style.transform='translateY(-1px)';}"
                    onmouseout="if(!this.disabled){this.style.transform='translateY(0)';}">
                    <span x-show="!submitted">✓ Konfirmasi Pilihan</span>
                    <span x-show="submitted">✅ Tersimpan</span>
                </button>
            </div>
        </template>

        {{-- Result cards (kalau mau ada result di chatbot sudah ada, tapi sementara null dulu) --}}
        <template x-if="msg.results && msg.results.length">
            <div style="
                display: flex;
                flex-direction: column;
                gap: 8px;
                width: 100%;
                margin-top: 4px;
            ">
                <template x-for="(result, ri) in msg.results" :key="ri">
                    <div style="
                        background: #fff;
                        border: 1px solid #e0e0f0;
                        border-radius: 16px;
                        padding: 14px 16px;
                        transition: all 0.2s ease;
                        cursor: pointer;
                        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
                    "
                        @mouseenter="$el.style.transform = 'translateY(-2px)'; $el.style.boxShadow = '0 4px 12px rgba(79, 70, 229, 0.1)'; $el.style.borderColor = '#c7d2fe';"
                        @mouseleave="$el.style.transform = 'translateY(0)'; $el.style.boxShadow = '0 1px 4px rgba(0, 0, 0, 0.04)'; $el.style.borderColor = '#e0e0f0';">
                        <div style="
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 8px;
                            gap: 10px;
                        ">
                            <span style="
                                font-size: 13px;
                                font-weight: 600;
                                color: #1e1b4b;
                                word-break: break-word;
                                flex: 1;
                                min-width: 0;
                                line-height: 1.4;
                            " x-text="result.major"></span>
                            <span style="
                                font-size: 12px;
                                font-weight: 700;
                                background: linear-gradient(135deg, #4f46e5, #6366f1);
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;
                                background-clip: text;
                                flex-shrink: 0;
                            " x-text="result.pct + '%'"></span>
                        </div>
                        <div style="
                            height: 6px;
                            background: #f0f0f8;
                            border-radius: 99px;
                            overflow: hidden;
                        ">
                            <div :style="`height:100%; border-radius:99px; background: linear-gradient(90deg, #4f46e5, #6366f1); width:${result.pct}%; animation:growBar 1.2s cubic-bezier(0.4,0,0.2,1) both; animation-delay:${ri * 0.15}s`"></div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- Timestamp --}}
        <span style="
            font-size: 11px;
            color: #9ca3af;
            flex-shrink: 0;
            margin-top: 2px;
            padding: 0 4px;
        " x-text="msg.time"></span>

    </div>

    {{-- User avatar --}}
    <div x-show="msg.sender === 'user'"
        style="
            width: 30px;
            height: 30px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin-left: 8px; 
            flex-shrink: 0; 
            margin-bottom: 2px;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.1);
            position: relative;
            line-height: 0;
        ">
        <div style="
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            line-height: 0;
        ">
            <x-icon name="user" color="#4f46e5" size="14" />
        </div>
    </div>

</div>