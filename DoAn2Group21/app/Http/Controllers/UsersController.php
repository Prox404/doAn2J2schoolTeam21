<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Imports\AdvancedUserImport;
use App\Models\User;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as Excel;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Classes;
use App\Models\ClassStudent;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as View;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
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
        return view('users.index');
    }

    public function api()
    {
        return DataTables::of(User::query())
            ->editColumn('created_at', function ($object) {
                return $object->created_at->format('Y/m/d');
            })
            ->editColumn('level', function ($object) {
                if ($object->level == 1)
                    return 'Sinh viên';
                else if ($object->level == 2)
                    return 'Giảng viên';
                else if ($object->level == 3)
                    return 'Giáo vụ';
                else if ($object->level == 4)
                    return 'Super Admin';
            })
            ->addColumn('edit', function ($object) {
                return route('user.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('user.destroy', $object);
            })
            ->make(true);
    }

    public function import()
    {
        try {
            $import = Excel::import(new UsersImport, request()->file('user_file'));
            return redirect()->back()->with('message', 'Success!!!');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', "File lỗi, vui lòng kiểm tra file !!");
        }
    }

    public function getIdByName($object, $name)
    {
        foreach ($object as $key => $value) {
            if ($value->name == $name)
                return $value->id;
        }
        return 0;
    }

    public function getClassSessionByName($object, $name)
    {
        foreach ($object as $key => $value) {
            if ($value->name == $name)
                return $value->class_sessions;
        }
        return 0;
    }

    public function advancedImport()
    {
        if (!empty($_SESSION['id'])) {
            $user_id = $_SESSION['id'];
        } else {
            $user_id = 0;
        }

        try {
            $import = Excel::toArray(new AdvancedUserImport, request()->file('user_file'));
            $import = $import[0];

            $subjects = Subjects::get(
                [
                    'id',
                    'name',
                    'class_sessions'
                ]
            );

            // $current_class = "none";

            foreach ($import as $data) {

                $isValid = $this->getIdByName($subjects, $data['subject']);

                $current_class =  Classes::query()
                    ->addSelect('classes.*')
                    ->where('name', 'like', $data['subject'] . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                if (!empty($current_class)) {

                    $numberStudent = ClassStudent::query()
                        ->addSelect('class_students.*')
                        ->where('class_id', $current_class->id)
                        ->get()
                        ->count();

                    echo $numberStudent . PHP_EOL;

                    // echo $number . '-' . $current_class->name . PHP_EOL;

                    if ($isValid != 0) {
                        $class_sessions = $this->getClassSessionByName($subjects, $data['subject']);
                        if ($numberStudent < $class_sessions) {
                            $user = User::updateOrCreate(
                                [
                                    'email' => $data['email'],
                                ],
                                [
                                    'name' => $data['name'],
                                    'level' => 1,
                                    'password' => bcrypt($data['password']),
                                    'added_by' => $user_id,
                                ]
                            );
                            ClassStudent::create([
                                'user_id' => $user->id,
                                'class_id' => $current_class->id,
                            ]);
                        } else {
                            preg_match_all('!\d+!', $current_class->name, $matches);
                            $number = intval($matches[0][0]) + 1;
                            $ClassName = $data['subject'] . $number;
                            $classes = Classes::create(
                                [
                                    'name' => $ClassName,
                                    'subject_id' => $isValid,
                                ]
                            );
                            $user = User::updateOrCreate(
                                [
                                    'email' => $data['email'],
                                ],
                                [
                                    'name' => $data['name'],
                                    'level' => 1,
                                    'password' => bcrypt($data['password']),
                                    'added_by' => $user_id,
                                ]
                            );
                            ClassStudent::create([
                                'user_id' => $user->id,
                                'class_id' => $classes->id,
                            ]);
                        }
                    }
                    // $classes = $current_class->class_sessions;
                } else {
                    if ($isValid != 0) {
                        $ClassName = $data['subject'] . '1';
                        $classes = Classes::create(
                            [
                                'name' => $ClassName,
                                'subject_id' => $isValid,
                            ]
                        );

                        $user = User::updateOrCreate(
                            [
                                'email' => $data['email'],
                            ],
                            [
                                'name' => $data['name'],
                                'level' => 1,
                                'password' => bcrypt($data['password']),
                                'added_by' => $user_id,
                            ]
                        );
                        ClassStudent::create([
                            'user_id' => $user->id,
                            'class_id' => $classes->id,
                        ]);
                    }
                }
            }

            return $import;
        } catch (\Exception $e) {
            return redirect()->back()->with('message', "File lỗi, vui lòng kiểm tra file !!" . $e->getMessage());
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->fill($request->validated());
        $user->update();

        return redirect()->route('user.index')->with('message', 'Success!!!');
    }

    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('message', 'Success delete ' . $user->name . ' !!!');
    }
}
