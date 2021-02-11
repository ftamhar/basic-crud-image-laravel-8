<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .td-center {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
    <title>Upload Image</title>
</head>
<body>
<div class="container">
    <div class="row mb-5 pt-5">
        <div class="col-md">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="myfile">Select an image:</label>
                    <input type="file" id="myfile" class="form-control" required name="image">
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md" id="for-table">

            @include('images_table')

        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    const hapus = (id) => {
        let conf = confirm("Yakin akan menghapus data?");
        if (conf) {
            $.ajax({
                url: '{!! url('images') !!}/' + id,
                type: 'DELETE',
                data: {"_token": "{{ csrf_token() }}"},
                success: function (data, textStatus, request) {
                    // Do something with the result
                    if (request.status === 200) {
                        $.get("{!! route('images.table') !!}", function (data, status) {
                            alert('Data berhasil dihapus');
                            $('#for-table').html(data);
                        });
                    } else if (request.status === 204) {
                        alert("Data tidak ditemukan")
                    } else {
                        alert(data.message)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr);
                    alert(thrownError);
                }
            });
        }
    }
</script>
</body>
</html>
