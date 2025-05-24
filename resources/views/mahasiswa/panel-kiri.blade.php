<!-- LEFT PANEL -->
<div class="col-md-4">
    <div class="position-sticky" style="top: 90px;">
        <div class="card p-4 text-center">
            <style>
                @keyframes pulse-border {
                    0% { border-color: #28a745; }
                    50% { border-color: #218838; }
                    100% { border-color: #28a745; }
                }

                .image-changed {
                    animation: pulse-border 2s infinite;
                }

                #image-upload-container:not(.d-none):hover {
                    opacity: 1 !important;
                }
            </style>

            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        const editProfileBtn = document.getElementById('btn-edit-profile');
                        if (editProfileBtn) {
                            console.log('Edit profile button found!');
                            editProfileBtn.addEventListener('click', function() {
                                console.log('Edit button clicked - direct handler');
                                const inputs = document.querySelectorAll('#form-left-panel input:not([type="file"]):not([type="hidden"])');
                                inputs.forEach(input => {
                                    input.disabled = false;
                                    console.log('Enabled input:', input.name || 'unnamed');
                                });

                                editProfileBtn.classList.add('d-none');
                                const saveBtn = document.getElementById('btn-save-profile');
                                if (saveBtn) saveBtn.classList.remove('d-none');

                                const imageUploadContainer = document.getElementById('image-upload-container');
                                if (imageUploadContainer) imageUploadContainer.classList.remove('d-none');
                            });
                        } else {
                            console.error('Edit profile button not found!');
                        }
                    }, 500);
                });
            </script>

            <div id="profile-image-container" class="position-relative mx-auto mb-3" style="width: 100px; height: 100px;">
                <img id="profile-image" src="{{ asset(Auth::user()->mahasiswa->foto ?? 'template/assets/images/mhs.jpeg') }}" alt="Profile Picture"
                    class="rounded-circle d-block" width="100" height="100" style="border: 5px solid blue; object-fit: cover;">

                <div id="image-upload-container" class="d-none" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10;">
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 rounded-circle bg-dark bg-opacity-50">
                        <label for="profile_image" class="btn btn-sm btn-light mb-0">
                            <i class="bi bi-camera"></i> Change
                        </label>
                    </div>
                </div>
                <div id="image-preview-badge" class="position-absolute bg-success text-white rounded-circle d-none"
                     style="width: 25px; height: 25px; bottom: 0; right: 0; display: flex; align-items: center; justify-content: center; z-index: 5;">
                    <i class="bi bi-check"></i>
                </div>

                <script>
                    window.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            const profileImageContainer = document.getElementById('profile-image-container');
                            if (profileImageContainer) {
                                console.log('Profile image container found!');
                                profileImageContainer.addEventListener('click', function() {
                                    console.log('Profile image clicked');

                                    const saveBtn = document.getElementById('btn-save-profile');
                                    if (saveBtn && !saveBtn.classList.contains('d-none')) {
                                        console.log('In edit mode, triggering file input');
                                        const fileInput = document.getElementById('profile_image');
                                        if (fileInput) fileInput.click();
                                    }
                                });
                            }

                            const profileImageInput = document.getElementById('profile_image');
                            if (profileImageInput) {
                                console.log('Profile image input found!');
                                profileImageInput.addEventListener('change', function() {
                                    const file = this.files[0];
                                    if (file) {
                                        console.log('File selected:', file.name);
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const profileImage = document.getElementById('profile-image');
                                            if (profileImage) {
                                                profileImage.src = e.target.result;
                                                profileImage.style.border = '5px solid #28a745';
                                                profileImage.classList.add('image-changed');
                                            }

                                            const previewBadge = document.getElementById('image-preview-badge');
                                            if (previewBadge) previewBadge.classList.remove('d-none');
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                });
                            }
                        }, 500);
                    });
                </script>
            </div>

            <form id="form-left-panel" class="text-start mt-3" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
                <div class="mb-2">
                    <label class="form-label">NIM</label>
                    <input type="text"
                        class="form-control"
                        id="nim"
                        name="nim"
                        value="{{ Auth::user()->mahasiswa->nim ?? '-' }}"
                        disabled
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        inputmode="numeric"
                        pattern="\d*">
                </div>
                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                        value="{{ Auth::user()->mahasiswa->nama ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="" placeholder="******" disabled>
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah.</small>
                </div>
                <div class="mb-2">
                    <label class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat"
                        value="{{ Auth::user()->mahasiswa->alamat ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">No Telepon</label>
                    <input type="text" class="form-control" id="no_telepon" name="no_telepon"
                        value="{{ Auth::user()->mahasiswa->no_telepon ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                        value="{{ Auth::user()->mahasiswa->tanggal_lahir ?? '-' }}" disabled>
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ Auth::user()->email }}" disabled>
                </div>
                <button type="button" id="btn-edit-profile" class="btn btn-outline-primary mt-2 w-100">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button type="button" id="btn-save-profile" class="btn btn-success mt-2 w-100 d-none">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>

                <script>
                    window.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            const saveProfileBtn = document.getElementById('btn-save-profile');
                            if (saveProfileBtn) {
                                console.log('Save profile button found!');
                                saveProfileBtn.addEventListener('click', function(e) {
                                    console.log('Save button clicked - direct handler');
                                    e.preventDefault();

                                    const formEl = document.getElementById('form-left-panel');
                                    if (!formEl) {
                                        console.error('Form element not found!');
                                        return;
                                    }

                                    const formData = new FormData(formEl);

                                    console.log('Form data entries:',
                                                [...formData.entries()].map(e => `${e[0]}: ${e[1].toString().substring(0, 30)}${e[1].toString().length > 30 ? '...' : ''}`));

                                    fetch('{{ url("/mahasiswa/profil/update") }}', { //ganti arah server
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(res => {
                                        if (res.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: 'Profil berhasil diperbarui!',
                                                showConfirmButton: false,
                                                timer: 2000,
                                                timerProgressBar: true
                                            });

                                            const inputs = document.querySelectorAll('#form-left-panel input:not([type="file"]):not([type="hidden"])');
                                            inputs.forEach(input => input.disabled = true);

                                            saveProfileBtn.classList.add('d-none');
                                            const editBtn = document.getElementById('btn-edit-profile');
                                            if (editBtn) editBtn.classList.remove('d-none');

                                            const imageUploadContainer = document.getElementById('image-upload-container');
                                            if (imageUploadContainer) imageUploadContainer.classList.add('d-none');

                                            const previewBadge = document.getElementById('image-preview-badge');
                                            if (previewBadge) previewBadge.classList.add('d-none');

                                            const profileImage = document.getElementById('profile-image');
                                            if (profileImage) {
                                                profileImage.style.border = '5px solid blue';
                                                profileImage.classList.remove('image-changed');
                                            }
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal!',
                                                text: res.message || 'Gagal menyimpan profil.',
                                                confirmButtonColor: '#dc3545'
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Terjadi Kesalahan!',
                                            text: 'Terjadi kesalahan saat menyimpan profil.',
                                            confirmButtonColor: '#dc3545'
                                        });
                                    });
                                });
                            } else {
                                console.error('Save profile button not found!');
                            }
                        }, 500);
                    });
                </script>
            </form>
        </div>
    </div>
</div>
