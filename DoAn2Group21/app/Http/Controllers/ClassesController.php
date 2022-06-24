<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as View;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class ClassesController extends Controller
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
        $subject = new Subjects();
        $subject_data = $subject::query()->get([
            'id',
            'name',
        ]);
        return view('classes.index', [
            'subject'=> $subject_data,
        ]);
    }

    public function api()
    {
        $query = Classes::query()
            ->addSelect('classes.*')
            ->addSelect('subjects.name')
            ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');
        
        return DataTables::of($query)
        ->addColumn('edit', function ($object) {
            return route('class.edit', $object);
        })
        ->addColumn('destroy', function ($object) {
            return route('class.destroy', $object);
        })
        ->make(true);
    }

    public function update( Classes $class)
    {
        // UpdateRequest $request,
        // $class->fill($request->validated());
        // $class->update();

        // return redirect()->route('classes.index')->with('message', 'Success!!!');
    }

    public function edit(Classes $class)
    {
        // return view('classes.edit')->with('subject', $class);
    }

    public function destroy(Classes $class)
    {   
        // $class->delete();
        // return redirect()->route('subject.index')->with('message', 'Success delete ' . $class->name .' !!!');
    }

    // public function store(StoreRequest $request): \Illuminate\Http\RedirectResponse
    // {
    //     $object = new Classes();
    //     $object->fill($request->validated());
    //     $object->save();

    //     return redirect()->route('subject.index')->with('message', 'Success');
    // }
}
