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
        if (auth()->user()->level == 1 || auth()->user()->level == 2) {
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
        $class = Classes::find($id);
        if ($class->status == 3) {
            return redirect()->route('schedule.index')->with('message', 'Lớp đã kết thúc, không thể chỉnh sửa lịch học');
        } else {
            $schedules = Schedules::query()
                ->addSelect(
                    'schedules.*',
                    'classes.name as class_name',
                    'subjects.name as subject_name',
                    'classes.shift as shift'
                )
                ->where('class_id', $id)
                ->leftJoin('classes', 'schedules.class_id', 'classes.id')
                ->leftJoin('subjects', 'classes.subject_id', 'subjects.id')
                ->orderBy('date', 'asc')
                ->paginate(10);

            return view('schedules.edit', [
                'schedules' => $schedules,
                'class_id' => $id,
            ]);
        }

        // return $schedules;
    }

    public function destroy(Schedules $schedule)
    {
        if (auth()->user()->level > 2) {
            $class_id = $schedule->class_id;
            $class = Classes::find($class_id);
            if ($class->status == 3) {
                return redirect()->route('schedule.edit', $class_id)->with('message', 'Class has been finished');
            } else {
                if (Attendance::where('schedule_id', $schedule->id)->count() > 0) {
                    return redirect()->route('schedule.edit', $schedule->id)->with('message', 'Cannot delete, it already enable !!!');
                } else {
                    $schedule->delete();
                    return redirect()->route('schedule.edit', $schedule->class_id)->with('success', 'Schedule deleted successfully');
                    // return "deleted";
                }
            }
        }
    }

    public function classDestroy($id)
    {
        $all_schedule = Schedules::where('class_id', $id)->get('id');
        if (auth()->user()->level > 2) {
            if (Attendance::whereIn('schedule_id', $all_schedule)->count() > 0) {
                return redirect()->route('schedule.edit', $id)->with('message', 'Cannot delete, it already enable !!!');
            } else {
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

    public function changeSession($class_id, $schedule_id)
    {
        $class = Classes::find($class_id);
        if ($class->status == 3) {
            return redirect()->route('schedule.edit', $class_id)->with('message', 'Schedule changed failed!, Class has been finished !');
        } else {
            $class_weekdays = $class->weekdays;
            $last_date = Schedules::where('class_id', $class_id)->orderBy('date', 'desc')->first('date');
            $last_date = $last_date->date;
            $current = Carbon::parse($last_date);
            $count = 0;
            $date_changed = true;
            while ($count < 365 && $date_changed) {
                $current = $current->addDays(1);
                $day = $current->isoFormat('E');
                if (in_array($day, $class_weekdays)) {
                    Schedules::where('id', $schedule_id)->update([
                        'date' => $current->format('Y-m-d'),
                    ]);
                    return redirect()->route('schedule.edit', $class_id)->with('message', 'Schedule changed from ' . $last_date . ' to ' . $current->format('Y-m-d'));
                    $date_changed = false;
                }

                $count++;
            }
            return redirect()->route('schedule.edit', $class_id)->with('message', 'Schedule changed failed! ');
        }
    }
}
