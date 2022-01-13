@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Должности:</h2>
            </div>

            <div class="pull-right ml-3 mr-3">
                @can('position-create')
                    <a class="btn btn-success" href="{{ route('position.create') }}">
                        <i class="bi bi-plus-square"></i>
                    </a>
                @endcan
            </div>
            <div class="pull-right ml-3 mr-3">
                <form action="{{ route('positionsearch') }}"  method="get">
                    <input name="search"  placeholder="Искать здесь..." type="search" autocomplete="off">
                    <button type="submit" title="Искать...">
                        <i class="bi bi-search"></i>
                    </button>
                    <a  title="Отменить параметры поиска" href="{{ route('position.index') }}">
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
               <th>Должность</th>
               <th>Детали</th>
               <th width="280px">Действия</th>
           </tr>
           @foreach ($data as $position)
               <tr>
                   <td>{{ ++$i }}</td>
                   <td>{{ $position->name }}</td>
                   <td>{{ $position->detail }}</td>
                   <td>
                       <form action="{{ route('position.destroy',$position->id) }}" method="POST">
                           <a class="btn btn-info" href="{{ route('position.show',$position->id) }}">
                               <i class="bi bi-binoculars"></i>
                           </a>
                           @can('position-edit')
                               <a class="btn btn-primary" href="{{ route('position.edit',$position->id) }}">
                                   <i class="bi bi-pencil"></i>
                               </a>
                           @endcan

                           @csrf
                           @method('DELETE')
                           @can('position-delete')
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
        <p class="text-center text-danger">Пока нет ни одной должности!</p>
    @endif
       {!! $data->links() !!}

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
   @endsection
