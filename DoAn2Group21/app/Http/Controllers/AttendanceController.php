<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\ClassWeekday;
use App\Models\Notification;
use App\Models\Schedules;
use App\Models\Subjects;
use App\Models\User;
use Carbon\Carbon;
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

    public function findValueById($object, $id, $id_field, $field)
    {
        foreach ($object as $key => $value) {
            if ($value->$id_field == $id) {
                return $value->$field;
            }
        }
    }

    public function index()
    {
        if (auth()->user()->level <= 4 && auth()->user()->level >= 2) {
            $subjects = Subjects::query()
                ->addSelect('subjects.*')
                ->get();

            return view('attendance.index')->with('subjects', $subjects);
        }
        if (auth()->user()->level == 1) {
            $id = auth()->user()->id;
            $classes = ClassStudent::query()
                ->addSelect('class_id')
                ->where('user_id', $id)
                ->distinct('class_id')
                ->get();

            $class_infor = Classes::whereIn('id', $classes)->get();

            $schedules = Schedules::whereIn('class_id', $classes)->get();
            
            $schedule_id = [];

            foreach ($schedules as $schedule) {
                $schedule_id[] = $schedule['id'];
                $schedule['shift'] = $this->findValueById($class_infor, $schedule['class_id'], 'id', 'shift');
                $schedule['name'] = $this->findValueById($class_infor, $schedule['class_id'], 'id', 'name');
            }

            $attendances = Attendance::whereIn('schedule_id', $schedule_id)->where('user_id', $id)->get();
            // return $schedules;
            foreach ($attendances as $attendance) {
                foreach ($schedules as $schedule) {
                    if ((int)$attendance['schedule_id'] == (int)$schedule['id']) {
                        switch ((int)$attendance['status']) {
                            case 1:
                                // echo '1' . PHP_EOL;
                                $schedule['status'] = 'Present';
                                // print_r($schedule . PHP_EOL);
                                break;
                            case 2:
                                // echo '2' . PHP_EOL;
                                $schedule['status'] = 'Absent';
                                break;
                            case 3:
                                // echo '3' . PHP_EOL;
                                $schedule['status'] = 'OnLeave';
                                break;
                            default:
                                $schedule['status'] = 'Present';
                                break;
                        }
                    }
                }
            }

            // return $schedules;
            $scheduleByClass = [];
            foreach ($schedules as $schedule) {
                $scheduleByClass[$schedule['name']][] = $schedule;
            }

            // return $scheduleByClass;

            return view('attendance.index')->with('scheduleByClass', $scheduleByClass);
        }
    }

    public function api()
    {
        if (auth()->user()->level >= 3 && auth()->user()->level <= 4) {
            $query = Classes::query()
                ->addSelect('classes.*')
                ->addSelect('subjects.name as subject_name')
                ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');

            return DataTables::of($query)
                ->addColumn('history', function ($object) {
                    return route('attendance.history', $object);
                })
                ->make(true);
        }
        if (auth()->user()->level == 2) {
            $id = auth()->user()->id;
            $class_id = ClassStudent::where('user_id', $id)->get('class_id');
            $query = Classes::query()
                ->addSelect('classes.*')
                ->addSelect('subjects.name as subject_name')
                ->where('classes.status', 2)
                ->whereIn('classes.id', $class_id)
                ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');

            return DataTables::of($query)
                ->addColumn('history', function ($object) {
                    return route('attendance.history', $object);
                })
                ->make(true);
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
            ->where('users.level', 1)
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
        $all_student_present = [];


        $students_absent_array = [];

        foreach ($students_absent as $key => $value) {
            if ($value > 3) {
                $students_absent_array[] = [
                    'id' => $key,
                    'name' => $this->findValueById($attendance, $key, 'user_id', 'name'),
                    'absent' => $value,
                ];
            }
        }
        $students_present_array = [];
        foreach ($students_present as $key => $value) {
            $count = count($attendances);
            if ($value == $count) {
                $students_present_array[] = [
                    'id' => $key,
                    'name' => $this->findValueById($attendance, $key, 'user_id', 'name'),
                    'present' => $value,
                ];
            }
            $all_student_present[] = [
                'id' => $key,
                'name' => $this->findValueById($attendance, $key, 'user_id', 'name'),
                'session' => $value . '/' . $count,
                'score' => round($value / $count * 10, 2),
            ];
        }

        $attendances_data = [];
        foreach ($attendances as $key => $value) {
            $date = $this->findValueById($schedules, $key, 'id', 'date');
            $attendances_data[$date] = array_count_values($value);
        }


        $line_chart_labels = [];
        foreach ($schedules as $schedule) {
            $line_chart_labels[] = $schedule->date;
        }

        $line_chart_data = [];
        foreach ($line_chart_labels as $label) {
            if (in_array($label, array_keys($attendances_data))) {
                foreach ($attendances_data as $key => $value) {
                    if ($key === $label) {
                        if (!empty($value[1])) {
                            $present = $value[1];
                        } else {
                            $present = 0;
                        }
                        if (!empty($value[2])) {
                            $absent = $value[2];
                        } else {
                            $absent = 0;
                        }
                        if (!empty($value[3])) {
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

        foreach ($all_student_present as $key => $value) {
            # code...
        }


        if ($class->status == 3) {
            // return $all_student_present;
            return view('attendance.history')
                ->with('schedules', $schedules)
                ->with('class', $class)
                ->with('numberStudentAbsentMoreThan3Sessions', $students_absent_array)
                ->with('numberStudentPresentFullDay', $students_present_array)
                ->with('allStudent', $all_students)
                ->with('line_chart_labels', $line_chart_labels)
                ->with('numberStudentPresent', $present_students)
                ->with('numberStudentAbsent', $absent_students)
                ->with('numberStudentOnLeave', $onLeave_students)
                ->with('allStudentPresent', $all_student_present);
        } else {
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
        }
    }


    public function attendance($class_id, $schedule_id)
    {
        $class = Classes::find($class_id);
        if ($class->status == 3) {
            return redirect()->route('attendance.history', $class_id)->with('message', 'Class has been finished');
        } else {
            $students = ClassStudent::query()
                ->addSelect('users.id', 'users.name', 'class_students.*')
                ->leftJoin('users', 'class_students.user_id', 'users.id')
                ->where('users.level', 1)
                ->where('class_id', '=', $class_id)
                ->get();
            $schedules = Schedules::find($schedule_id);

            return view('attendance.attendance')
                ->with('students', $students)
                ->with('schedules', $schedules)
                ->with('class_id', $class_id);
        }
    }

    public function store(Request $request)
    {
        try {
            $schedule_id = $request->schedule_id;
            $class_id = $request->class_id;
            $class = Classes::find($class_id);
            if ($class->status == 3) {
                return redirect()->route('attendance.history', $class_id)->with('message', 'Class has been ended!!!');
            } else {
                $class_name = $class->name;
                foreach ($request->attendance as $key => $value) {
                    $find = Attendance::where('user_id', $key)
                        ->where('schedule_id', $schedule_id)
                        ->count();

                    if ($find >= 1) {
                        Attendance::where('user_id', $key)
                            ->where('schedule_id', $schedule_id)
                            ->update([
                                'status' => $value,
                            ]);
                    } else {
                        Attendance::Create([
                            'user_id' => $key,
                            'schedule_id' => $schedule_id,
                            'status' => $value,
                        ]);
                    }
                }

                $student_id = $request->student_id;

                $date = Carbon::now()->format('Y-m-d H:i');
                foreach ($student_id as $id) {
                    Notification::create([
                        'user_id' => $id,
                        'content' => $class_name . ': Attendance has been taken' . ' ' . $date,
                    ]);
                }

                return redirect()->route('attendance.history', $class_id)->with('message', 'Success!!!');
            }
            // return $class->status;

            // return $request->student_id;
        } catch (\Exception $e) {
            return redirect()->route('attendance.index')->with('message', 'Error!!! ' . $e->getMessage());
        }
    }
}
