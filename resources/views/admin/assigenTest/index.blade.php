@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Назначенные тестирования:</h2>
            </div>
            <div class="pull-right ml-3 mr-3">
                @can('assigntest-create')
                    <a class="btn btn-success" href="{{ route('test-assign.create') }}">
                        <i class="bi bi-plus-square"></i>
                    </a>
                @endcan
            </div>
            <div class="pull-right ml-3 mr-3">
                <form action="{{ route('testassignsearch') }}"  method="get">
                    <input name="search"  placeholder="Искать здесь..." type="search" autocomplete="off">
                    <button type="submit" title="Искать...">
                        <i class="bi bi-search"></i>
                    </button>
                    <a  title="Отменить параметры поиска" href="{{ route('test-assign.index') }}">
                        <i class="bi bi-backspace"></i>
                    </a>
                </form>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if (sizeof($data) > 0)
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
           <tr>
               <th>№</th>
               <th>ФИО</th>
               <th>Тематика</th>
               <th>Тип</th>
               <th>Доступен</th>
               <th>Статус</th>
               <th width="280px">Действия</th>
           </tr>
           @foreach ($data as $test_assign)
               <tr>
                   <td>{{ ++$i }}</td>
                   <td>{{ $test_assign->last_name }} {{ $test_assign->first_name }} {{ $test_assign->patronymic }}</td>
                   <td>{{ $test_assign->theme }} </td>
                   <td>{{ $test_assign->type }} </td>
                   <td>c {{ $test_assign->date_start }} по {{ $test_assign->date_end }} </td>
                   <td>{{ $test_assign->status }} </td>

                   <td>
                       <form action="{{ route('test-assign.destroy',$test_assign->id) }}" method="POST">
                           <a class="btn btn-info" href="{{ route('test-assign.show',$test_assign->id) }}">
                               <i class="bi bi-binoculars"></i>
                           </a>
                           @can('assigntest-edit')
                               <a class="btn btn-primary" href="{{ route('test-assign.edit',$test_assign->id) }}">
                                   <i class="bi bi-pencil"></i>
                               </a>
                               @if($test_assign->status == 'Начат ранее')
                                   <a class="btn btn-success" href="{{ route('refreshtest',$test_assign->id) }}">
                                       <i class="bi bi-arrow-clockwise"></i>
                                   </a>
                                   @endif
                           @endcan

                           @csrf
                           @method('DELETE')
                           @can('assigntest-delete')
                               <button type="submit" class="btn btn-danger">
                                   <i class="bi bi-x-circle"></i>
                               </button>
                           @endcan
                       </form>
                   </td>
               </tr>
           @endforeach
        </table>
        </div>
    @else
        <p class="text-center text-danger">Пока нет ни одного назначенного теста!</p>
    @endif
       {!! $data->render() !!}

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
   @endsection
