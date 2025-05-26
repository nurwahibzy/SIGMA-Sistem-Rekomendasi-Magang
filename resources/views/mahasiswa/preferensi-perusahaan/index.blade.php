<form id="form-preferensi-perusahaan" action="{{ url('mahasiswa/profil/edit/preferensi/perusahaan') }}" method="POST">
    @csrf
    <div class="section-wrapper mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">Preferensi Perusahaan</h5>
            <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>

        <p class="text-muted mb-3">Preferensi perusahaan magang.</p>

        <div class="card mb-4">
            <div class="card-body">
                @forelse($jenis as $item)   
                    @php
                        $isChecked = collect($preferensi_perusahaan)->pluck('id_jenis')->contains($item->id_jenis);
                    @endphp
                    <input type="checkbox" id="jenis_{{ $item->id_jenis }}" name="id_jenis[]" value="{{ $item->id_jenis }}"
                        class="checkbox-input" {{ $isChecked ? 'checked' : '' }}>
                    <label for="jenis_{{ $item->id_jenis }}" class="checkbox-label">{{ $item->jenis }}</label>
                @empty
                    <p>Bidang tidak tersedia</p>
                @endforelse
            </div>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#form-preferensi-perusahaan").on('submit', function (e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil disimpan.'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan.'
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan.'
                    });
                }
            });
        });
    });
</script>