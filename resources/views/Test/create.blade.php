@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Добавить тест:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('test-theme.show', $id) }}">
                    <i class="bi bi-arrow-return-left"></i>
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Упс!</strong> Ошибка Ввода!!!<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('test.store') }}" method="POST">
        @csrf
        <input type="hidden" value="{{$id}}" name="test_theme_id" class="form-control" placeholder="Вопрос">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Вопрос:</strong>
                    <input type="text" name="question" class="form-control" placeholder="Вопрос">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Правильный ответ:</strong>
                    <select class="form-control" name="answer_true" aria-label="Default select example">
                        <option selected>Выберите правильный ответ</option>
                        <option value="A">Вариант А</option>
                        <option value="B">Вариант B</option>
                        <option value="C">Вариант C</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Вариант А:</strong>
                    <input type="text" name="A" class="form-control" placeholder="Вопрос">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Вариант B:</strong>
                    <input type="text" name="B" class="form-control" placeholder="Вопрос">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Вариант C:</strong>
                    <input type="text" name="C" class="form-control" placeholder="Вопрос">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>

    </form>

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
