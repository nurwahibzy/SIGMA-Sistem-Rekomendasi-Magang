<!-- DataTables CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        let table = $('#aktivitasTable').DataTable({
            responsive: true
        });

        $('#uploadForm').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '{{ url("/mahasiswa/aktivitas/$id_magang/tambah") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res.success) {
                        $('#result').html(`<div class="alert alert-success">Aktivitas berhasil ditambahkan!</div>`);

                        // Ambil data dari form
                        const keterangan = $('#keterangan').val();
                        const fileInput = $('input[name="file"]')[0];
                        const file = fileInput.files[0];
                        const fileName = file ? file.name : '-';

                        // Asumsikan format tanggal lokal
                        const today = new Date().toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });

                        // Generate row baru
                        const newRow = table.row.add([
                            table.rows().count() + 1,
                            keterangan,
                            file ? `<span class="text-muted">${fileName}</span>` : '-',
                            today
                        ]).draw(false).node();

                        $(newRow).addClass('table-success');

                        // Reset form & tutup modal
                        $('#uploadForm')[0].reset();
                        $('#modalTambah').modal('hide');
                    } else {
                        $('#result').html(`<div class="alert alert-danger">Gagal menyimpan aktivitas.</div>`);
                    }
                }
            });
        });
    });
</script>