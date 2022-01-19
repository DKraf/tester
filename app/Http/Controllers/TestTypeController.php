<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestType;

/**
 * Class TestTypeController
 * @package App\Http\Controllers
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
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
        return view('admin.testType.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }


    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.testType.create');
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
            'min_procent' => 'required',
            'time_for_testing' => 'required'
        ],
            [
                'name.required' => 'Наименование должно быть заполнено',
                'questions_count.required' => 'Колличество вопросов должно быть указанно',
                'min_procent.required' => 'Минимальное процент правильных ответов должен быть указан',
                'time_for_testing.required' => 'Время прохождения тестов должно быть заполнено',
                'questions_count.min' => 'Количество вопросов в тесте должно быть не менее 1',
                'min_question_count.min' => 'Минимальное колличество правильных ответов в тесте должно быть не менее 1'
            ]
        );
        $input = $request->all();
        if ($input['questions_count'] < 2) {
            return redirect()->route('test-type.create')
                ->with('warning','Ошибка сохранения! Колличество вопросов должно быть не менее 2');
        }
        $input['min_question_count'] = ($input['questions_count'] / 100 ) * $input['min_procent'];
        TestType::create($input);

        return redirect()->route('test-type.index')
            ->with('success','Тип тестов успешно добавлен.');
    }


    /**
     * Отобразить указанный ресурс.
     *
     * @param TestType $test_type
     * @return \Illuminate\Http\Response
     */
    public function show(TestType $test_type)
    {
        $test_type['min_procent'] = ($test_type['questions_count'] / $test_type['min_question_count']) * 100;

        return view('admin.testType.show',compact('test_type'));
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
        $test_type['min_procent'] = ($test_type['min_question_count'] / $test_type['questions_count'])  * 100 ;
        return view('admin.testType.edit',compact('test_type'));
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
            'min_procent' => 'required',
            'time_for_testing' => 'required'
        ],
            [
                'name.required' => 'Наименование должно быть заполнено',
                'questions_count.required' => 'Колличество вопросов должно быть указанно',
                'min_procent.required' => 'Минимальное процент правильных ответов должен быть указан',
                'time_for_testing.required' => 'Время прохождения тестов должно быть заполнено',
                'questions_count.min' => 'Количество вопросов в тесте должно быть не менее 1',
                'min_question_count.min' => 'Минимальное колличество правильных ответов в тесте должно быть не менее 1'
            ]
        );
        $save_data = $request->all();
        if ($save_data['questions_count'] < 2) {
            return redirect()->route('test-type.edit',$test_type->id)
                ->with('warning','Ошибка сохранения! Колличество вопросов должно быть не менее 2');
        }
        $save_data['min_question_count'] =  ($save_data['questions_count'] / 100 ) * $save_data['min_procent'];

        $test_type->update($save_data);

        return redirect()->route('test-type.index')
            ->with('success','Тип теста успешно обновлен');
    }


    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param TestType $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestType $test_type)
    {
        $test_type->delete();

        return redirect()->route('test-type.index')
            ->with('success', 'Тип теста успешно удален');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $data = TestType::search($search);

        return view('admin.testType.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }
}
