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
use App\Http\Service\AssigenTestService;

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

        return view('admin.assigenTest.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    /**
     * Вывести форму для создания нового ресурса.
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

        return view('admin.assigenTest.create', compact('users', 'themes', 'types'));
    }


    /**
     * Поместить только что созданный ресурс в хранилище.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'test_theme_id' => 'required|integer',
            'test_type_id' => 'required|integer',
            'user_id' => 'required|integer',
            'date_start' =>'required',
            'date_end' =>'required',
        ],
            [
                'test_theme_id.required' => 'Тематика теста должена быть заполнена',
                'test_theme_id.integer' => 'Тематика теста должена быть заполнена',
                'test_type_id.required' => 'Тип теста должена быть заполнена',
                'test_type_id.integer' => 'Тип теста должена быть заполнена',
                'date_start.required' => 'Дата начала теста должна быть заполнена',
                'date_end.required' => 'Дата окончания теста должна быть заполнена',
                'user_id.required' => 'Пользователь должен быть заполнен',
                'user_id.integer' => 'Пользователь должен быть заполнен'
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
        return view('admin.assigenTest.show',compact('data', 'tests'));
    }


    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
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

        return view('admin.assigenTest.edit',compact('test', 'users' , 'themes' ,'types'));
    }

    /**
     * Обновить указанный ресурс в хранилище
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
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

    /**
     * Все пройденные тесты
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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


    /**
     * Детали пройденного теста админу
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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

    //USER

    /**
     * Все тесты назначенные пользователю
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userTestAssign(Request $request)
    {
        $userId = Auth::id();

        $data = AssigenTest::orderBy('assigen_test.id','DESC')
            ->where('user_id' , '=' , $userId)
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type',
                'test_type.time_for_testing as time_for_testing',
                'test_type.questions_count as questions_count',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic'
            )
            ->where('date_done', '=', null)
            ->paginate(30);

        $data = $this->status_service->checkDateForStatus($data);

        return view('user.assigenTest.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    /**
     * Получить назнаенный тест для прохождение пользователем
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function takeTest($id)
    {
        $data = AssigenTest::where('assigen_test.id' , '=' , $id)
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.time_for_testing as time_for_testing'
            )
            ->get()
        ;
        $tests = AssigenTestQuestion::where('assigen_test_question.assigen_test_id' , '=' , $id )
            ->leftJoin('test', 'test.id', '=', 'assigen_test_question.question_id')
            ->select
            (
                'test.*'
            )
            ->get();

        if (!$this->status_service->checkTestAccess($data)) {
            return redirect()->back()
                ->with('warning', 'Выбраный вами  тест не доступен либо просрочен!');
        }

        if ($this->status_service->checkWasStart($data)){
            return redirect()->back()
                ->with('warning', 'Выбранный тест был начат ранее, но не был закончен, для переназначения теста обратитесь к Администратору сайта');
        }

        $test = AssigenTest::find($id);
        $test->update(['is_started'=>true]);

        return view('user.assigenTest.take',compact('data', 'tests'))
            ->with('i');
    }


    /**
     * сохранение результата тестирования
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveResult(Request $request, $id)
    {
        $input = $request->all();
        $test_time = $input['time_for_testing'];
        unset($input['time_for_testing']);
        unset($input['_token']);

        $this->status_service->saveAnswerToAssignTestQuestions($id,$input);
        $this->status_service->calculateTrueAnswers($id, $test_time);

        return redirect()->route('user.testassign')
            ->with('success', 'Тест завершен! Результаты тестирования находятся в вкладе "История тестирвоания"');
    }


    /**
     * История тестов Пользователя
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userTestsHistory(Request $request)
    {
        $userId = Auth::id();
        $data = AssigenTest::orderBy('assigen_test.date_done','DESC')
            ->leftJoin('test_theme', 'assigen_test.test_theme_id', '=', 'test_theme.id')
            ->leftJoin('test_type', 'assigen_test.test_type_id', '=', 'test_type.id')
            ->leftJoin('users', 'assigen_test.user_id', '=', 'users.id')
            ->select
            (
                'assigen_test.*',
                'test_theme.name as theme',
                'test_type.name as type',
                'test_type.questions_count as questions_count',
                'test_type.min_question_count as min_question_count',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.patronymic as patronymic'
            )
            ->where('date_done', '!=', null)
            ->where('user_id' , '=' , $userId)
            ->paginate(30);

        $data = $this->status_service->checkDateForStatus($data);

        return view('user.testsHistory.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    /**
     * Детали теста пройденного для пользователя
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userTestsHistoryShow($id)
    {
        $assign_test = new AssigenTestService();
        $data_temp = $assign_test->getTestHistory($id);

        $data = $data_temp['data'];
        $tests = $data_temp['tests'];

        return view('user.testsHistory.show',compact('data', 'tests'))
            ->with('i');
    }


    /**
     * Поиск назначеных тестов
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $data = AssigenTest::search($search);

        return view('admin.assigenTest.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    /**
     * Поиск истории
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function searchHistory(Request $request)
    {
        $search = $request->input('search');
        $data = AssigenTest::searchHistory($search);

        return view('admin.testsHistory.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }

    public function refreshTest($id)
    {
        $test = AssigenTest::find($id);

        $tests = Test::where('test_theme_id',$test->test_theme_id)
            ->pluck('question', 'id')->all();

        $test_type = TestType::find($test->test_type_id);
        $this->status_service->deleteAssigenTestQuestion($test->id);

        $this->status_service->AssigenTestQuestion($id, $test_type->questions_count, $tests );

        $test->update(['is_started' => false]);

        return redirect()->route('test-assign.index')
            ->with('success','Тест переназначен');
    }
}
