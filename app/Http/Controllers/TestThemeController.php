<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Models\TestTheme;

/**
 * Class TestThemeController
 * @package App\Http\Controllers
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class TestThemeController extends Controller

{
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:testtheme-list|testtheme-create|testtheme-edit|testtheme-delete', ['only' => ['index','show']]);
        $this->middleware('permission:testtheme-create', ['only' => ['create','store']]);
        $this->middleware('permission:testtheme-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:testtheme-delete', ['only' => ['destroy']]);
    }


    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TestTheme::latest()->paginate(30);
        return view('admin.testTheme.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }


    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.testTheme.create');
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
        ],
            [
                'name.required' => 'Наименование должно быть заполнено',
            ]
        );

        TestTheme::create($request->all());

        return redirect()->route('test-theme.index')
            ->with('success', 'Тематика тестов успешно добавлена.');
    }


    /**
     * Отобразить указанный ресурс.
     *
     * @param Request $request
     * @param TestTheme $test_theme
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, TestTheme $test_theme)
    {
        $data = Test::orderBy('id','DESC')
            ->where('test_theme_id',$test_theme->id)
            ->paginate(30);
        return view('admin.testTheme.show',compact('test_theme', 'data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param TestTheme $test_theme
     * @return \Illuminate\Http\Response
     */
    public function edit(TestTheme $test_theme)
    {
        return view('admin.testTheme.edit',compact('test_theme'));
    }


    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param \Illuminate\Http\Request $request
     * @param TestTheme $test_theme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestTheme $test_theme)
    {
        request()->validate([
            'name' => 'required'
        ],
            [
                'name.required' => 'Тематика должна быть заполнена'
            ]
        );

        $test_theme->update($request->all());

        return redirect()->route('test-theme.index')
            ->with('success','Тематика теста успешно обновлен');
    }


    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param TestTheme $test-theme
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestTheme $test_theme)
    {
        $test_theme->delete();

        return redirect()->route('test-theme.index')
            ->with('success','Тематика теста успешно удален');
    }


    /**
     * Поиск
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $data = TestTheme::search($search);

        return view('admin.testTheme.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }

}
