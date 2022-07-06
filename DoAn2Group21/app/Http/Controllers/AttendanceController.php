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
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
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
        $class_sessions = Schedules::query()
            ->addSelect('schedules.*')
            ->where('subject_id', '=', $class->subject_id)
            ->get();
        return view('attendance.history')
        ->with('class_sessions', $class_sessions)
        ->with('class', $class);
    }


    public function attendance( $class_id , $schedule_id)
    {
        $students = ClassStudent::query()
            ->addSelect('users.id', 'users.name', 'class_students.*')
            ->leftJoin('users', 'class_students.user_id', 'users.id')
            ->where('class_id', '=', $class_id)
            ->get();
        $schedules = Schedules::query()
            ->addSelect('schedules.*')
            ->where('id', '=', $schedule_id)
            ->first();
        return view('attendance.attendance')
        ->with('students', $students)
        ->with('schedules', $schedules)
        ->with('class_id', $class_id);
    }

    public function store(Request $request)
    {
        try {
            $schedule_id = $request->schedule_id;
            $class_id = $request->class_id;
            $date = $request->date;
            $date = date('Y-m-d', strtotime($date));
            foreach ($request->attendance as $key => $value) {
                $find = Attendance::query()
                    ->addSelect('attendance.*')
                    ->where('user_id', $key)
                    ->where('schedule_id', $schedule_id)
                    ->where('class_id', $class_id)
                    ->count();

                if($find >= 1){
                    Attendance::where('user_id', $key)
                    ->where('schedule_id', $schedule_id)
                    ->where('class_id', $class_id)
                    ->update([
                        'status' => $value,
                        'date' => $date,
                    ]);
                }else{
                    Attendance::Create([
                        'class_id' => $class_id,
                        'user_id'=> $key,
                        'schedule_id'=> $schedule_id,
                        'status' => $value,
                        'date' => $date,
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
