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
        return view('admin.homePage.index', compact('data'));

    }


    public function update(Request $request)
    {

        $image1 = $image2 = $image3 = null;
        $path = 'storage/';
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
        $request['image1'] = ($image1) ? $path.$image1 : null;
        $request['image2'] = ($image2) ? $path.$image2 : null;
        $request['image3'] = ($image3) ? $path.$image3 : null;

        $save_data = array_filter($request);

        $home_page = HomePage::find(1);

        $home_page->update(($save_data));
        return redirect()->route('showindex')
            ->with('success', 'Компания успешно обновлена');
    }

    public function show()
    {
        $data = HomePage::first();
        return view('welcome.index', compact('data'));
    }

    private function uploadImage($img_obj, $name_it)
    {
        $name = $name_it;
        $extension = $img_obj->getClientOriginalExtension();
        $filename = $name . '.' . $extension;
        $img_obj->storeAs('public', $filename);
        return $filename;
    }


}
