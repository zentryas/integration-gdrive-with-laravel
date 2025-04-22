<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Testing</title>
  </head>
  <body>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow my-5">
                    <div class="card-body">
                        <form action="{{ route('file.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                        
                            <div class="form-group my-3">
                                <label for="">Nama File</label>
                                <input type="text" name="nama_file" class="form-control">
                            </div>
                            <div class="form-group my-3">
                                <label for="">File</label>
                                <input type="file" name="file" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary my-3">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-3">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                @foreach($data as $key)
                                    <tr>
                                        <td>{{ $key->file_id }}</td>
                                        <td>{{ $key->nama_file }}</td>
                                        <td>
                                            <a href="file/{{ $key->file_id }}" class="btn btn-danger btn-sm">Download</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>