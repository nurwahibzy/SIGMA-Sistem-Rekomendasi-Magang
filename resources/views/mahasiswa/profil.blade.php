@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>
    <section class="section">
        <div class="row">

            @include('mahasiswa.panel-kiri')
            @include('mahasiswa.panel-kanan')
        </div>
    </section>
@endsection

@section('scripts')
    <style>
        #profile-image-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto;
            border-radius: 50%;
        }

        #profile-image-container:hover #image-upload-container:not(.d-none) {
            cursor: pointer;
            opacity: 1;
        }

        #image-upload-container {
            transition: opacity 0.3s ease;
            opacity: 0.7;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
        }

        #image-upload-container:not(.d-none):hover {
            opacity: 1;
        }

        #profile-image-container:hover {
            cursor: pointer;
        }

        #image-preview-badge {
            transition: all 0.3s ease;
            z-index: 5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        @keyframes pulse-border {
            0% { border-color: #28a745; }
            50% { border-color: #218838; }
            100% { border-color: #28a745; }
        }

        .image-changed {
            animation: pulse-border 2s infinite;
        }

        .form-control:disabled {
            transition: background-color 0.3s ease, border-color 0.3s ease, opacity 0.3s ease;
        }

        #btn-edit-profile, #btn-save-profile {
            transition: all 0.3s ease;
        }
    </style>
    <script>

        document.addEventListener("DOMContentLoaded", function() {
            // edit (pane)
            const btnEditForms = document.getElementById("btn-edit-forms");
            if (!btnEditForms) {
                console.error("Edit forms button not found!");
                return;
            }

            const formRight = document.getElementById("form-right-panel");
            if (!formRight) {
                console.error("Right panel form not found!");
                return;
            }

            const formInputs = formRight.querySelectorAll("input, select, textarea");
            const saveButtons = formRight.querySelectorAll(".save-btn");
            let isEditingRight = false;

            btnEditForms.type = "button";

            btnEditForms.addEventListener("click", function(e) {
                console.log("Right panel edit button clicked");
                e.preventDefault();

                if (!isEditingRight) {
                    // masuk mode edit
                    formInputs.forEach(el => el.disabled = false);
                    saveButtons.forEach(btn => btn.classList.remove("d-none"));
                    btnEditForms.innerHTML = '<i class="bi bi-save"></i> Simpan';
                    btnEditForms.classList.replace("btn-outline-primary", "btn-success");
                    isEditingRight = true;
                } else {
                    // kirim data ke server
                    const data = new FormData(formRight);

                    console.log("Submitting right panel form data");
                    fetch("{{ url('/mahasiswa/profil/update') }}", { //ini nanti diganti arah server
                        method: "POST",
                        body: data
                    })
                    .then(res => res.json())
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

                            formInputs.forEach(el => el.disabled = true);
                            saveButtons.forEach(btn => btn.classList.add("d-none"));
                            btnEditForms.innerHTML = '<i class="bi bi-pencil-square"></i> Edit';
                            btnEditForms.classList.replace("btn-success", "btn-outline-primary");
                            isEditingRight = false;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message || 'Gagal menyimpan profil.',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan!',
                            text: 'Terjadi kesalahan saat menyimpan profil.',
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
            const formRight = document.getElementById("form-right-panel");
            const formInputs = formRight.querySelectorAll("input, select, textarea");
            const saveButtons = formRight.querySelectorAll(".save-btn");
            let isEditingRight = false;

            btnEditForms.type = "button";

            // inisiasi choice di panel-kanan
        });
    </script>
@endsection
