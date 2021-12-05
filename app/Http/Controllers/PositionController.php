<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:position-list|position-create|position-edit|position-delete', ['only' => ['index','show']]);
        $this->middleware('permission:position-create', ['only' => ['create','store']]);
        $this->middleware('permission:position-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:position-delete', ['only' => ['destroy']]);
    }


    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data = Position::latest()->paginate(5);
        return view('Position.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    /**
     * Отобразить форму для создания нового ресурса.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('position.create');
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
            'name' => 'required'
        ]);

        Position::create($request->all());

        return redirect()->route('position.index')
            ->with('Должность успешно создана');
    }

    /**
     * Отобразить указанный ресурс.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        return view('position.show',compact('position'));
    }


    /**
     * Отобразить форму для редактирования указанного
     * ресурса.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {

        return view('position.edit',compact('position'));
    }


    /**
     * Обновить указанный ресурс в хранилище.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $position
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Position $position)
    {
        request()->validate([
            'name' => 'required'
        ]);

        $position->update($request->all());

        return redirect()->route('position.index')
            ->with('Должность успешно обновлена');
    }

    /**
     * Удалить указанный ресурс из хранилища.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('position.index')
            ->with('Должность успешно удалена');
    }
}
