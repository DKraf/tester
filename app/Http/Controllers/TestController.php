<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

/**
 * Class TestController
 * @package App\Http\Controllers
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class TestController extends Controller

{
    /**
     * Отобразить список ресурсов
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
  //
    }


    /**
     * Вывести форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function createCustom($id)
    {
        return view('admin.test.create',compact('id'));
    }


    /**
     * Поместить только что созданный ресурс в хранилище.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'test_theme_id' => 'required',
            'question' => 'required',
            'answer_true' => 'required',
            'A' => 'required',
            'B' => 'required',
            'C' => 'required'
        ],
            [
                'test_theme_id.required' => 'Тематика теста потерялась',
                'question.required' => 'Вопрос должен быть заполнен',
                'answer_true.required' => 'Правильный ответ должен быть заполнен',
                'A.required' => 'Вариант ответ А - должен быть заполнен',
                'B.required' => 'Вариант ответ B - должен быть заполнен',
                'C.required' => 'Вариант ответ C - должен быть заполнен'
            ]
        );

        $input = $request->all();

        Test::create($input);

        return redirect()->route('test-theme.show', $request->test_theme_id)
            ->with('success','Тест создан');
    }


    /**
     * Отобразить указанный ресурс.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $test = Test::find($id);

        return view('admin.test.show',compact('test'));
    }


    /**
     * Отобразить форму для редактирования указанногоресурса.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $test = Test::find($id);

        return view('admin.test.edit',compact('test'));
    }


    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'test_theme_id' => 'required',
            'question' => 'required',
            'answer_true' => 'required',
            'A' => 'required',
            'B' => 'required',
            'C' => 'required'
        ],
            [
                'test_theme_id.required' => 'Тематика теста потерялась',
                'question.required' => 'Вопрос должен быть заполнен',
                'answer_true.required' => 'Правильный ответ должен быть заполнен',
                'A.required' => 'Вариант ответ А - должен быть заполнен',
                'B.required' => 'Вариант ответ B - должен быть заполнен',
                'C.required' => 'Вариант ответ C - должен быть заполнен'
            ]);

        $input = $request->all();

        $test = Test::find($id);
        $test->update($input);

        return redirect()->route('test-theme.show',$request->test_theme_id)
            ->with('success','Тест успешно обнавлен!');
    }


    /**
     * Удалить указанный ресурс из хранилища
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Test::find($id)->delete();
        return redirect()->route('test-theme.index')
            ->with('success','Тест удален Успешно');
    }
}
