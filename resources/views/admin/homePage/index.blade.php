<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Токен CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <title>Инженер - 2015</title>
    <!-- Скрипты -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/homestyles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
    <body>
    <form action="{{ route('updateindex') }}" enctype="multipart/form-data" method="POST">
    @csrf
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <a class="navbar-brand" href="#!">Инженер-2015</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Главная</a></li>
                        <li class="nav-item"><a class="nav-link" href="/home">Кабинет тестирований</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 align-items-center my-5">
                <div class="col-lg-7"><img class="img-fluid rounded mb-4 mb-lg-0" src="{{asset('images/900x400.png')}}" alt="..." /></div>
                <div class="col-lg-5">
                    <input type="text" name="h1" value="{{ $data->h1 }}" class="form-control" >
                    <input type="text" name="t1" value="{{ $data->t1 }}" class="form-control" >
                </div>
            </div>
            <div class="card text-white bg-secondary my-5 py-4 text-center">
                <div class="card-body"><input type="text" name="t2" value="{{ $data->t2 }}" class="form-control" >
                </div>
            </div>
            <div class="row gx-4 gx-lg-5">
                <div class="col-md-4 mb-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <input type="text" name="h3" value="{{ $data->h3 }}" class="form-control" >
                            <input type="file" name="image1" value="{{ $data->image1 }}" class="form-control">
                            <input type="text" name="t3" value="{{ $data->t3 }}" class="form-control" >
                        </div>

                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <input type="text" name="h4" value="{{ $data->h4 }}" class="form-control" >
                            <input type="file" name="image2" value="{{ $data->image2 }}" class="form-control">
                            <input type="text" name="t4" value="{{ $data->t4 }}" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <input type="text" name="h5" value="{{ $data->h5 }}" class="form-control" >
                            <input type="file" name="image3" value="{{ $data->image3 }}" class="form-control">
                            <input type="text" name="t5" value="{{ $data->t5 }}" class="form-control" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <input type="text" name="fh1" value="{{ $data->fh1 }}" class="form-control" >
                    <input type="text" name="f1" value="{{ $data->f1 }}" class="form-control" >
                </div>
                <div class="col-md-4 mb-5">
                    <div class="container px-4 px-lg-5"><p class="m-0 text-center text-white">&copy;2021 Инженер-2015</p></div>
                </div>
                <div class="col-md-4 mb-5">
                    <input type="text" name="fh2" value="{{ $data->fh2 }}" class="form-control" >
                    <input type="text" name="f2" value="{{ $data->f2 }}" class="form-control" >
                </div>
            </div>
        </footer>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    </body>
</html>
