{{-- resources/views/partials/chatbot/subject-modal.blade.php --}}
<div x-show="showSubjectModal" x-transition
    style="position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; background:rgba(0,0,0,0.5); display:grid; place-items:center; padding:20px;">

    <div style="background:#fff; border-radius:20px; padding:28px; max-width:500px; width:100%;">

        {{-- HEADER --}}
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
            <div style="width:36px; height:36px; border-radius:12px; background:#eef2ff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <x-icon name="file-text" color="#4f46e5" size="18" />
            </div>
            <h3 style="font-size:18px; font-weight:700; color:#1e1b4b; margin:0;">
                Satu Langkah Lagi!
            </h3>
        </div>
        <p style="font-size:12px; color:#6b7280; margin:0 0 20px; line-height:1.5;">
            Pilih <strong>3–4 mata pelajaran</strong> favorit beserta nilainya.
            Tulis nama lengkap ya. Contoh: <em>Administrasi Sistem Jaringan</em> (bukan ASJ)
        </p>

        {{-- INPUT MANUAL --}}
        <div style="margin-bottom:14px;">
            <input type="text"
                x-model="subjectInput"
                @keydown.enter.prevent="addCustomSubject()"
                placeholder="Ketik: Matematika 85, Fisika 78..."
                style="width:100%; padding:10px 14px; border-radius:12px; border:1px solid #e0e0f0; font-size:14px; outline:none;">
            <p style="font-size:11px; color:#9ca3af; margin:4px 0 0;">
                Format: Nama Mapel Nilai. Contoh: <em>Matematika 85</em>
            </p>
        </div>

        {{-- SELECTED TAGS --}}
        <div style="margin-bottom:14px; min-height:32px;">
            <template x-for="(s, i) in selectedSubjects" :key="s.name">
                <span style="display:inline-flex; align-items:center; gap:4px; padding:6px 12px; border-radius:99px; background:#eef2ff; color:#4f46e5; font-size:13px; font-weight:500; margin:0 6px 6px 0;">
                    <span x-text="s.name + ' (' + s.score + ')'"></span>
                    <button @click="selectedSubjects.splice(i,1)" style="border:none; background:none; cursor:pointer; color:#4f46e5; font-size:16px; line-height:1; padding:0;">&times;</button>
                </span>
            </template>
        </div>

        {{-- COUNT + LIMIT --}}
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <span style="font-size:12px; color:#9ca3af;" x-text="selectedSubjects.length + '/4 terpilih'"></span>
            <span x-show="selectedSubjects.length >= 4" style="font-size:12px; color:#4f46e5; font-weight:500;">
                Maksimal 4
            </span>
        </div>

        {{-- TAG SUGGESTIONS --}}
        <p style="font-size:11px; color:#9ca3af; margin:0 0 8px;">
            Atau pilih mapel, lalu masukkan nilainya:
        </p>
        <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:14px;">
            <template x-for="subject in popularSubjects" :key="subject">
                <button @click="selectSubject(subject)"
                    x-show="!selectedSubjects.find(s => s.name === subject) && selectedSubjects.length < 4"
                    style="padding:6px 12px; border-radius:99px; border:1px solid #e0e0f0; background:#fff; font-size:12px; color:#374151; cursor:pointer; -webkit-appearance:none; appearance:none;"
                    onmouseover="this.style.borderColor='#c7d2fe'; this.style.background='#faf9ff';"
                    onmouseout="this.style.borderColor='#e0e0f0'; this.style.background='#fff';">
                    <span x-text="subject"></span>
                </button>
            </template>
        </div>

        {{-- INPUT NILAI --}}
        <div x-show="pendingSubject" style="display:flex; align-items:center; gap:8px; margin-bottom:14px; padding:10px 14px; background:#fafbff; border-radius:12px; border:1px solid #e0e0f0;">
            <span style="font-size:14px; font-weight:600; color:#1e1b4b;" x-text="pendingSubject"></span>
            <input type="number"
                x-model="pendingScore"
                @keydown.enter.prevent="confirmSubject()"
                placeholder="Nilai"
                min="0" max="100"
                style="width:70px; padding:6px 10px; border-radius:8px; border:1px solid #e0e0f0; font-size:14px; outline:none;">
            <button @click="confirmSubject()"
                style="padding:6px 12px; border-radius:8px; border:none; background:#4f46e5; color:#fff; font-size:13px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                Tambah
            </button>
            <button @click="pendingSubject = null; pendingScore = null"
                style="padding:6px 8px; border-radius:8px; border:none; background:transparent; color:#9ca3af; font-size:13px; cursor:pointer; -webkit-appearance:none; appearance:none;">
                <x-icon name="plus" color="#9ca3af" size="14" style="transform:rotate(45deg);" />
            </button>
        </div>

        {{-- BUTTON SUBMIT --}}
        <button @click="submitSubjects()"
            :disabled="selectedSubjects.length < 3"
            :style="selectedSubjects.length < 3 ? 'opacity:0.5;' : 'width:100%; margin-top:12px; padding:12px; border-radius:14px; border:none; background:#4f46e5; color:#fff; font-size:15px; font-weight:600; cursor:pointer;'"> Simpan & Lanjutkan
        </button>
    </div>
</div>