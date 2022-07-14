<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\ClassWeekday;
use App\Models\Schedules;
use App\Models\Subjects;
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
            ->addColumn('teacher', function ($object) {
                $teacher = ClassStudent::query()
                    ->addSelect('users.name as teacher')
                    ->leftJoin('users', 'class_students.user_id', 'users.id')
                    ->where('class_students.class_id', $object->id)
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
            ->make(true);
    }

    public function addTeacher()
    {
        
    }

    public function update(Request $request)
    {
        $name = $request->name;
        $subject_id = $request->subject;
        $weekdays = $request->weekday;
        $shift = $request->shift;

        // $number_weekday = count($weekdays);

        Classes::updateOrCreate([
            'id' => $request->id,
        ], [
            'name' => $name,
            'subject_id' => $subject_id
        ]);

        ClassWeekday::where('class_id', $request->id)->delete();

        foreach ($weekdays as $weekday) {
            ClassWeekday::create([
                'class_id' => $request->id,
                'shift' => $shift,
                'weekday_id' => $weekday
            ]);
        }

        return redirect()->route('classes.index')->with('message', 'Success update ' . $name . ' !!!');
    }

    public function edit($id)
    {
        $class = Classes::find($id);

        $subject = new Subjects();
        $subject_data = $subject::query()->get([
            'id',
            'name',
        ]);

        $weekdays = ClassWeekday::query()
            ->addSelect('class_weekdays.*')
            ->where('class_id', '=', $id);

        return view('classes.edit', [
            'class' => $class,
            'subject' => $subject_data,
            'weekdays' => $weekdays,
        ]);
    }

    public function userApi($id)
    {
        $query = ClassStudent::query()
            ->select('class_students.class_id')
            ->addSelect('users.id', 'users.name')
            ->leftJoin('users', 'users.id', 'user_id')
            ->where('class_id', '=', $id);
        return DataTables::of($query)
            ->addColumn('edit', function ($object) {
                return route('user.edit', $object);
            })
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

        for ($i = 0; $i < $number_weekday; $i++) {
            $class_weekday = new ClassWeekday();
            $class_weekday->class_id = $insertedId;
            $class_weekday->shift = $shift;
            $class_weekday->weekday_id = $weekdays[$i];
            $class_weekday->save();
        }

        // return $insertedId;
        return redirect()->route('subject.index')->with('message', 'Success add ' . $name . ' !!!');
    }

    function getAllDaysInAMonth($year, $month, $day, $daysError = 30)
    {
        $dateString = 'first ' . $day . ' of ' . $year . '-' . $month;

        if (!strtotime($dateString)) {
            throw new \Exception('"' . $dateString . '" is not a valid strtotime');
        }

        $startDay = new \DateTime($dateString);

        if ($startDay->format('j') > $daysError) {
            $startDay->modify('- 7 days');
        }

        $days = [];

        while ($startDay->format('Y-m') <= $year . '-' . str_pad($month, 2, 0, STR_PAD_LEFT)) {
            $days[] = clone $startDay;
            $startDay->modify('+ 7 days');
        }

        return $days;
    }

    public function autoSchedule($id)
    {
        $class = Classes::query()
            ->addSelect('classes.*')
            ->addSelect('subjects.name as subject_name', 'subjects.end_date', 'subjects.start_date')
            ->leftJoin('subjects', 'classes.subject_id', 'subjects.id')
            ->where('classes.id', '=', $id)
            ->first();

        // $class = $class->toArray();

        $weekdays = ClassWeekday::query()
            ->addSelect('class_weekdays.weekday_id as weekdays')
            ->where('class_id', '=', $id)
            ->get();
        $weekdays = $weekdays->pluck('weekdays')->toArray();

        $shift = ClassWeekday::query()
            ->addSelect('class_weekdays.shift as shift')
            ->where('class_id', '=', $id)
            ->first();
        $shift = $shift->shift;

        $start_date = new Carbon($class->start_date);
        $end_date = new Carbon($class->end_date);

        $start_month = $start_date->format('m');
        $end_month = $end_date->format('m');
        $start_year = $start_date->format('Y');
        $end_year = $end_date->format('Y');

        $days = [];
        for ($i = $start_month; $i <= $end_month; $i++) {
            foreach ($weekdays as $weekday) {
                switch ($weekday) {
                    case 1:
                        $day = 'Monday';
                        break;
                    case 2:
                        $day = 'Tuesday';
                        break;
                    case 3:
                        $day = 'Wednesday';
                        break;
                    case 4:
                        $day = 'Thursday';
                        break;
                    case 5:
                        $day = 'Friday';
                        break;
                    case 6:
                        $day = 'Saturday';
                        break;
                    case 7:
                        $day = 'Sunday';
                        break;
                    default:
                        $day = null;
                        break;
                }

                $days = $this->getAllDaysInAMonth($start_year, $i, $day);

                foreach ($days as $day) {
                    $schedule = new Schedules();
                    $schedule->weekday_id = $weekday;
                    $schedule->date = $day;
                    $schedule->subject_id = $class->subject_id;
                    $schedule->class_id = $id;
                    $schedule->shift = $shift;
                    $schedule->save();
                }
            }
        }

        return redirect()->route('schedule.index')->with('message', 'Success create auto schedule !!!');
    }
}
