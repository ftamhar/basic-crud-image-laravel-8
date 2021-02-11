<table class="table table-striped table-bordered table-responsive-sm">
    <thead>
    <tr>
        <th class="text-center">No.</th>
        <th class="text-center">Image</th>
        <th class="text-center">Update</th>
        <th class="text-center">Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php $no =1 ?>
    @foreach($images as $image)
        <tr>
            <td class="td-center">{{ $no }}</td>
            <td class="td-center"><img src="{{url('/thumbnail_images/'. $image->name)}}" alt="My Image">
            </td>
            <td>
                <form action="{{ route('images.update', $image->id) }}" method="post" enctype="multipart/form-data">
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
        <?php $no++ ?>
    @endforeach
    </tbody>
</table>
