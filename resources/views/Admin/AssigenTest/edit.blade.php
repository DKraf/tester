@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Редактирование назначеного теста:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('test-assign.index') }}">
                    <i class="bi bi-arrow-return-left"></i>
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Упс!</strong> Ошибка Ввода!!!<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('test-assign.update',$test->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Пользователь:</strong>
                    <select name="user_id" class="form-select" aria-label="Default select example">
                        @foreach ($users as $user)
                            <option @if($test->user_id == $user->id) selected @endif value={{$user->id}}>
                                {{$user->last_name}} {{$user->first_name}} {{$user->patronymic}}
                                @if(isset($user->company_name))
                                    , {{$user->company_name}}
                                @endif
                                @if(isset($user->position_name))
                                    ,  {{$user->position_name}}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Тематика:</strong>
                    <select name="test_theme_id" class="form-select" aria-label="Default select example">
                        @foreach ($themes as $them)
                            <option @if($test->test_theme_id == $them->id) selected @endif value={{$them->id}}>{{$them->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Тип:</strong>
                    <select name="test_type_id" class="form-select" aria-label="Default select example">
                        <option>Выберите тип теста </option>
                        @foreach ($types as $type)
                            <option  @if($test->test_type_id == $type->id) selected @endif  value={{$type->id}}>
                                {{$type->name}}
                                @if(isset($type->questions_count))
                                    , кол-во вопросов: {{$type->questions_count}}
                                @endif
                                @if(isset($type->time_for_testing))
                                    , время теста: {{$type->time_for_testing}}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Начало досутпа -
                        <input name="date_start" type="date" id="start"  value="{{$test->date_start}}" style="border: 1px solid #ced4da">
                    </strong>
                </div>
                <div class="form-group">
                    <strong> Окончание доступа -
                        <input name="date_end" type="date" id="start"  value="{{$test->date_end}}" style="border: 1px solid #ced4da">
                    </strong>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
