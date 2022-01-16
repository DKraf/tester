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
                <h2>Пользователи:</h2>
            </div>

            <div class="pull-right ml-3 mr-3">
                <a class="btn btn-success" href="{{ route('users.create') }}">
                    <i class="bi bi-plus-square"></i>
                </a>
            </div>
            <div class="pull-right ml-3 mr-3">
                <form action="{{ route('usersearch') }}"  method="get">
                    <input name="search"  placeholder="Искать здесь..." type="search" autocomplete="off">
                    <button type="submit" title="Искать...">
                        <i class="bi bi-search"></i>
                    </button>
                    <a  title="Отменить параметры поиска" href="{{ route('users.index') }}">
                        <i class="bi bi-backspace"></i>
                    </a>
                </form>
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
            <th>ФИО</th>
            <th>Login</th>
            <th>Email</th>
            <th>Компания</th>
            <th>Должность</th>
            <th width="280px">Действие</th>
        </tr>
        @foreach ($data as $key => $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->last_name }} {{ $user->first_name }} {{ $user->patronymic }}</td>
                <td>{{ $user->login}}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->company_name }}</td>
                <td>{{ $user->position_name  }}</td>
                <td>
                    <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                        <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}">
                            <i class="bi bi-binoculars"></i>
                        </a>
                        <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger  btn-sm">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
    {!! $data->render() !!}

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
