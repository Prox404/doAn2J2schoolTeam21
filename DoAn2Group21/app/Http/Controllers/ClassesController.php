<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\ClassWeekday;
use App\Models\Schedules;
use App\Models\Subjects;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\View as View;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;

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

    public function test()
    {
        return view('classes.test');
    }

    public function index()
    {
        $subject = new Subjects();
        $subject_data = $subject::query()->get([
            'id',
            'name',
        ]);
        return view('classes.index', [
            'subject' => $subject_data,
        ]);
    }

    public function api()
    {
        $query = Classes::query()
            ->addSelect('classes.*')
            ->addSelect('subjects.name as subject_name')
            ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');

        return DataTables::of($query)
            ->editColumn('weekdays', function ($object) {
                if(!empty($object->weekdays)) {
                    $weekdayName = [];
                    foreach($object->weekdays as $weekday) {
                        switch($weekday){
                            case 1:
                                $weekdayName[] = 'T2';
                                break;
                            case 2:
                                $weekdayName[] = 'T3';
                                break;
                            case 3:
                                $weekdayName[] = 'T4';
                                break;
                            case 4:
                                $weekdayName[] = 'T5';
                                break;
                            case 5:
                                $weekdayName[] = 'T6';
                                break;
                            case 6:
                                $weekdayName[] = 'T7';
                                break;
                            case 7:
                                $weekdayName[] = 'CN';
                                break;
                        }
                    }
                    return implode(', ', $weekdayName);
                }else {
                    return 'Chưa cập nhật';
                }
            })
            ->editColumn('shift', function ($object) {
                if(!empty($object->shift)) {
                    if($object->shift == 1) {
                        return 'Sáng';
                    }else {
                        return 'Chiều';
                    }
                }else{
                    return 'Chưa cập nhật';
                }
            })
            ->addColumn('numberOfLessonsLearned', function ($object) {
                $schedules = Schedules::where('class_id', $object->id)->get();
                // $attendance = 0;
                $schedule_id = [];
                foreach($schedules as $schedule) {
                    $schedule_id[] = $schedule->id;
                }
                $attendance = Attendance::whereIn('schedule_id', $schedule_id)->distinct()->get('schedule_id')->count();
                return $attendance . '/'. count($schedule_id)  ;
            })
            ->addColumn('expectedEndDate', function ($object) {
                $schedules = Schedules::where('class_id', $object->id)->get();
                // $attendance = 0;
                $schedule_id = [];
                foreach($schedules as $schedule) {
                    $schedule_id[] = $schedule->id;
                }
                $attendance = Attendance::whereIn('schedule_id', $schedule_id)->distinct()->get('schedule_id');
                $remainingDays = count($schedule_id) - count($attendance);
                $lastSchedule = $attendance->last();
                $lastScheduleDate = Schedules::where('id', $lastSchedule['schedule_id'])->orderBy('date', 'desc')->first()['date'];  
                $currentDate = Carbon::parse($lastScheduleDate)->addDays(1);

                $weekdays = $object->weekdays;

                $numberFailDate = 0;
                $numberSuccessDate = 0;
                while ($numberFailDate < 365) {
                    if($numberSuccessDate <= $remainingDays){
                        if(in_array($currentDate->isoFormat('E'), $weekdays)){
                            $numberSuccessDate++;
                        }else{
                            $numberFailDate++;
                        }
                        $currentDate->addDays(1);
                    }else{
                        break;
                    }
                }

                return $currentDate->format('Y-m-d');
                // return $attendance;
            })
            ->addColumn('teacher', function ($object) {
                $teacher = ClassStudent::query()
                    ->addSelect('users.name as teacher')
                    ->leftJoin('users', 'class_students.user_id', 'users.id')
                    ->where('class_students.class_id', $object->id)
                    ->where('users.level', 2)
                    ->first();

                if (!empty($teacher->teacher)) {
                    return [
                        'name' => $teacher,
                        'status' => 1,
                    ];
                } else {
                    return [
                        'href' => route('class.addTeacher', $object),
                        'status' => 404,
                    ];
                }
            })
            ->addColumn('edit', function ($object) {
                return route('class.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('class.destroy', $object);
            })
            ->addColumn('accept', function ($object) {
                if($object->status == 1){
                    return [
                        'href' => route('class.accept', $object),
                        'status' => 404,
                    ];
                }else{
                    return [
                        'status' => 1,
                    ];
                }
            })
            ->make(true);
    }

    public function addTeacher($id)
    {
        $teachers = User::query()
            ->addSelect('users.id as id', 'users.name as name')
            ->where('level', 2)
            ->get();
        
        return view('classes.addTeacher', [
            'teachers' => $teachers,
            'class_id' => $id,
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $teacher = new ClassStudent();
        $teacher->class_id = $request->class_id;
        $teacher->user_id = $request->teacher;
        $teacher->save();
        return redirect()->route('class.index', $request->class_id)->with('success', 'Teacher added successfully');
    }

    public function update(Request $request)
    {
        $name = $request->name;
        $subject_id = $request->subject;
        $weekdays = $request->weekday;
        $shift = $request->shift; 
        $teacher_id = $request->teacher;

        $teacher = ClassStudent::query()
            ->addSelect('users.id as id')
            ->leftJoin('users', 'class_students.user_id', 'users.id')
            ->where('class_students.class_id', $request->id)
            ->where('users.level', 2)
            ->first();


        // remove older teacher    
        if(!empty($teacher)){
            $current_teacher_id = $teacher->id;
            ClassStudent::where('user_id', $current_teacher_id)->delete();
        }
        //create new teacher
        ClassStudent::create([
            'class_id' => $request->id,
            'user_id' => $teacher_id,
        ]);

        Classes::updateOrCreate([
            'id' => $request->id,
        ], [
            'shift' => $shift,
            'weekdays' => $weekdays
        ]);

        return redirect()->route('class.index')->with('message', 'Success update ' . $name . ' !!!');
    }

    public function edit($id)
    {
        $class = Classes::find($id);

        $subject = new Subjects();
        $subject_data = $subject::query()->get([
            'id',
            'name',
        ]);

        $teacher = ClassStudent::query()
            ->select('users.id as id', 'users.name as name')
            ->leftJoin('users', 'class_students.user_id', 'users.id')
            ->where('class_id', $id )
            ->where('users.level', 2)
            ->first();

        if(!isset($teacher)){
            $teacher = null;
        }
        
        $teachers = User::query()
            ->addSelect('users.id as user_id', 'users.name as user_name')
            ->where('level', 2)
            ->get();

        $weekdays = $class->weekdays;

        $numberOfStudents = ClassStudent::query()
            ->where('class_id', $id)
            ->count();

        return view('classes.edit', [
            'class' => $class,
            'current_teacher' => $teacher,
            'teachers' => $teachers,
            'subject' => $subject_data,
            'weekdays' => $weekdays,
            'numberOfStudents' => $numberOfStudents,
        ]);

        // return $teacher;
    }

    public function userApi($id)
    {
        $query = ClassStudent::query()
            ->select('class_students.class_id')
            ->addSelect('users.id', 'users.name')
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('class_id', '=', $id)
            ->where('users.level', 1)
            ->get();
        return DataTables::of($query)
            ->addColumn('destroy', function ($object) {
                return route('classStudent.destroy', $object);
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
        $name = $request->name;
        $subject_id = $request->subject;
        $weekdays = $_POST['weekday'];
        $shift = $request->shift;

        $class = new Classes();
        $class->name = $name;
        $class->subject_id = $subject_id;
        $class->weekdays = $weekdays;
        $class->shift = $shift;
        $class->save();

        return redirect()->route('class.index')->with('message', 'Success add ' . $name . ' !!!');
    }

    public function autoSchedule($id)
    {
        $class = Classes::find($id)->with('subjects')->first();

        $weekdays = $class->weekdays;
        $subject = $class->subjects;
        $class_sessions = intval($subject->class_sessions);
        $start_date = $subject->start_date;
        $date = new Carbon($start_date);

        $count = 0;
        try {
            while (true) {
                if ($count < $class_sessions) {
                    if(in_array($date->isoFormat('E'), $weekdays)){
                        $schedule = new Schedules();
                        $schedule->class_id = $id;
                        $schedule->date = $date->format('Y-m-d');
                        $schedule->save();
                        $count++;
                    }
                    $date->addDays(1);
                }else{
                    break;
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return redirect()->route('schedule.index')->with('message', 'Success create auto schedule !!!');
    }

    public function accept($id)
    {
        $class = Classes::find($id);

        $subject = new Subjects();
        $subject_data = $subject::query()->get([
            'id',
            'name',
        ]);

        $teacher = ClassStudent::query()
            ->select('users.id as id', 'users.name as name')
            ->leftJoin('users', 'class_students.user_id', 'users.id')
            ->where('class_id', $id )
            ->where('users.level', 2)
            ->first();

        if(!isset($teacher)){
            $teacher = null;
        }
        
        $teachers = User::query()
            ->addSelect('users.id as user_id', 'users.name as user_name')
            ->where('level', 2)
            ->get();

        $weekdays = $class->weekdays;

        $numberOfStudents = ClassStudent::query()
            ->where('class_id', $id)
            ->count();

        return view('classes.accept', [
            'class' => $class,
            'current_teacher' => $teacher,
            'teachers' => $teachers,
            'subject' => $subject_data,
            'weekdays' => $weekdays,
            'numberOfStudents' => $numberOfStudents,
        ]);
    }

    public function checkInformation($id)
    {
        $classFlag = true;
        $class = Classes::find($id);
        $teacher = ClassStudent::query()
            ->select('users.id as id', 'users.name as name')
            ->leftJoin('users', 'class_students.user_id', 'users.id')
            ->where('class_id', $id )
            ->where('users.level', 2)
            ->count();
        ;

        $classStudent = ClassStudent::where('class_id', $id)->get();


        if(count($class->weekdays) == 0){
            $classFlag = false;
        }else if($class->shift == null){
            $classFlag = false;
        }else if($class->subject_id == null){
            $classFlag = false;
        }else if($class->name == null){
            $classFlag = false;
        }else if($teacher == 0){
            $classFlag = false;
        }

        $classStudentFlag = true;
        if($classStudent->count() - $teacher < 15){
            $classStudentFlag = false;
        }

        if ($classFlag == false) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Vui lòng điền đủ thông tin lớp học !',
                ]
            );
        }else{
            if($classStudentFlag == false){
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Số lượng sinh viên chưa đủ điều kiện mở lớp !',
                    ]
                );
            }else{
                Classes::where('id', $id)->update(['status' => 2]);
                return (
                    [
                        'status' => 'success',
                        'message' => 'Thành công !',
                    ]
                );
            }
        }

        return $teacher;
    }
    
}
