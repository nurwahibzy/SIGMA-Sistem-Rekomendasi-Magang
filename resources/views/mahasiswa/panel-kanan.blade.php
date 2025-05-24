<!-- RIGHT PANEL -->
<div class="col-md-8">
    <!-- Info Box -->
    <div class="card p-5 bg-primary bg-opacity-10 border-0 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold text-primary mb-3">
                    Tarik perhatian rekruter dengan <br>
                    <span class="text-light">Profil Anda</span>
                </h4>
                <p class="text-muted">
                    Buat profil dan bantu perusahaan mengenal Anda lebih mudah.
                    Dapatkan rekomendasi magang yang sesuai pengalaman dan keahlian Anda.
                </p>
            </div>
            <div class="col-md-4 d-flex justify-content-center">
                <img src="{{ asset('template/assets/images/magang.jpg') }}" alt="Foto Profil"
                    class="rounded-circle shadow" style="width: 120px; height: 120px; object-fit: cover;">
            </div>
        </div>
    </div>

    <!-- KEAHLIAN SECTION -->
    <div class="section-wrapper mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">Keahlian</h5>
            <button type="button" data-section="keahlian" class="btn-edit-section btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
        </div>

        <form id="form-keahlian" action="{{ url('/mahasiswa/profil/update/keahlian') }}" method="POST" class="section-form">
            @csrf
            <p class="text-muted mb-3">Tulis keahlian mu dan tentukan skala prioritas nya</p>

            <div class="mb-2">
                <button type="button" id="btn-add-keahlian" class="btn btn-sm btn-primary" disabled>
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </div>

            <fieldset class="form-section" id="section-keahlian" disabled>
                <div id="keahlian-container">
                    <div class="card mb-4 skill-item">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Bidang Keahlian</label>
                                <select name="id_bidang[]" class="form-select id_bidang">
                                    <option value="">-- Pilih Bidang --</option>
                                    @foreach ($bidang as $item)
                                        <option value="{{ $item->id_bidang }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prioritas</label>
                                <select name="prioritas[]" class="form-select prioritas">
                                    <option value="">-- Pilih Prioritas --</option>
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Keahlian</label>
                                <textarea name="keahlian[]" rows="4" class="form-control keahlian"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- PREFERENSI PERUSAHAAN SECTION -->
    <div class="section-wrapper mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">Preferensi Perusahaan</h5>
            <button type="button" data-section="perusahaan" class="btn-edit-section btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
        </div>

        {{-- ganti route arah server --}}
        <form id="form-perusahaan" action="{{ url('/mahasiswa/profil/update/perusahaan') }}" method="POST" class="section-form">
            @csrf
            <p class="text-muted mb-3">
                Pilih jenis perusahaan yang kamu minati. Kamu bisa memilih lebih dari satu.
            </p>

            <fieldset class="form-section" id="section-perusahaan" disabled>
                <div class="card p-4">
                    <div class="form-group">
                        <label for="jenis_perusahaan" class="form-label">Jenis Perusahaan</label>
                        <select class="custom-choices" multiple id="jenis_perusahaan"
                            name="jenis_perusahaan[]">
                            <optgroup label="Jenis Perusahaan">
                                @foreach ($jenis as $item)
                                    <option value="{{ $item->id_jenis }}"
                                        {{ in_array($item->id_jenis, old('jenis_perusahaan', $preferensi_perusahaan->pluck('jenis_perusahaan_id')->toArray() ?? [])) ? 'selected' : '' }}>
                                        {{ $item->jenis }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- PREFERENSI LOKASI SECTION -->
    <div class="section-wrapper mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">Preferensi Lokasi</h5>
            <button type="button" data-section="lokasi" class="btn-edit-section btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
        </div>

        {{-- ganti route arah server --}}
        <form id="form-lokasi" action="{{ url('/mahasiswa/profil/update/lokasi') }}" method="POST" class="section-form">
            @csrf
            <p class="text-muted mb-3">
                Pilih sesuai dengan lokasi kamu sekarang.
            </p>

            <fieldset class="form-section" id="section-lokasi" disabled>
                <div class="card p-4 mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="provinsi" class="form-label">Provinsi</label>
                            <select class="form-select" id="provinsi" name="provinsi">
                                <option value="">Provinsi</option>
                                {{-- @foreach ($provinsi as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kota" class="form-label">Kota/Kabupaten</label>
                            <select class="form-select" id="kota" name="kota">
                                <option value="">Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- PENGALAMAN SECTION -->
    <div class="section-wrapper mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">Pengalaman</h5>
            <button type="button" data-section="pengalaman" class="btn-edit-section btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
        </div>

        {{-- ganti route arah server --}}
        <form id="form-pengalaman" action="{{ url('/mahasiswa/profil/update/pengalaman') }}" method="POST" class="section-form">
            @csrf
            <p class="text-muted mb-3">
                Isi semua pengalaman kamu di sini.
            </p>

            <div class="mb-2">
                <button type="button" id="btn-add-pengalaman" class="btn btn-sm btn-primary" disabled>
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </div>

            <fieldset class="form-section" id="section-pengalaman" disabled>
                <div id="pengalaman-container">
                    <div class="card mb-4 experience-item">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <textarea class="form-control pengalaman" name="pengalaman[]" rows="4"
                                    placeholder="Contoh: Magang di PT XYZ sebagai Web Developer..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <!-- CV SECTION -->
    <div class="section-wrapper mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">CV</h5>
            <button type="button" data-section="cv" class="btn-edit-section btn btn-outline-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
        </div>

        {{-- ganti route arah server --}}
        <form id="form-cv" action="{{ url('/mahasiswa/profil/update/cv') }}" method="POST" enctype="multipart/form-data" class="section-form">
            @csrf
            <fieldset class="form-section" id="section-cv" disabled>
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title">Upload CV</h5>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <p class="card-text">Lengkapi Profilmu dengan mengunggah dokumen CV.</p>
                                    <input type="file" name="cv" class="form-control basic-filepond">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let choicesInstances = {};

                setTimeout(() => {
                    const jenisPerusahaanSelect = document.getElementById('jenis_perusahaan');
                    if (jenisPerusahaanSelect) {
                        try {
                            choicesInstances.perusahaan = new Choices(jenisPerusahaanSelect, {
                                removeItemButton: true,
                                searchEnabled: true,
                                searchPlaceholderValue: 'Cari jenis perusahaan...',
                                itemSelectText: '',
                                classNames: {
                                    containerOuter: 'choices custom-choices-container',
                                    containerInner: 'choices__inner custom-choices-inner',
                                    list: 'choices__list',
                                    listItems: 'choices__list--multiple',
                                    listSingle: 'choices__list--single',
                                    listDropdown: 'choices__list--dropdown',
                                    item: 'choices__item',
                                    itemSelectable: 'choices__item--selectable',
                                    input: 'choices__input',
                                    placeholder: 'choices__placeholder',
                                },
                                placeholderValue: 'Pilih jenis perusahaan',
                                shouldSort: false,
                                position: 'bottom'
                            });
                            choicesInstances.perusahaan.disable();
                        } catch (e) {
                            console.warn('Could not initialize/disable Choices.js component:', e);
                        }
                    }
                }, 100);

                const editButtons = document.querySelectorAll('.btn-edit-section');

                editButtons.forEach(button => {
                    const sectionId = button.getAttribute('data-section');
                    let isEditing = false;

                    button.addEventListener('click', function() {
                        const form = document.getElementById(`form-${sectionId}`);
                        const fieldset = document.getElementById(`section-${sectionId}`);

                        if (!isEditing) {
                            fieldset.disabled = false;
                            isEditing = true;

                            if (sectionId === 'keahlian') {
                                document.getElementById('btn-add-keahlian').disabled = false;
                            } else if (sectionId === 'pengalaman') {
                                document.getElementById('btn-add-pengalaman').disabled = false;
                            } else if (sectionId === 'perusahaan' && choicesInstances.perusahaan) {
                                choicesInstances.perusahaan.enable();
                                const container = document.querySelector('.custom-choices-container');
                                if (container) {
                                    container.style.opacity = '1';
                                }
                            }

                            this.innerHTML = '<i class="bi bi-save"></i> Simpan';
                            this.classList.replace('btn-outline-primary', 'btn-success');
                        } else {
                            const formData = new FormData(form);

                            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
                            this.disabled = true;

                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.disabled = false;

                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message || `Data ${sectionId} berhasil diperbarui!`,
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true
                                    });

                                    fieldset.disabled = true;
                                    isEditing = false;

                                    if (sectionId === 'keahlian') {
                                        document.getElementById('btn-add-keahlian').disabled = true;
                                    } else if (sectionId === 'pengalaman') {
                                        document.getElementById('btn-add-pengalaman').disabled = true;
                                    } else if (sectionId === 'perusahaan' && choicesInstances.perusahaan) {
                                        choicesInstances.perusahaan.disable();
                                        const container = document.querySelector('.custom-choices-container');
                                        if (container) {
                                            container.style.opacity = '0.7';
                                        }
                                    }

                                    this.innerHTML = '<i class="bi bi-pencil-square"></i> Edit';
                                    this.classList.replace('btn-success', 'btn-outline-primary');
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: data.message || `Gagal menyimpan data ${sectionId}!`,
                                        confirmButtonColor: '#dc3545'
                                    });

                                    this.innerHTML = '<i class="bi bi-save"></i> Simpan';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);

                                this.disabled = false;
                                this.innerHTML = '<i class="bi bi-save"></i> Simpan';

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan!',
                                    text: `Terjadi kesalahan saat menyimpan data ${sectionId}!`,
                                    confirmButtonColor: '#dc3545'
                                });
                            });
                        }
                    });
                });

                function updatePriorityOptions() {
                    const items = document.querySelectorAll('#keahlian-container .skill-item');
                    const count = items.length;
                    items.forEach(item => {
                        const sel = item.querySelector('select.prioritas');
                        sel.innerHTML = '<option value="">-- Pilih Prioritas --</option>';
                        for (let i = 1; i <= count; i++) {
                            const opt = document.createElement('option');
                            opt.value = i;
                            opt.textContent = i;
                            sel.appendChild(opt);
                        }
                    });
                }

                function updateBidangOptions() {
                    const selects = document.querySelectorAll('#keahlian-container select.id_bidang');
                    const values = Array.from(selects).map(s => s.value).filter(v => v);
                    selects.forEach(select => {
                        Array.from(select.options).forEach(opt => {
                            if (opt.value && values.includes(opt.value) && select.value !== opt.value) {
                                opt.disabled = true;
                            } else {
                                opt.disabled = false;
                            }
                        });
                    });
                }

                function updatePrioritasDisabled() {
                    const selects = document.querySelectorAll('#keahlian-container select.prioritas');
                    const values = Array.from(selects).map(s => s.value).filter(v => v);
                    selects.forEach(select => {
                        Array.from(select.options).forEach(opt => {
                            if (opt.value && values.includes(opt.value) && select.value !== opt.value) {
                                opt.disabled = true;
                            } else {
                                opt.disabled = false;
                            }
                        });
                    });
                }

                updateBidangOptions();
                updatePriorityOptions();
                updatePrioritasDisabled();

                document.getElementById('keahlian-container').addEventListener('change', function(e) {
                    if (e.target.matches('select.id_bidang')) {
                        updateBidangOptions();
                    }
                    if (e.target.matches('select.prioritas')) {
                        updatePrioritasDisabled();
                    }
                });

                const addKeahlianBtn = document.getElementById('btn-add-keahlian');
                addKeahlianBtn.addEventListener('click', function() {
                    const container = document.getElementById('keahlian-container');
                    const cards = container.querySelectorAll('.skill-item');
                    const lastCard = cards[cards.length - 1];
                    const clone = lastCard.cloneNode(true);
                    clone.querySelectorAll('select, textarea').forEach(el => el.value = '');
                    clone.classList.add('position-relative');
                    const btnClose = document.createElement('button');
                    btnClose.type = 'button';
                    btnClose.className = 'btn-close remove-keahlian position-absolute top-0 end-0 m-2';
                    clone.insertBefore(btnClose, clone.firstChild);
                    container.appendChild(clone);
                    updatePriorityOptions();
                    updateBidangOptions();
                    updatePrioritasDisabled();
                });

                document.getElementById('keahlian-container').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-keahlian')) {
                        const card = e.target.closest('.skill-item');
                        card.remove();
                        updatePriorityOptions();
                        updateBidangOptions();
                        updatePrioritasDisabled();
                    }
                });

                const addPengalamanBtn = document.getElementById('btn-add-pengalaman');
                addPengalamanBtn.addEventListener('click', function() {
                    const container = document.getElementById('pengalaman-container');
                    const cards = container.querySelectorAll('.experience-item');
                    const lastCard = cards[cards.length - 1];
                    const clone = lastCard.cloneNode(true);
                    clone.querySelectorAll('textarea').forEach(el => el.value = '');
                    clone.classList.add('position-relative');
                    const btnClose = document.createElement('button');
                    btnClose.type = 'button';
                    btnClose.className = 'btn-close remove-pengalaman position-absolute top-0 end-0 m-2';
                    clone.insertBefore(btnClose, clone.firstChild);
                    container.appendChild(clone);
                });

                document.getElementById('pengalaman-container').addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-pengalaman')) {
                        const card = e.target.closest('.experience-item');
                        card.remove();
                    }
                });

                const themeToggleBtn = document.getElementById('toggle-dark');
                if (themeToggleBtn) {
                    themeToggleBtn.addEventListener('change', function() {
                        setTimeout(() => {
                            if (choicesInstances.perusahaan) {
                                const isDisabled = choicesInstances.perusahaan.config.disabled;

                                const jenisPerusahaanSelect = document.getElementById('jenis_perusahaan');
                                if (jenisPerusahaanSelect) {
                                    const selectedValues = choicesInstances.perusahaan.getValue();
                                    choicesInstances.perusahaan.destroy();

                                    choicesInstances.perusahaan = new Choices(jenisPerusahaanSelect, {
                                        removeItemButton: true,
                                        searchEnabled: true,
                                        searchPlaceholderValue: 'Cari jenis perusahaan...',
                                        itemSelectText: '',
                                        classNames: {
                                            containerOuter: 'choices custom-choices-container',
                                            containerInner: 'choices__inner custom-choices-inner',
                                            list: 'choices__list',
                                            listItems: 'choices__list--multiple',
                                            listSingle: 'choices__list--single',
                                            listDropdown: 'choices__list--dropdown',
                                            item: 'choices__item',
                                            itemSelectable: 'choices__item--selectable',
                                            input: 'choices__input',
                                            placeholder: 'choices__placeholder',
                                        },
                                        placeholderValue: 'Pilih jenis perusahaan',
                                        shouldSort: false,
                                        position: 'bottom'
                                    });

                                    if (selectedValues && selectedValues.length) {
                                        choicesInstances.perusahaan.setChoiceByValue(
                                            selectedValues.map(item => item.value)
                                        );
                                    }

                                    if (isDisabled) {
                                        choicesInstances.perusahaan.disable();
                                    }
                                }
                            }
                        }, 200);
                    });
                }
            });
        </script>

        <style>
            /* semantic dark light manual, klo mau coba ganti warna di sini aja*/
            :root {
                --bs-primary: #05a3c9;
                --bs-primary-rgb: 5, 163, 201;
                --bs-primary-dark: #037c99;
                --bs-body-bg: #22233B;
                --bs-body-color: #ffffff;
                --bs-secondary-color: rgba(255, 255, 255, 0.6);
                --bs-tertiary-bg: #1c1e30;
                --bs-border-color: rgba(255, 255, 255, 0.1);
                --bs-border-color-translucent: rgba(255, 255, 255, 0.175);
            }

            html[data-bs-theme="light"] {
                /* light */
                --bs-body-bg: #f8f9fa;
                --bs-body-color: #212529;
                --bs-secondary-color: rgba(33, 37, 41, 0.6);
                --bs-tertiary-bg: #ffffff;
                --bs-border-color: rgba(0, 0, 0, 0.1);
                --bs-border-color-translucent: rgba(0, 0, 0, 0.175);
            }

            .section-wrapper {
                margin-bottom: 2rem;
            }

            .form-section {
                transition: opacity 0.3s ease;
            }

            .form-section:disabled {
                opacity: 0.75;
            }

            .btn-edit-section {
                transition: all 0.3s ease;
            }

            /* style choice */
            .choices__inner {
                background-color: var(--bs-body-bg, #22233B);
                border-radius: 4px;
                border: 1px solid var(--bs-border-color, rgba(255,255,255,0.1));
                color: var(--bs-body-color, white);
                min-height: 44px;
                padding: 7px 7px 3px;
            }

            .choices__list--multiple .choices__item {
                background-color: var(--bs-primary, #05a3c9);
                border: none;
                border-radius: 20px;
                color: var(--bs-white, #fff);
                padding: 5px 10px;
                margin-bottom: 3px;
                margin-right: 5px;
                font-size: 13px;
            }

            .choices.is-disabled {
                opacity: 0.7;
            }

            .choices.is-disabled .choices__inner {
                background-color: var(--bs-tertiary-bg, #1c1e30) !important;
                border: 1px solid var(--bs-border-color, #2c2e43) !important;
                color: var(--bs-body-color, white) !important;
                cursor: not-allowed;
            }

            .choices.is-disabled .choices__placeholder {
                color: var(--bs-body-color, white) !important;
                opacity: 0.7;
            }

            html[data-bs-theme="light"] .choices.is-disabled .choices__placeholder {
                color: var(--bs-body-color, #212529) !important;
                opacity: 0.7;
            }

            .choices.is-disabled .choices__list--multiple .choices__item {
                background-color: var(--bs-secondary-bg, #304254) !important;
                border: none !important;
                color: var(--bs-secondary-color, #b0b5bd) !important;
                opacity: 0.8;
            }

            .choices.is-disabled .choices__item--disabled {
                cursor: not-allowed;
                user-select: none;
            }

            .choices__item--selectable {
                cursor: pointer;
            }

            .choices__list--dropdown {
                background-color: var(--bs-body-bg, #22233B);
                border-color: var(--bs-border-color, #1c1e30);
                box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            }

            .choices__list--dropdown .choices__item {
                color: var(--bs-body-color, white);
            }

            .choices__list--dropdown .choices__item--selectable.is-highlighted {
                background-color: var(--bs-primary, #05a3c9);
                color: var(--bs-white, white);
            }

            .choices:not(.is-disabled) .choices__inner:focus,
            .choices:not(.is-disabled) .choices__inner:focus-within {
                border-color: var(--bs-primary, #05a3c9) !important;
                box-shadow: 0 0 0 0.25rem var(--bs-primary-rgb, rgba(5, 163, 201, 0.25)) !important;
                outline: 0;
            }

            .skill-item, .experience-item {
                position: relative;
            }

            .remove-keahlian, .remove-pengalaman {
                z-index: 10;
            }

            .choices__list--multiple .choices__item.is-highlighted {
                background-color: var(--bs-primary-dark, #037c99);
                border: none;
            }

            .choices__list--dropdown {
                margin-top: 3px;
                border-radius: 4px;
            }

            .choices__list--dropdown .choices__item {
                padding: 10px;
            }

            .choices__list--dropdown .choices__group {
                padding: 10px;
                color: var(--bs-primary, #05a3c9);
                font-weight: bold;
                border-bottom: 1px solid var(--bs-border-color, rgba(255,255,255,0.1));
            }

            .choices__placeholder {
                color: var(--bs-body-color, white);
            }

            .choices__input {
                background-color: transparent;
                color: var(--bs-body-color, white);
            }

            .choices[data-type*="select-multiple"] .choices__button {
                border-left: 1px solid var(--bs-border-color-translucent, rgba(255,255,255,0.3));
                background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMjEiIHZpZXdCb3g9IjAgMCAyMSAyMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSIjRkZGIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0yLjU5Mi4wNDRsMTguMzY0IDE4LjM2NC0yLjU0OCAyLjU0OEwuMDQ0IDIuNTkyeiIvPjxwYXRoIGQ9Ik0wIDE4LjM2NEwxOC4zNjQgMGwyLjU0OCAyLjU0OEwyLjU0OCAyMC45MTJ6Ii8+PC9nPjwvc3ZnPg==");
            }

            /* overide light */
            html[data-bs-theme="light"] .choices__inner {
                background-color: var(--bs-tertiary-bg, #f8f9fa);
                color: var(--bs-body-color, #212529);
            }

            html[data-bs-theme="light"] .choices__list--dropdown {
                background-color: var(--bs-tertiary-bg, #f8f9fa);
            }

            html[data-bs-theme="light"] .choices__list--dropdown .choices__item {
                color: var(--bs-body-color, #212529);
            }

            html[data-bs-theme="light"] .choices__input {
                color: var(--bs-body-color, #212529);
                background-color: transparent !important;
            }

            html[data-bs-theme="light"] .choices__placeholder {
                color: var(--bs-body-color, #212529);
            }

            .choices__list--dropdown .choices__input {
                background-color: transparent !important;
                border: 1px solid var(--bs-border-color) !important;
                border-radius: 4px;
                padding: 8px;
                margin-bottom: 8px;
            }
        </style>
    @endpush
</div>
