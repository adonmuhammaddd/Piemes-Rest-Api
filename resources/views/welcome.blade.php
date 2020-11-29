<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search</title>
    <link rel="stylesheet" href="{{asset('style/bootstrap.min.css')}}">
</head>
<body>
    <nav class="navbar navbar-light bg-light justify-content-between">
        <a class="navbar-brand">Navbar</a>
        <form class="form-inline">
          <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </nav>
      <div class="container">
            <h1>List Tempat Wisata</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tempat Wisata</th>
                        <th>Provinsi</th>
                        <th>Jumlah Pengunjung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attractions as $attraction)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$attraction->tempat_wisata}}</td>
                            <td>{{$attraction->provinsi}}</td>
                            <td>{{$attraction->jumlah_pengunjung}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
      </div>
<script src="{{asset('script/jquery-3.5.0.min.js')}}"></script>
<script src="{{asset('script/bootstrap.min.js')}}"></script>
</body>
</html>