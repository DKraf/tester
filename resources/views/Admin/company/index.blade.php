@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Компании:</h2>
            </div>
            <div class="pull-right ml-3 mr-3">
                @can('company-create')
                    <a class="btn btn-success" href="{{ route('company.create') }}">
                        <i class="bi bi-plus-square"></i>
                    </a>
                @endcan
            </div>
            <div class="pull-right ml-3 mr-3">
                <form action="{{ route('companysearch') }}"  method="get">
                    <input name="search"  placeholder="Искать здесь..." type="search" autocomplete="off">
                    <button type="submit" title="Искать...">
                        <i class="bi bi-search"></i>
                    </button>
                    <a  title="Отменить параметры поиска" href="{{ route('company.index') }}">
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
                <th>Наименование</th>
                <th>БИН</th>
                <th>Тел:</th>
                <th width="280px">Действия</th>
            </tr>
            @foreach ($data as $company)
               <tr>
                   <td>{{ ++$i }}</td>
                   <td>{{ $company->name }}</td>
                   <td>{{ $company->bin }}</td>
                   <td>{{ $company->tel_number }}</td>
                   <td>
                       <form action="{{ route('company.destroy',$company->id) }}" method="POST">
                           <a class="btn btn-info" href="{{ route('company.show',$company->id) }}">
                               <i class="bi bi-binoculars"></i>
                           </a>
                           @can('company-edit')
                               <a class="btn btn-primary" href="{{ route('company.edit',$company->id) }}">
                                   <i class="bi bi-pencil"></i>
                               </a>
                           @endcan
                           @csrf
                           @method('DELETE')
                           @can('company-delete')
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
        <p class="text-center text-danger">Пока нет ни одной компании!</p>
    @endif
       {!! $data->links() !!}
       <p class="text-center text"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
   @endsection
