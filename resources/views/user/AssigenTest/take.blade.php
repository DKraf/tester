@extends('layouts.app')

@section('content')
    @foreach ($data as $test_assign)
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left" id="end_time">
                <h2> Тема тестирования: {{ $test_assign->theme }}</h2>
            </div>
            <div class="pull-right pt-2 pb-2" id="time_for_testing_view" >
                До окончания теста осталось: {{ $test_assign->time_for_testing }}мин 00сек
            </div>
        </div>
    </div>

    @endforeach
    @if (sizeof($tests) > 0)
        <section id="user_start_testing" display="none">
        <form action="{{ route('user.test.result',$test_assign->id) }}" method="POST"  id="end_test_for_end_time">
            <input type="hidden" id="time_for_testing" name="time_for_testing" value="{{ $test_assign->time_for_testing }}">
            @foreach ($tests as $test)
                    <div class="pt-2 pb-2" >
                        <h6> {{ ++$i }}) {{ $test->question }} </h6>
                        <div class="form-check form-check-inline pt-2 pb-2">
                            <input class="form-check-input" type="radio" name="{{ $test->id }}" id="inlineRadio1" value="A">
                            <label class="form-check-label" for="inlineRadio1">{{ $test->A }}</label>
                        </div>
                        <div class="form-check form-check-inline pt-2 pb-2">
                            <input class="form-check-input" type="radio" name="{{ $test->id }}" id="inlineRadio2" value="B">
                            <label class="form-check-label" for="inlineRadio2">{{ $test->B }}</label>
                        </div>
                        <div class="form-check form-check-inline pt-2 pb-2">
                            <input class="form-check-input" type="radio" name="{{ $test->id }}" id="inlineRadio3" value="C" >
                            <label class="form-check-label" for="inlineRadio3">{{ $test->C }}</label>
                        </div>
                    </div>
                @endforeach
                    @csrf
                    <div class="form-row text-center pt-4 pb-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger pull-center">
                                Завершить тест
                            </button>
                        </div>
                    </div>

    @else
        <p class="text-center text-danger">Пока нет ни одного назначенного вопроса!</p>
    @endif
        </form>
        </section>
        <div class="form-row text-center pt-4 pb-4" id="test_start_button_div">
            <div class="col-12">
                <button class="btn btn-danger pull-center" id="button_test_starting">
                    Начать тест
                </button>
            </div>
        </div>
    <p class="text-center text-primary pt-4 pb-4"><small>	&#169 2021.  ТОО "Инженер-2015"</small></p>
@endsection
