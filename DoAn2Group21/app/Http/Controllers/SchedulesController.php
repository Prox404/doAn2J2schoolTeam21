<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\Schedules;
use Carbon\Carbon;
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
        if(auth()->user()->level == 1 || auth()->user()->level == 2){
            $id = auth()->user()->id;

            $class_id = ClassStudent::where('user_id', $id)
            ->distinct('class_id')
            ->get('class_id');

            $schedules = Schedules::whereIn('class_id', $class_id)
            ->leftJoin('classes', 'schedules.class_id', 'classes.id')
            ->get();
            
            return view('schedules.index', [
                'schedules' => $schedules,
            ]);

        }
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
                return route('schedule.classDestroy', $object);
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
        if (auth()->user()->level > 2) {
            if(Attendance::where('schedule_id', $schedule->id)->count() > 0){
                return redirect()->route('schedule.edit', $schedule->id)->with('message', 'Cannot delete, it already enable !!!');
            }else{
                $schedule->delete();
                return redirect()->route('schedule.edit', $schedule->class_id)->with('success', 'Schedule deleted successfully');
                // return "deleted";
            }
        }  
    }
    
    public function classDestroy($id)
    {
        $all_schedule = Schedules::where('class_id', $id)->get('id');
        if (auth()->user()->level > 2) {
            if(Attendance::whereIn('schedule_id', $all_schedule)->count() > 0){
                return redirect()->route('schedule.edit', $id)->with('message', 'Cannot delete, it already enable !!!');
            }else{
                Schedules::whereIn('id', $all_schedule)->delete();
                return redirect()->route('schedule.edit', $id)->with('success', 'Schedule deleted successfully');
                // return "deleted";
            }
        }  
    }

    public function getSchedule($id)
    {
        $schedule = Schedules::find($id);

        return $schedule;
    }

    public function update(Request $request)
    {

        return redirect()->route('schedule.edit')->with('message', 'Success update !!!');
    }
}
