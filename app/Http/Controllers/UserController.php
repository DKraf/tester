<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Position;
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
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
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
        return view('users.create',compact('roles','position','company'));
    }

    /**
     * Поместить только что созданный ресурс в хранилище.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required',
            'position_id' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
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

        ]
        );

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success','User created successfully');
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
        return view('users.show',compact('user'));
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

        return view('users.edit',compact('user','roles','userRole','position','company'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|same:confirm-password',
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
            ->with('success','User deleted successfully');
    }
}
