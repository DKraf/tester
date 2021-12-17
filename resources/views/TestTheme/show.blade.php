@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="text-center">
                <h2> Тематика "{{ $test_theme->name }}" </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('test-theme.index') }}">
                    <i class="bi bi-arrow-return-left"></i>
                </a>
            </div>
        </div>
        <div class="pull-left">
            <h3> Вопросы: </h3>
        </div>
    </div>
    <div class="pull-right">
        @can('test-create')
            <a class="btn btn-success" href="{{ route('createCustom', $test_theme->id) }}">
                <i class="bi bi-plus-square"></i>
            </a>
        @endcan
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
                   <th>Вопрос</th>
                   <th>Вариант А</th>
                   <th>Вариант B</th>
                   <th>Вариант C</th>
                   <th width="280px">Действия</th>
               </tr>
               @foreach ($data as $test)
                   <tr>
                       <td>{{ ++$i }}</td>
                       <td>{{ $test->question }}</td>
                       <td>{{ $test->A }}</td>
                       <td>{{ $test->B }}</td>
                       <td>{{ $test->C }}</td>
                       <td>
                           <form action="{{ route('test.destroy',$test->id) }}" method="POST">
                               <a class="btn btn-info" href="{{ route('test.show',$test->id) }}">
                                   <i class="bi bi-binoculars"></i>
                               </a>
                               @can('test-edit')
                                   <a class="btn btn-primary" href="{{ route('test.edit',$test->id) }}">
                                       <i class="bi bi-pencil"></i>
                                   </a>
                               @endcan
                               @csrf
                               @method('DELETE')
                               @can('test-delete')
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
        {{-- Pagination --}}
        <div class="pagination justify-content-center">
            {!! $data->links() !!}
        </div>
    @else
        <p class="text-center text-danger">Пока нет ни одного теста!</p>
    @endif


    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
