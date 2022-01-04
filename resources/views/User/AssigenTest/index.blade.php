@extends('layouts.app')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @elseif($message = Session::get('warning'))
        <div class="alert alert-warning">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Назначенные тестирования:</h2>
            </div>
        </div>
    </div>
    @if (sizeof($data) > 0)
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
           <tr>
               <th>№</th>
               <th>Тематика</th>
               <th>Время на тестирование</th>
               <th>Колличество вопросов</th>
               <th>Доступен</th>
               <th>Статус</th>
               <th width="280px">Пройти тестирование</th>
           </tr>
           @foreach ($data as $test_assign)
               <tr>
                   <td>{{ ++$i }}</td>
                   <td>{{ $test_assign->theme }} </td>
                   <td>{{ $test_assign->time_for_testing }} мин. </td>
                   <td>{{ $test_assign->questions_count }} </td>
                   <td>с {{ $test_assign->date_start }} по {{ $test_assign->date_end }} </td>
                   <td>{{ $test_assign->status }} </td>
                   <td>
                       <a class="btn btn-success" href="{{ route('user.test.take',$test_assign->id) }}">
                           <i class="bi bi-play-btn"></i>
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
