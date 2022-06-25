<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\ClassWeekday;
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
            ->addSelect('subjects.name as subject_name')
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
        // dd($class);
        $subject = new Subjects();
        $subject_data = $subject::query()->get([
            'id',
            'name',
        ]);

        $weekdays = ClassWeekday::query()
            ->addSelect('class_weekdays.weekday_id, class_weekdays.shift')
            ->where('class_id', $class->id);

        return view('classes.edit',[
            'class' => $class,
            'subject' => $subject_data,
            'weekdays' => $weekdays,
        ]);
    }

    public function userApi(Classes $class)
    {
        $query = ClassStudent::query()
            ->select('class_students.*')
            ->addSelect('users.name as username')
            ->where('class_id', $class->id)
            ->leftJoin('users', 'users.id', 'user_id');
        return DataTables::of($query)
            ->addColumn('edit', function ($object) {
                return route('user.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('user.destroy', $object);
            })
            ->make(true);
    }

    public function destroy(Classes $class)
    {   
        // $class->delete();
        // return redirect()->route('subject.index')->with('message', 'Success delete ' . $class->name .' !!!');
    }

    public function store(Request $request)
    {
        // $object = new Classes();
        // $object->fill($request->validated());
        // $object->save();

        $name = $request->name;
        $subject_id = $request->subject;
        $weekdays = $_POST['weekday'];
        $shift = $request->shift;

        $number_weekday = count($weekdays);

        $class = new Classes();
        $class->name = $name;
        $class->subject_id = $subject_id;
        $class->save();
        $insertedId = $class->id;

        for ($i=0; $i < $number_weekday; $i++) {
            $class_weekday = new ClassWeekday();
            $class_weekday->class_id = $insertedId;
            $class_weekday->shift = $shift;
            $class_weekday->weekday_id = $weekdays[$i];
            $class_weekday->save();
        }

        // return $insertedId;
        return redirect()->route('subject.index')->with('message', 'Success add ' . $name .' !!!');
    }
}
