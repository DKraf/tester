<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

/**
 * Class CompanyController
 * @package App\Http\Controllers
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class CompanyController extends Controller

{
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:company-list|company-create|company-edit|company-delete', ['only' => ['index','show']]);
        $this->middleware('permission:company-create', ['only' => ['create','store']]);
        $this->middleware('permission:company-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:company-delete', ['only' => ['destroy']]);
    }


    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Company::latest()->paginate(3);
        return view('admin.company.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }


    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.company.create');
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
            'bin' => 'required|min:12|max:12',
            'legal_address' => 'required',
            'tel_number' => 'required'
        ],
            [
                'name.required' => 'Наименование должно быть заполнено',
                'bin.required' => 'БИН должен быть заполнен',
                'legal_address.required' => 'Адрес должен быть заполнен',
                'tel_number.required' => 'Телефон должен быть заполнен',
                'bin.min' => 'БИН должен состоять не менее чем из 12 цифр',
                'bin.max' => 'БИН должен состоять не более чем из 12 цифр'
            ]
        );

        Company::create($request->all());

        return redirect()->route('company.index')
            ->with('success','Компания успешно добавлена.');
    }


    /**
     * Отобразить указанный ресурс.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('admin.company.show',compact('company'));
    }


    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('admin.company.edit',compact('company'));
    }


    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        request()->validate([
            'name' => 'required'
        ]);

        $company->update($request->all());

        return redirect()->route('company.index')
            ->with('success', 'Компания успешно обновлена');
    }


    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param Сompany $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Company::find($id)->delete();
        return redirect()->route('company.index')
            ->with('success', 'Компания успешно удалена');
    }


    /**
     * Поиск
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $data = Company::search($search);

        return view('admin.company.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 30);
    }
}
