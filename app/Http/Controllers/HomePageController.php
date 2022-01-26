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

        $image1 = $image2 = $image3 = null;

        if (isset($request->image1)) {
            $name = 'image1';
            $image1 = $this->uploadImage($request->image1, $name);
        }
        if (isset($request->image2)) {
            $name = 'image2';
            $image2 = $this->uploadImage($request->image2, $name);
        }
        if (isset($request->image3)) {
            $name = 'image3';
            $image3 = $this->uploadImage($request->image3, $name);
        }

        $request = $request->all();
        $request['image1'] = $image1;
        $request['image2'] = $image2;
        $request['image3'] = $image3;

        $save_data = array_filter($request);

        $home_page = HomePage::find(1);

        $home_page->update(($save_data));
        return redirect()->route('/')
            ->with('success', 'Компания успешно обновлена');
    }

    private function uploadImage($img_obj, $name_it)
    {
        $name = $name_it;
        $extension = $img_obj->getClientOriginalExtension();
        $filename = $name . '.' . $extension;
        return $img_obj->storeAs('storage', $filename);
        return $img_obj->storeAs('storage', $filename);
    }


}
