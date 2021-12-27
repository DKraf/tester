<?php

namespace App\Http\Controllers;

use App\Models\AssigenTest;
use App\Models\AssigenTestQuestion;
use App\Models\Test;
use App\Models\TestTheme;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\TestService;

/**
 * Class TestController
 * @package App\Http\Controllers
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class AssigenTestController extends Controller
{
    /**
     * @var TestService
     */
    private $status_service;

    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:assigntest-list|assigntest-create|assigntest-edit|assigntest-delete', ['only' => ['index','show']]);
        $this->middleware('permission:assigntest-create', ['only' => ['create','store']]);
        $this->middleware('permission:assigntest-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:assigntest-delete', ['only' => ['destroy']]);
        $this->status_service = new TestService();
    }
    /**
     * Отобразить список ресурсов
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $data = AssigenTest::orderBy('assigen_test.id','DESC')
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic'
            )
            ->where('date_done', '=', null)
            ->paginate(30);

        $data = $this->status_service->checkDateForStatus($data);

        return view('admin.assigentest.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }

    /**
     * Вывести форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $users = User::orderBy('users.id','DESC')
            ->leftJoin('position', 'users.position_id', '=', 'position.id')
            ->leftJoin('company', 'users.company_id', '=', 'company.id')
            ->select('users.*', 'position.name as position_name', 'company.name as company_name')
        ->get();
        $themes= TestTheme::orderBy('id','DESC')->get();
        $types = TestType::orderBy('id','DESC')->get();

        return view('admin.assigentest.create', compact('users', 'themes', 'types'));
    }


    /**
     * Поместить только что созданный ресурс в хранилище.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'test_theme_id' => 'required',
            'test_type_id' => 'required',
            'user_id' => 'required',
            'date_start' =>'required',
            'date_end' =>'required',

        ],
            [
                'test_theme_id.required' => 'Тематика теста должена быть заполнена',
                'test_type_id.required' => 'Тип теста должена быть заполнена',
                'date_start.required' => 'Дата начала теста должна быть заполнена',
                'date_end.required' => 'Дата окончания теста должна быть заполнена',
                'user_id.required' => 'Пользователь должен быть заполнен'
            ]
        );

        $input = $request->all();
        $test_type= TestType::find($input['test_type_id']);
        $tests = Test::where('test_theme_id',$input['test_theme_id'])
            ->pluck('question', 'id')->all();

        if (sizeof($tests) < $test_type->questions_count) {
            return redirect()->back()
                ->with('warning', 'Колличество вопросов в тесте не соответсвует Типу');
        }

        $id = AssigenTest::create($input);
        $this->status_service->AssigenTestQuestion($id->id, $test_type->questions_count, $tests );
        return redirect()->route('test-assign.index')
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
        $status_service = new TestService();

        $data = AssigenTest::orderBy('assigen_test.id','DESC')->where('assigen_test.id', '=', $id)
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->leftJoin('company', 'company.id', '=', 'users.company_id')
            ->leftJoin('position', 'position.id', '=', 'users.position_id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type_name',
                'test_type.questions_count as questions_count',
                'test_type.min_question_count as min_question_count',
                'test_type.time_for_testing as time_for_testing',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic',
                'position.name as position_name',
                'company.name as company_name'

            )
            ->get();

        $tests = AssigenTestQuestion::where('assigen_test_question.assigen_test_id' , '=' , $id )
            ->leftJoin('test', 'test.id', '=', 'assigen_test_question.question_id')
            ->select
            (
                'test.question as question_name',
                'test.id as question_id',
            )
            ->get();

        $data = $status_service->checkDateForStatus($data);
        return view('admin.assigentest.show',compact('data', 'tests'));
    }

    /**
     * Отобразить форму для редактирования указанного

     * ресурса.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $test = AssigenTest::find($id);

        $users = User::orderBy('users.id','DESC')
            ->leftJoin('position', 'users.position_id', '=', 'position.id')
            ->leftJoin('company', 'users.company_id', '=', 'company.id')
            ->select('users.*', 'position.name as position_name', 'company.name as company_name')
            ->get();
        $themes= TestTheme::orderBy('id','DESC')->get();
        $types = TestType::orderBy('id','DESC')->get();

        return view('admin.assigentest.edit',compact('test', 'users' , 'themes' ,'types'));
    }

    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'test_theme_id' => 'required',
            'test_type_id' => 'required',
            'user_id' => 'required',
            'date_start' =>'required',
            'date_end' =>'required',

        ],
            [
                'test_theme_id.required' => 'Тематика теста должена быть заполнена',
                'test_type_id.required' => 'Тип теста должена быть заполнена',
                'date_start.required' => 'Дата начала теста должна быть заполнена',
                'date_end.required' => 'Дата окончания теста должна быть заполнена',

                'user_id.required' => 'Пользователь должен быть заполнен'
            ]
        );

        $input = $request->all();

        $test = AssigenTest::find($id);
        $test->update($input);

        return redirect()->route('test-assign.index')
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
        AssigenTestQuestion::where('assigen_test_id', $id)->delete();
        AssigenTest::find($id)->delete();
        return redirect()->route('test-assign.index')
            ->with('Тест удален Успешно');
    }

    public function testsHistory(Request $request)
    {
        $data = AssigenTest::orderBy('assigen_test.date_done','DESC')
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic'
            )
            ->where('date_done', '!=', null)
            ->paginate(30);

        $data = $this->status_service->checkDateForStatus($data);

        return view('admin.testsHistory.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    public function testsHistoryShow($id)
    {
        $status_service = new TestService();

        $data = AssigenTest::orderBy('assigen_test.id','DESC')->where('assigen_test.id', '=', $id)
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->leftJoin('company', 'company.id', '=', 'users.company_id')
            ->leftJoin('position', 'position.id', '=', 'users.position_id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type_name',
                'test_type.questions_count as questions_count',
                'test_type.min_question_count as min_question_count',
                'test_type.time_for_testing as time_for_testing',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic',
                'position.name as position_name',
                'company.name as company_name'

            )
            ->get();

        $tests = AssigenTestQuestion::where('assigen_test_question.assigen_test_id' , '=' , $id )
            ->leftJoin('test', 'test.id', '=', 'assigen_test_question.question_id')
            ->select
            (
                'test.question as question_name',
                'test.id as question_id',
                'test.A as A',
                'test.B as B',
                'test.C as C',
                'test.answer_true as answer_true',
                'assigen_test_question.selected_answer as selected_answer'
            )
            ->get();

        $data = $status_service->checkDateForStatus($data);
        return view('admin.testsHistory.show',compact('data', 'tests'));
    }
}
