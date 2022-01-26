<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Инженер - 2015</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Сертифицированный учебный центр 'Инженер 2015', оказывает услуги по тестированию сотрудников" />
        <meta name="author" content="Kravchenko Dmitriy DKRAF-DEV" />
        <meta name="robots" content="index, follow" />
        <meta name="keywords" content="Инженер 2015, injener2015, тестирование сотрудников, сертификация, подтверждение квалификации, обучение по промышленной безопасности, профессиональная переподготовка, обучение по промышленной безопасности, учебный центр Караганда" />

        <!-- Токен CSRF -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
        <!-- Скрипты -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/popper.min.js') }}" defer></script>
        <script src="{{ asset('js/custom.js') }}" defer></script>

        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('css/homestyles.css') }} rel="stylesheet" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    </head>
    <body>
        <!-- Responsive navbar-->
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
                    <h1 class="font-weight-light">{{$data->h1}}</h1>
                    <p>{{ $data->t1 }}</p>
                </div>
            </div>
            <!-- Call to Action-->
            <div class="card text-white bg-secondary my-5 py-4 text-center">
                <div class="card-body"><p class="text-white m-0">{{ $data->t2 }}</p></div>
            </div>
            <!-- Content Row-->
            <div class="row gx-4 gx-lg-5">
                <div class="col-md-4 mb-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="card-title">{{ $data->h3 }}</h2>
                            <img id="myImg1" src={{asset('storage/image1.jpg')}}  style="width:100%;max-width:300px">
                            <p class="card-text">{{ $data->t3 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="card-title">{{ $data->h4 }}</h2>
                            <img id="myImg2" src={{asset('storage/image2.jpg')}}  style="width:100%;max-width:300px">
                            <p class="card-text">{{ $data->t4 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="card-title">{{ $data->h5 }}</h2>
                            <img id="myImg3" src={{asset('storage/image3.jpg')}}  style="width:100%;max-width:300px">
                            <p class="card-text">{{ $data->t5 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <div class="container px-4 px-lg-5"><p class="m-0 text-center text-white-50 ">{{ $data->fh1 }}</p></div>
                    <div class="container px-4 px-lg-5"><p class="m-0 text-center text-gray-200 small">{{ $data->f1 }}</p></div>

                </div>
                <div class="col-md-4 mb-5">
                    <div class="container px-4 px-lg-5"><p class="m-0 text-center text-white">&copy;2021 Инженер-2015</p></div>

                </div>
                <div class="col-md-4 mb-5">
                    <div class="container px-4 px-lg-5"><p class="m-0 text-center text-white-50">{{ $data->fh2 }}</p></div>
                    <div class="container px-4 px-lg-5"><p class="m-0 text-center text-gray-200 small">{{ $data->f2 }}</p></div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
{{--        <script src="js/scripts.js"></script>--}}
    </body>


    <div id="myModal1" class="modal">
        <span id="span1" class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <div id="myModal2" class="modal">
        <span id="span2" class="close">&times;</span>
        <img class="modal-content" id="img02">
        <div id="caption1"></div>
    </div>

    <div id="myModal3" class="modal">
        <span id="span3" class="close">&times;</span>
        <img class="modal-content" id="img013">
        <div id="caption2"></div>
    </div>
</html>
