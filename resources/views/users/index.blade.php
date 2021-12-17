@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Пользователи:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('users.create') }}">
                    <i class="bi bi-plus-square"></i>
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
<div class="table-responsive">
    <table class="table  table-bordered table-striped">
        <tr>
            <th>№</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Отчество</th>
            <th>Email</th>
            <th>Компания</th>
            <th>Должность</th>
            <th width="280px">Действие</th>
        </tr>
        @foreach ($data as $key => $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->patronymic }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->company_name }}</td>
                <td>{{ $user->position_name  }}</td>
                <td>
                    <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}">
                        <i class="bi bi-binoculars"></i>
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}">
                        <i class="bi bi-pencil"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                    {!! Form::button('<i class="bi bi-x-circle"></i>', ['class' => 'btn btn-danger btn-sm']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>
</div>
    {!! $data->render() !!}

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
