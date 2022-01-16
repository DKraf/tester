@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Информация о типе теста:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('test-type.index') }}">
                    <i class="bi bi-arrow-return-left"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Наименование:</strong>
                {{ $test_type->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Кол-во вопросов:</strong>
                {{ $test_type->questions_count }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Мин. кол-во правильных ответов:</strong>
                {{ $test_type->min_question_count }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Время теста:</strong>
                {{ $test_type->time_for_testing }}
            </div>
        </div>
    </div>
    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
