@extends('layouts.tamplate')
@section('content')
    <div class="page-heading">
        <h3>My Profile</h3>
    </div>
    <section class="section">
        <div class="row">

            <!-- LEFT PANEL -->
            <div class="col-md-4">
                <div class="position-sticky" style="top: 90px;">
                    <div class="card p-4 text-center">
                        <img src="{{ asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture"
                            class="rounded-circle mx-auto d-block mb-3" width="100" height="100"
                            style="border: 5px solid blue;">
                        <form id="form-left-panel" class="text-start mt-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" id="nim"
                                    value="{{ Auth::user()->admin->nip ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama"
                                    value="{{ Auth::user()->admin->nama ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" value="******" disabled>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah.</small>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat"
                                    value="{{ Auth::user()->admin->alamat ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control" id="no_telepon"
                                    value="{{ Auth::user()->admin->no_telepon ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir"
                                    value="{{ Auth::user()->admin->tanggal_lahir ?? '-' }}" disabled>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email"
                                    value="{{ Auth::user()->email }}" disabled>
                            </div>
                            <button type="button" id="btn-edit-profile" class="btn btn-outline-primary mt-2 w-100">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button type="submit" id="btn-save-profile" class="btn btn-success mt-2 w-100 d-none">
                                <i class="bi bi-check-lg"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editBtn = document.getElementById("btn-edit-profile");
        const saveBtn = document.getElementById("btn-save-profile");
        const inputs = document.querySelectorAll("#form-left-panel input");

        // EDIT & SAVE PROFILE (LEFT PANEL)
        editBtn.addEventListener("click", function () {
            inputs.forEach(input => input.disabled = false);
            editBtn.classList.add("d-none");
            saveBtn.classList.remove("d-none");
        });

        saveBtn.addEventListener("click", function () {
            const data = {
                _token: "{{ csrf_token() }}",
                nim: document.getElementById("nim").value,
                nama: document.getElementById("nama").value,
                password: document.getElementById("password").value,
                alamat: document.getElementById("alamat").value,
                no_telepon: document.getElementById("no_telepon").value,
                tanggal_lahir: document.getElementById("tanggal_lahir").value,
                email: document.getElementById("email").value,
            };

            
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    alert("Profil berhasil diperbarui!");
                    inputs.forEach(input => input.disabled = true);
                    saveBtn.classList.add("d-none");
                    editBtn.classList.remove("d-none");
                } else {
                    alert("Gagal menyimpan profil.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan.");
            });
        });
    });
</script>
@endsection