<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestType;

class TestTypeController extends Controller

{
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:testtype-list|testtype-create|testtype-edit|testtype-delete', ['only' => ['index','show']]);
        $this->middleware('permission:testtype-create', ['only' => ['create','store']]);
        $this->middleware('permission:testtype-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:testtype-delete', ['only' => ['destroy']]);
    }
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TestType::latest()->paginate(30);
        return view('testtype.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }

    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('testtype.create');
    }

    /**
     * Поместить только что созданный ресурс в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'questions_count' => 'required|min:1',
            'min_question_count' => 'required',
            'time_for_testing' => 'required'
        ],
            [
                'name.required' => 'Наименование должно быть заполнено',
                'questions_count.required' => 'Колличество вопросов должно быть указанно',
                'min_question_count.required' => 'Минимальное колличество правильных ответов должно быть указанно',
                'time_for_testing.required' => 'Время прохождения тестов должно быть заполнено',
                'questions_count.min' => 'Количество вопросов в тесте должно быть не менее 1',
                'min_question_count.min' => 'Минимальное колличество правильных ответов в тесте должно быть не менее 1'
            ]
        );

        TestType::create($request->all());

        return redirect()->route('testtype.index')
            ->with('Тип тестов успешно добавлен.');
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param TestType $test_type
     * @return \Illuminate\Http\Response
     */
    public function show(TestType $test_type)
    {
        return view('testtype.show',compact('test_type'));
    }

    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param TestType $test_type
     * @return \Illuminate\Http\Response
     */
    public function edit(TestType $test_type)
    {
        return view('testtype.edit',compact('test_type'));
    }


    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param \Illuminate\Http\Request $request
     * @param TestType $test_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestType $test_type)
    {
        request()->validate([
            'name' => 'required',
            'questions_count' => 'required|min:1',
            'min_question_count' => 'required',
            'time_for_testing' => 'required'
        ],
            [
                'name.required' => 'Наименование должно быть заполнено',
                'questions_count.required' => 'Колличество вопросов должно быть указанно',
                'min_question_count.required' => 'Минимальное колличество правильных ответов должно быть указанно',
                'time_for_testing.required' => 'Время прохождения тестов должно быть заполнено',
                'questions_count.min' => 'Количество вопросов в тесте должно быть не менее 1',
                'min_question_count.min' => 'Минимальное колличество правильных ответов в тесте должно быть не менее 1'
            ]
        );

        $test_type->update($request->all());

        return redirect()->route('company.index')
            ->with('Тип теста успешно обновлен');
    }

    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param Сompany $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestType $test_type)
    {
        $test_type->delete();

        return redirect()->route('testtype.index')
            ->with('Тип теста успешно удален');
    }
}
