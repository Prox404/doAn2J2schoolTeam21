<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\Notification;
use App\Models\Scores;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class ScoresController extends Controller
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
        return View::make('scores.index');
    }

    public function classApi()
    {
        if (auth()->user()->level >= 3 && auth()->user()->level <= 4) {
            $query = Classes::query()
                ->addSelect('classes.*')
                ->addSelect('subjects.name as subject_name')
                ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');

            return DataTables::of($query)
                ->addColumn('edit', function ($object) {
                    return route('score.edit', $object);
                })
                ->make(true);
        }
        if (auth()->user()->level == 2) {
            $id = auth()->user()->id;
            $class_id = ClassStudent::where('user_id', $id)->get('class_id');
            $query = Classes::query()
                ->addSelect('classes.*')
                ->addSelect('subjects.name as subject_name')
                ->where('classes.status', '>=', 2)
                ->whereIn('classes.id', $class_id)
                ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');

            return DataTables::of($query)
                ->addColumn('edit', function ($object) {
                    return route('score.edit', $object);
                })
                ->make(true);
        }
        if (auth()->user()->level == 1) {
            $id = auth()->user()->id;
            $class_id = ClassStudent::where('user_id', $id)->get('class_id');
            $query = Classes::query()
                ->addSelect('classes.*')
                ->addSelect('subjects.name as subject_name')
                ->where('classes.status', '>=', 2)
                ->whereIn('classes.id', $class_id)
                ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');

            return DataTables::of($query)
                ->addColumn('show', function ($object) {
                    return route('score.show', $object);
                })
                ->make(true);
        }
    }

    public function edit($id)
    {
        $students = ClassStudent::query()
            ->addSelect('users.id', 'users.name', 'class_students.*')
            ->leftJoin('users', 'class_students.user_id', 'users.id')
            ->where('users.level', 1)
            ->where('class_id', '=', $id)
            ->get();

        return view('scores.edit')
            ->with('students', $students)
            ->with('id', $id);
    }

    public function store(Request $request, $id)
    {
        // return $request->scores;
        try {
            $class = Classes::find($id);
            if ($class->status == 3) {
                return redirect()->back()->with('message', 'Class has been ended!!!');
            } else {

                $quiz1 = $request->scores['quiz1'];
                $quiz2 = $request->scores['quiz2'];
                $homework = $request->scores['homework'];
                $midterm = $request->scores['midterm'];
                $final = $request->scores['final'];

                foreach ($quiz1 as $key => $value) {
                    if ($value <= 10 && $value >= 0) {
                        Scores::updateOrInsert(
                            [
                                'user_id' => $key,
                                'class_id' => $id,
                            ],
                            [
                                'quiz1' => $value,
                            ]
                        );
                    } else {
                        return redirect()->back()->with('message', 'Quiz 1 score must be between 0 and 10!!!');
                    }
                }

                foreach ($quiz2 as $key => $value) {
                    if ($value <= 10 && $value >= 0) {
                        Scores::updateOrInsert(
                            [
                                'user_id' => $key,
                                'class_id' => $id,
                            ],
                            ['quiz2' => $value]
                        );
                    } else {
                        return redirect()->back()->with('message', 'Quiz 2 score must be between 0 and 10!!!');
                    }
                }

                foreach ($homework as $key => $value) {
                    if ($value <= 10 && $value >= 0) {
                        Scores::updateOrInsert(
                            [
                                'user_id' => $key,
                                'class_id' => $id,
                            ],
                            ['homework' => $value]
                        );
                    } else {
                        return redirect()->back()->with('message', 'Homework score must be between 0 and 10!!!');
                    }
                }
                foreach ($midterm as $key => $value) {
                    if ($value <= 10 && $value >= 0) {
                        Scores::updateOrInsert(
                            [
                                'user_id' => $key,
                                'class_id' => $id,
                            ],
                            ['midterm' => $value]
                        );
                    } else {
                        return redirect()->back()->with('message', 'Midterm exam score must be between 0 and 10!!!');
                    }
                }
                foreach ($final as $key => $value) {
                    if ($value <= 10 && $value >= 0) {
                        Scores::updateOrInsert(
                            [
                                'user_id' => $key,
                                'class_id' => $id,
                            ],
                            ['final' => $value]
                        );
                    } else {
                        return redirect()->back()->with('message', 'Final exam score must be between 0 and 10!!!');
                    }
                }

                $student_id = ClassStudent::where('class_id', $id)->get('user_id');
                // return $student_id;
                $date = Carbon::now()->format('Y-m-d H:i');
                foreach ($student_id as $id) {
                    Notification::create([
                        'user_id' => $id['user_id'],
                        'content' => $class->name . ': Score has been taken' . ' ' . $date,
                    ]);
                }

                return redirect()->back()->with('message', 'Success!!!');
            }
            // return $class->status;

            // return $request->student_id;
        } catch (\Exception $e) {
            return redirect()->route('score.index')->with('message', 'Error!!! ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        if (auth()->user()->level == 1) {
            $user_id = auth()->user()->id;
            $scores = Scores::where('user_id', $user_id)->where('class_id', $id)->get();
            // return $scores;
            if (count($scores) != 0) {
                $scores = $scores[0];
            } else {
                $scores = [];
            }

            return view('scores.show')
                ->with('scores', $scores);
        } else if (auth()->user()->level >= 2 && auth()->user()->level <= 4) {
            $class = Classes::find($id);
            if ($class->status == 3) {
                $scores = Scores::where('class_id', $id);
                $scores_obj = $scores->get();
                // $user_id = $scores->get('user_id');

                if (count($scores_obj) != 0) {
                    $students = ClassStudent::query()
                        ->addSelect('users.id', 'users.name', 'class_students.*')
                        ->leftJoin('users', 'class_students.user_id', 'users.id')
                        ->where('users.level', 1)
                        ->where('class_id', '=', $id)
                        ->get();

                    foreach ($scores_obj as $score) {
                        $score['name'] = $this->findValueById($students, $score['user_id'], 'id', 'name');
                    }
                } else {
                    $scores_obj = [];
                }
                // return $scores_obj;
                return view('scores.show')
                    ->with('scores', $scores_obj)
                    ->with('id', $id);
            } else {
                return redirect()->back()->with('message', 'Class has not been finished!!!');
            }
        }
    }
}
