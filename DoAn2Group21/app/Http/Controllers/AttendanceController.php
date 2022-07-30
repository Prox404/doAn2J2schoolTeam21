<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\ClassWeekday;
use App\Models\Schedules;
use App\Models\Subjects;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
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
        $subjects = Subjects::query()
        ->addSelect('subjects.*')
        ->get();

        return view('attendance.index')->with('subjects', $subjects);
    }

    public function api()
    {
        $query = Classes::query()
            ->addSelect('classes.*')
            ->addSelect('subjects.name as subject_name')
            ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');
        
        return DataTables::of($query)
        ->addColumn('history', function ($object) {
            return route('attendance.history', $object);
        })
        ->addColumn('destroy', function ($object) {
            return route('attendance.destroy', $object);
        })
        ->make(true);
    }

    public function history(Classes $class)
    {
        $schedules = Schedules::with('classes')->where('class_id', $class->id)->get();
        return view('attendance.history')
        ->with('schedules', $schedules)
        ->with('class', $class);
    }


    public function attendance( $class_id , $schedule_id)
    {
        $students = ClassStudent::query()
            ->addSelect('users.id', 'users.name', 'class_students.*')
            ->leftJoin('users', 'class_students.user_id', 'users.id')
            ->where('class_id', '=', $class_id)
            ->get();
        $schedules = Schedules::find($schedule_id);
        
        return view('attendance.attendance')
        ->with('students', $students)
        ->with('schedules', $schedules)
        ->with('class_id', $class_id);

        // return $schedules;
    }

    public function store(Request $request)
    {
        try {
            $schedule_id = $request->schedule_id;
            $class_id = $request->class_id;
            foreach ($request->attendance as $key => $value) {
                $find = Attendance::where('user_id', $key)
                    ->where('schedule_id', $schedule_id)
                    ->count();

                if($find >= 1){
                    Attendance::where('user_id', $key)
                    ->where('schedule_id', $schedule_id)
                    ->update([
                        'status' => $value,
                    ]);
                }else{
                    Attendance::Create([
                        'user_id'=> $key,
                        'schedule_id'=> $schedule_id,
                        'status' => $value,
                    ]);
                }
            }
            return redirect()->route('attendance.history', $class_id)->with('message', 'Success!!!');
        } catch (\Exception $e) {
            return redirect()->route('attendance.index')->with('message', 'Error!!! ' . $e->getMessage());
        }

        // return $request;
    }
}
