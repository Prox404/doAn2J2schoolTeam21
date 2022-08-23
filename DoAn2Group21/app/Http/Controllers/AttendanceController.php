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

    public function findValueById($object, $id, $id_field, $field)
    {
        foreach ($object as $key => $value) {
            if ($value->$id_field == $id) {
                return $value->$field;
            }
        }
    }

    public function history(Classes $class)
    {
        $schedules = Schedules::with('classes')->where('class_id', $class->id)->get();
        
        $schedule_id = [];
        foreach ($schedules as $key => $value) {
            $schedule_id[] = $value->id;
        }

        $attendance = Attendance::query()
            ->addSelect('attendances.*')
            ->addSelect('users.name as name')
            ->leftJoin('users', 'attendances.user_id', 'users.id')
            ->whereIn('schedule_id', $schedule_id)
            ->get();

        
        $all_students = [];
        $students_absent = [];
        $students_present = [];
        $attendances = [];
        foreach ($attendance as $key => $value) {
            if ($value->status == 2) {
                $students_absent[] = $value->user_id;
            }
            if ($value->status == 1) {
                $students_present[] = $value->user_id;
            }
            $all_students[$value->user_id] = $value->name;
            $attendances[$value->schedule_id][] = $value->status;
        }

        $students_absent = array_count_values($students_absent);
        $students_present = array_count_values($students_present);

        $students_absent_array = [];
        
        foreach ($students_absent as $key => $value) {
            if($value > 3){
                $students_absent_array[] = [
                    'id' => $key,
                    'name' => $this->findValueById($attendance, $key, 'user_id', 'name'),
                    'absent' => $value,
                ];
            }
        }
        $students_present_array = [];
        foreach ($students_present as $key => $value) {
            if($value == count($attendances)){
                $students_present_array[] = [
                    'id' => $key,
                    'name' => $this->findValueById($attendance, $key, 'user_id', 'name'),
                    'present' => $value,
                ];
            }
        }

        $attendances_data = [];
        foreach ($attendances as $key => $value) {
            $date = $this->findValueById($schedules, $key, 'id', 'date');
            $attendances_data[$date] = array_count_values($value);
        }


        $line_chart_labels = [];
        foreach($schedules as $schedule) {
            $line_chart_labels[] = $schedule->date;
        }

        $line_chart_data = [];
        foreach($line_chart_labels as $label) {
            if(in_array($label, array_keys($attendances_data))) {
                foreach($attendances_data as $key => $value) {
                    if($key === $label) {
                        if(!empty($value[1])) {
                            $present = $value[1];
                        } else {
                            $present = 0;
                        }
                        if(!empty($value[2])) {
                            $absent = $value[2];
                        } else {
                            $absent = 0;
                        }
                        if(!empty($value[3])) {
                            $onLeave = $value[3];
                        } else {
                            $onLeave = 0;
                        }
                        $line_chart_data[] = [
                            'present' => $present,
                            'absent' => $absent,
                            'onLeave' => $onLeave,
                        ];
                    }
                }
            } else {
                $line_chart_data[] = [
                    'present' => 0,
                    'absent' => 0,
                    'onLeave' => 0,
                ];
            }
        }

        $absent_students = [];
        $present_students = [];
        $onLeave_students = [];
        foreach ($line_chart_data as $data) {
            $absent_students[] = $data['absent'];
            $present_students[] = $data['present'];
            $onLeave_students[] = $data['onLeave'];  
        }


        return view('attendance.history')
        ->with('schedules', $schedules)
        ->with('class', $class)
        ->with('numberStudentAbsentMoreThan3Sessions', $students_absent_array)
        ->with('numberStudentPresentFullDay', $students_present_array)
        ->with('allStudent', $all_students)
        ->with('line_chart_labels', $line_chart_labels)
        ->with('numberStudentPresent', $present_students)
        ->with('numberStudentAbsent', $absent_students)
        ->with('numberStudentOnLeave', $onLeave_students);

        // return $students_present_array;
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
