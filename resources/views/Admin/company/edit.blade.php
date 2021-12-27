@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Редактирование компании:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('company.index') }}">
                    <i class="bi bi-arrow-return-left"></i></a>
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

    <form action="{{ route('company.update',$company->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Наименование:</strong>
                    <input type="text" name="name" value="{{ $company->name }}" class="form-control" placeholder="Наименование">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>БИН:</strong>
                    <input type="text" class="form-control"  name="bin" placeholder="БИН" value="{{ $company->bin }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Адрес:</strong>
                    <input type="text" class="form-control"   name="legal_address" placeholder="Адрес" value="{{ $company->legal_address }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Телефон:</strong>
                    <input type="text" class="form-control"   name="tel_number" placeholder="Телефон"  value="{{ $company->tel_number }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>

    </form>

    <p class="text-center text-primary"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
