<!DOCTYPE html>
<html>
<head>
    <title>Form Wilayah Indonesia (AJAX Submit)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Pilih Provinsi dan Kota</h2>

    <form id="lokasi-form">
        <label for="provinsi">Provinsi:</label>
        <select name="provinsi" id="provinsi" required>
            <option value="">-- Pilih Provinsi --</option>
        </select>

        <br><br>

        <label for="daerah">Kota/Kabupaten:</label>
        <select name="daerah" id="daerah" required>
            <option value="">-- Pilih Kota/Kabupaten --</option>
        </select>

        <br><br>

        <button type="submit">Submit</button>
    </form>

    <div id="alert-message" style="margin-top: 20px;"></div>

    <script>
        const preferensiId = {{ $akun->mahasiswa->preferensi_lokasi_mahasiswa->id_preferensi_lokasi }};
        const updateUrl = `/mahasiswa/profil/edit/preferensi/lokasi/${preferensiId}`;

        // Isi dropdown provinsi dan kota
        $(document).ready(function() {
            $.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json', function(data) {
                $.each(data, function(i, prov) {
                    $('#provinsi').append($('<option>', {
                        value: prov.id,
                        text : prov.name
                    }));
                });
            });

            $('#provinsi').on('change', function() {
                var provId = $(this).val();
                $('#daerah').empty().append('<option value="">-- Pilih Kota/Kabupaten --</option>');

                if (provId) {
                    $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`, function(data) {
                        $.each(data, function(i, daerah) {
                            $('#daerah').append($('<option>', {
                                value: daerah.id,
                                text : daerah.name
                            }));
                        });
                    });
                }
            });

            // AJAX submit
            $('#lokasi-form').submit(function(e) {
                e.preventDefault();

                const provinsi = $('#provinsi option:selected').text();
                const daerah = $('#daerah option:selected').text();

                if (!provinsi || !daerah) {
                    $('#alert-message').html('<p style="color: red;">Provinsi dan Kota wajib diisi.</p>');
                    return;
                }

                $.ajax({
                    url: "{{ url('/mahasiswa/profil/edit/preferensi/lokasi/' . $akun->mahasiswa->preferensi_lokasi_mahasiswa->id_preferensi_lokasi ) }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'PUT',
                        provinsi: provinsi,
                        daerah: daerah
                    },
                    success: function(response) {
                        $('#alert-message').html('<p style="color: green;">Data berhasil disimpan!</p>');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        $('#alert-message').html('<p style="color: red;">Terjadi kesalahan saat menyimpan data.</p>');
                    }
                });
            });
        });
    </script>

</body>
</html>
