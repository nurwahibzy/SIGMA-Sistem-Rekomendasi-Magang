<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Import Data Mahasiswa dari Excel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Setup CSRF token untuk semua AJAX request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>
<body>
<div class="container mt-4">
    <h2>Import Data Mahasiswa dari Excel</h2>

    <div id="alert-success" class="alert alert-success" style="display:none;"></div>
    <div id="alert-error" class="alert alert-danger" style="display:none;"></div>

    <form id="importForm" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="file_excel" class="form-label">File Excel</label>
            <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls" required />
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>

<script>
$(document).ready(function(){
    $('#importForm').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: '{{ url('mahasiswa/tes') }}',  // Pastikan route ini ada dan sesuai
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                $('#alert-success').show().text(response.message);
                $('#alert-error').hide();
                $('#importForm')[0].reset();
            },
            error: function(xhr){
                let errors = xhr.responseJSON && xhr.responseJSON.errors;
                let errorText = 'Terjadi kesalahan!';
                if(errors){
                    errorText = '';
                    $.each(errors, function(key, value){
                        errorText += value[0] + '\n';
                    });
                }
                $('#alert-error').show().text(errorText);
                $('#alert-success').hide();
            }
        });
    });
});
</script>
</body>
</html>
