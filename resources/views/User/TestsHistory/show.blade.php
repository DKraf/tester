@extends('layouts.app')

@section('content')
    @foreach ($data as $test_assign)
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Информация о пройденом тесте:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('user.testhistory') }}">
                    <i class="bi bi-arrow-return-left"></i>
                </a>
            </div>
        </div>
    </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>ФИО тестируемого:</strong>
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
                    <strong>Всего отвечено правильно на:</strong>
                    {{ $test_assign->true_answer_count }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Затраченное время на тестирование:</strong>
                    {{ $test_assign->time_spent }} мин.
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Дата сдачи теста:</strong>
                    {{ $test_assign->date_done }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Результат:</strong>
                    @if(!empty($test_assign->true_answer_count) &&  ((int)$test_assign->true_answer_count >= (int)$test_assign->min_question_count))
                        Сдал
                    @else
                        Не сдал
                    @endif
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
                    <th>Вариант А</th>
                    <th>Вариант B</th>
                    <th>Вариант C</th>
                    <th>Выбранный ответ</th>
                </tr>
                @foreach ($tests as $test)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $test->question_name }} </td>
                        <td>{{ $test->A }} </td>
                        <td>{{ $test->B }} </td>
                        <td>{{ $test->C }} </td>
                        <td style="
                        @if($test->answer_true == $test->selected_answer) background-color:#aef5ae
                        @else background-color:#ffb4b4
                        @endif ">{{ $test->selected_answer }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <p class="text-center text-danger">Почему то нет вопросов, что то не так !!! Свяжтесь с администратором!!!</p>
    @endif
    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
