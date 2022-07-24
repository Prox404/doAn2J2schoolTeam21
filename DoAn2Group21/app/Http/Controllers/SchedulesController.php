<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassWeekday;
use App\Models\Schedules;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class SchedulesController extends Controller
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
        return view('schedules.index');
    }

    public function classApi()
    {
        $query = Classes::query()
            ->addSelect('classes.*')
            ->addSelect('subjects.name as subject_name')
            ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');

        return DataTables::of($query)
            ->addColumn('edit', function ($object) {
                return route('schedule.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('class.destroy', $object);
            })
            ->addColumn('autoSchedule', function ($object) {
                if (isset($object->schedule()->where('class_id', $object->id)->first()->id)) {
                    return [
                        'status' => 1,
                    ];
                } else {
                    return [
                        'status' => 404,
                        'href' => route('class.autoSchedule', $object),
                    ];
                }
            })
            ->make(true);
    }

    public function edit($id)
    {
        $schedules = Schedules::query()
            ->addSelect('schedules.*', 'classes.name as class_name', 'subjects.name as subject_name')
            ->where('class_id', $id)
            ->leftJoin('classes', 'schedules.class_id', 'classes.id')
            ->leftJoin('subjects', 'classes.subject_id', 'subjects.id')
            ->orderBy('date', 'asc')
            ->paginate(10);

        return view('schedules.edit', [
            'schedules' => $schedules,
        ]);
    }

    public function destroy(Schedules $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedule.edit', $schedule->class_id)->with('success', 'Schedule deleted successfully');
    }

    public function getSchedule($id)
    {
        $schedule = Schedules::find($id);

        return $schedule;
    }

    public function update(Request $request)
    {
        $date = new Carbon($request->date);
        $weekday_id = $date->dayOfWeek;
        if ($weekday_id == 0) {
            $weekday_id = 7;
        }

        Schedules::updateOrCreate([
            'id' => $request->id,
        ], [
            'shift' => $request->shift,
            'weekday_id' => $weekday_id,
            'date' => $request->date,
        ]);

        return redirect()->route('schedule.edit', $request->class_id)->with('message', 'Success update !!!');
    }
}
