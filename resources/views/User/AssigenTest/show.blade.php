@extends('layouts.app')

@section('content')
    @foreach ($data as $test_assign)
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Информация о назначеном тесте:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('test-assign.index') }}">
                    <i class="bi bi-arrow-return-left"></i>
                </a>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Назначен на:</strong>
                    {{ $test_assign->last_name }} {{ $test_assign->first_name }} {{ $test_assign->patronymic }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Должность:</strong>
                    {{ $test_assign->position_name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Компания:</strong>
                    {{ $test_assign->company_name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Тематика:</strong>
                    {{ $test_assign->theme }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Тип теста:</strong>
                    {{ $test_assign->type_name }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Кол-во вопросов:</strong>
                    {{ $test_assign->questions_count }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Мин. кол-во правильных ответов:</strong>
                    {{ $test_assign->min_question_count }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Время теста:</strong>
                    {{ $test_assign->time_for_testing }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Тест доступен:</strong>
                    с
                    {{ $test_assign->date_start }}
                     по
                    {{ $test_assign->date_end }}

                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Статус теста:</strong>
                    {{ $test_assign->status }}
                </div>
            </div>
        </div>
    @endforeach
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Вопросы в тесте:</h2>
            </div>
        </div>
    </div>
    @if (sizeof($tests) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Номер вопроса</th>
                    <th>Содержание вопроса</th>
                </tr>
                @foreach ($tests as $test)
                    <tr>
                        <td>{{ $test->question_id }} </td>
                        <td>{{ $test->question_name }} </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <p class="text-center text-danger">Пока нет ни одного назначенного вопроса!</p>
    @endif
    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
