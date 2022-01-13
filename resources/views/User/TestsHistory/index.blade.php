@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>История прохождения тестирований:</h2>
            </div>
        </div>
    </div>
    @if (sizeof($data) > 0)
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
           <tr>
               <th>№</th>
               <th>ФИО</th>
               <th>Тематика</th>
               <th>Кол-во вопросов</th>
               <th>Мин. кол-во ответов</th>
               <th>Отвечено правильно</th>
               <th>Дата прохождения</th>
               <th>Затраченное время</th>
               <th>Результат</th>
               <th width="280px">Действия</th>
           </tr>
           @foreach ($data as $test_assign)
               <tr>
                   <td>{{ ++$i }}</td>
                   <td>{{ $test_assign->last_name }} {{ $test_assign->first_name }} {{ $test_assign->patronymic }}</td>
                   <td>{{ $test_assign->theme }} </td>
                   <td>{{ $test_assign->questions_count }} </td>
                   <td>{{ $test_assign->min_question_count }} </td>
                   <td>{{ $test_assign->true_answer_count }} </td>
                   <td> {{ $test_assign->date_done }} </td>
                   <td>{{ $test_assign->time_spent }} мин. </td>
                   <td> @if(!empty($test_assign->true_answer_count) &&  ((int)$test_assign->true_answer_count >= (int)$test_assign->min_question_count))
                           Сдал
                       @else
                           Не сдал
                       @endif</td>
                   <td>
                       <a class="btn btn-info" href="{{ route('user.testhistoryshow',$test_assign->id) }}">
                           <i class="bi bi-binoculars"></i>
                       </a>
                       <a class="btn btn-success" href="{{ route('downloadResult',$test_assign->id) }}">
                           <i class="bi bi-download"></i>
                       </a>
                   </td>
               </tr>
           @endforeach
        </table>
        </div>
    @else
        <p class="text-center text-danger">Пока нет ни одного назначенного теста!</p>
    @endif
       {!! $data->links() !!}

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
   @endsection
