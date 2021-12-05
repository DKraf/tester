@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Типы тестов:</h2>
            </div>
            <div class="pull-right">
                @can('testtype-create')
                    <a class="btn btn-success" href="{{ route('test-type.create') }}">
                        <i class="bi bi-plus-square"></i>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    @if (sizeof($data) > 0)
       <table class="table table-bordered table-striped">
           <tr>
               <th>№</th>
               <th>Наименование</th>
               <th>Кол-во вопросов</th>
               <th>Время теста</th>
               <th width="280px">Действия</th>
           </tr>
           @foreach ($data as $test_type)
               <tr>
                   <td>{{ ++$i }}</td>
                   <td>{{ $test_type->name }}</td>
                   <td>{{ $test_type->questions_count }}</td>
                   <td>{{ $test_type->time_for_testing }}</td>
                   <td>
                       <form action="{{ route('test-type.destroy',$test_type->id) }}" method="POST">
                           <a class="btn btn-info" href="{{ route('test-type.show',$test_type->id) }}">
                               <i class="bi bi-binoculars"></i>
                           </a>
                           @can('testtype-edit')
                               <a class="btn btn-primary" href="{{ route('test-type.edit',$test_type->id) }}">
                                   <i class="bi bi-pencil"></i>
                               </a>
                           @endcan

                           @csrf
                           @method('DELETE')
                           @can('testtype-delete')
                               <button type="submit" class="btn btn-danger">
                                   <i class="bi bi-x-circle"></i>
                               </button>
                           @endcan
                       </form>
                   </td>
               </tr>
           @endforeach
       </table>
    @else
        <p class="text-center text-danger">Пока нет ни одного типа теста!</p>
    @endif
       {!! $data->links() !!}

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
   @endsection
