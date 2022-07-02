<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subjects\StoreRequest;
use App\Http\Requests\Subjects\UpdateRequest;
use App\Models\Subjects;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as View;
use Yajra\DataTables\DataTables;

class SubjectsController extends Controller
{

    public function __construct()
    {
        $routeName = Route::currentRouteName();
        $arr         = explode('.', $routeName);
        $arr         = array_map('ucfirst', $arr);
        $title       = implode(' / ', $arr);
        View::share('title', $title);
    }

    public function index()
    {
        return view('subjects.index');
    }

    public function api()
    {
        $query = Subjects::query()
            ->addSelect('subjects.*')
            ->addSelect([
                'class_count' => Classes
                    ::selectRaw('count(*)')
                    ->whereColumn('classes.subject_id', 'subjects.id')
            ])
            ->leftJoin('classes', 'subjects.id', 'classes.subject_id')
            ->groupBy('subjects.id');
        
        return DataTables::of($query)
        ->addColumn('edit', function ($object) {
            return route('subject.edit', $object);
        })
        ->addColumn('destroy', function ($object) {
            return route('subject.destroy', $object);
        })
        ->make(true);
    }

    public function update(UpdateRequest $request, Subjects $subject)
    {
        $subject->fill($request->validated());
        $subject->update();

        return redirect()->route('subject.index')->with('message', 'Success!!!');
    }

    public function edit(Subjects $subject)
    {
        return view('subjects.edit')->with('subject', $subject);
    }

    public function destroy(Subjects $subject)
    {   
        $subject->delete();
        return redirect()->route('subject.index')->with('message', 'Success delete ' . $subject->name .' !!!');
    }

    public function store(StoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $object = new Subjects();
        $object->fill($request->validated());
        $object->save();

        return redirect()->route('subject.index')->with('message', 'Success');
    }
}
