<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
     * Отобразить список ресурсов
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('users.id','DESC')
            ->leftJoin('position', 'users.position_id', '=', 'position.id')
            ->leftJoin('company', 'users.company_id', '=', 'company.id')
            ->select('users.*', 'position.name as position_name', 'company.name as company_name')
            ->paginate(30);
        return view('admin.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    /**
     * Вывести форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        $position = Position::pluck('name','id')->toArray();;
        $company = Company::pluck('name','id')->all();
        $pass = uniqid();
        return view('admin.users.create',compact('roles','position','company','pass'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required',
            'position_id' => 'required',
            'login' => 'required|unique:users,login',
            'roles' => 'required'
        ],
        [
            'first_name.required' => 'Имя должно быть заполнено',
            'last_name.required' => 'Фамилия должна быть заполненна',
            'company_id.required' => 'Компания не выбранна',
            'position_id.required' => 'Должность не выбранна',
            'login.required' => 'login не заполнен',
            'email.unique' => 'email существует',
            'login.unique' => 'Указанный login уже существует',
            'password.required' => 'Пароль не заполнен',
            'password.same' => 'Пароли не совпадают',
            'roles.required' => 'Роль не выбранна',

        ]
        );

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','Пользователь успешно создан');
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $position = Position::find($user->position_id);
        $company = Company::find($user->company_id);

        return view('admin.users.show',compact('user', 'position', 'company'));
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
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $position = Position::pluck('name','id')->all();
        $company = Company::pluck('name','id')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('admin.users.edit',compact('user','roles','userRole','position','company'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'roles' => 'required'
        ],
            [
                'first_name.required' => 'Имя должно быть заполнено',
                'last_name.required' => 'Фамилия должна быть заполненна',
                'company_id.required' => 'Компания не выбранна',
                'position_id.required' => 'Должность не выбранна',
                'email.required' => 'email не заполнен',
                'email.email' => 'email не соответсвует формату',
                'email.unique' => 'Указанный Email уже существует',
                'password.required' => 'Пароль не заполнен',
                'password.same' => 'Пароли не совпадают',
                'roles.required' => 'Роль не выбранна',

            ]);

        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        if (isset($input['email'])) {
            $flag = User::where('email', $input['email'])->get()->toArray();
            if (sizeof($flag)) {
                return redirect()->route('users.index')
                    ->with('warning', 'Ошибка редактирования пользователя, пользователь с таким email существует');
            }
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','Пользователь успешно обнавлен!');
    }


    /**
     * Удалить указанный ресурс из хранилища
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','Пользователь успешно удален');
    }


    /**
     * Поиск
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $data = User::search($search);

        return view('admin.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 30);
    }


    /**
     * Редактирование профиля вьюха
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userEdit()
    {
        $userId = Auth::id();

        $user = User::find($userId);
        $position = Position::pluck('name','id')->all();
        $company = Company::pluck('name','id')->all();


        return view('user.users.edit',compact('user','position','company'));
    }

    /**
     * Редактирвоание профиля
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function userChange(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required',
            'position_id' => 'required',
        ],
            [
                'first_name.required' => 'Имя должно быть заполнено',
                'last_name.required' => 'Фамилия должна быть заполненна',
                'company_id.required' => 'Компания не выбранна',
                'position_id.required' => 'Должность не выбранна',
                'email.required' => 'email не заполнен',
                'email.email' => 'email не соответсвует формату',
                'email.unique' => 'Указанный Email уже существует',
            ]);

        $input = $request->all();

        if (isset($input['email'])) {
            $flag = User::where('email', $input['email'])->get()->toArray();
            if (sizeof($flag)) {
                return redirect()->route('user.edit')
                    ->with('warning', 'Ошибка редактирования профиля, пользователь с таким email существует');
            }
        }

        $user = User::find($id);
        $user->update($input);

        return redirect()->route('user.edit')
            ->with('success','Пользователь успешно обнавлен!');
    }


    /**
     * Смена пароля вьюха
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function changePassword()
    {
        return view('user.users.editpassword');
    }


    /**
     * Редактирование пароля
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editUserPassword(Request $request)
    {
        $this->validate($request, [

            'password' => 'required|same:confirm-password|min:6',
            'old-password' => 'required|password'
        ],
            [
                'password.required' => 'Пароль не заполнен',
                'old-password.password' => 'Текущий пароль указан не верно',
                'password.min' => 'Пароль должен быть не менее 6 символов',
                'password.same' => 'Пароли не совпадают',
                'old-password.required' => 'Старый пароль не указан',

            ]
        );
        $userId = Auth::id();
        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::find($userId);
        $user->update([
            'password'=> $input['password']
        ]);

        return redirect()->route('user.edit')
            ->with('success','Пароль успешно изменен!');
    }


    /**
     * Сброс пароля на стандартный
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword($id)
    {
        $user = User::find($id);
        $user->update([
            'password'=> Hash::make('1q2w3e4r5t')
        ]);

        return redirect()->route('users.show', $id)
            ->with('success','Пароль успешно сброшен на стандартный');
    }
}
