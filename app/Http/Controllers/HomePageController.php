<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use Illuminate\Http\Request;

/**
 * Class CompanyController
 * @package App\Http\Controllers
 * @author [Kravchenko Dmitriy => RedHead-DEV]
 */
class HomePageController extends Controller

{
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:homepage-list', ['only' => 'index']);
        $this->middleware('permission:homepage-edit', ['only' => 'update']);

    }


    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = HomePage::first();
        return view('admin.homePage.index',compact('data'));
//
//        return view('admin.homePage.index',compact('data'))
//            ->with('i', (request()->input('page', 1) - 1) * 30);
    }


    public function update(Request $request)
    {

        $image = null;
        if (isset($request->image)) {
            $name = 'image';
            $extension = $request->image->getClientOriginalExtension();
            $filename = $name . '.' . $extension;
            $image = $request->image->storeAs('public', $filename);
        }
        $request = $request->all();
        $request['image'] = $image;
        $save_data = array_filter($request);

        $home_page = HomePage::find(1);

        $home_page->update(($save_data));
        return redirect()->route('/')
            ->with('success', 'Компания успешно обновлена');
    }


}
