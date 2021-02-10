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
            vertical-align: middle!important;
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
        <div class="col-md">

            <table class="table table-striped table-bordered table-responsive-sm">
                <thead>
                <tr>
                    <th class="text-center">id</th>
                    <th class="text-center">Image</th>
                    <th class="text-center">Update</th>
                    <th class="text-center">Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach($images as $image)
                    <tr>
                        <td class="td-center">{{ $image->id }}</td>
                        <td class="td-center"><img src="{{url('/thumbnail_images/'. $image->name)}}" alt="My Image">
                        </td>
                        <td>
                            <form action="images/{{ $image->id }}" method="post" enctype="multipart/form-data">
                                <input name="_method" type="hidden" value="PUT">
                                @csrf
                                <div class="form-group">
                                    <label for="myfile">Select an image:</label>
                                    <input type="file" id="myfile" class="form-control" required name="image">
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </td>
                        <td class="td-center">
                            <button onclick="hapus({{ $image->id }})" class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
                success: function (result) {
                    // Do something with the result
                    if (result.status === 200) {
                        location.reload();
                    } else {
                        alert(result.message)
                    }
                }
            });
        }
    }
</script>
</body>
</html>
