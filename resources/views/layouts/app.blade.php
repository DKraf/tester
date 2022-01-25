<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Токен CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <title>Инженер - 2015</title>
    <!-- Скрипты -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>

    <!-- Шрифты -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <!-- Стили -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="form-row center sidebar-heading border-bottom bg-light mr-0 pl-4 ">

                <img src={{asset('images/logo.png')}} width="75" height="75">
            </div>
            <div class="list-group list-group-flush">
                @role('Admin')
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/admin/edit-index"><i class="bi bi-sliders"></i> Настройка страницы</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/user/edit"><i class="bi bi-person-circle"></i> Настройка профиля</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/users"><i class="bi bi-people"></i> Пользователи</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/roles"><i class="bi bi-person-check"></i> Роли</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/position"><i class="bi bi-mortarboard"></i> Должности</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/company"><i class="bi bi-building"></i> Компании</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/test-type"><i class="bi bi-clipboard-data"></i> Виды тестов</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/test-theme"><i class="bi bi-patch-question"></i> Тематики</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/test-assign"><i class="bi bi-journal-plus"></i> Назначенные Тесты</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/admin/testhistory"><i class="bi bi-calendar-event"></i> История Тестирования</a>
                @endrole
                @role('User')
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/user/edit"><i class="bi bi-person-circle"></i> Настройка профиля</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/user/test-assign"><i class="bi bi-file-earmark-check"></i> Мои тесты</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3 " href="/user/tests-history"><i class="bi bi-file-earmark-bar-graph"></i> История тестирования</a>

                @endrole
            </div>
        </div>

        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-engeener border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-outline-light" id="sidebarToggle"><></button>
                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="navbar-collapse collapse" id="navbarSupportedContent" style="">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->last_name }} {{ ' '. Auth::user()->first_name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-left"></i> Выйти
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>
</div>
    </div>
</body>
</html>
