<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Imports\AdvancedUserImport;
use App\Models\User;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as Excel;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\updateUserRequest as UserUpdateUserRequest;
use App\Models\Classes;
use App\Models\ClassStudent;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as View;
use Illuminate\Validation\Rule;
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
        if (auth()->user()->level == 4) {
            return DataTables::of(User::query()->where('level', '<', 4))
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
        if (auth()->user()->level == 3){
            return DataTables::of(User::query()->where('level', '<', 3))
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
        
    }

    public function get20Student($id)
    {
        $class_student = ClassStudent::where('class_id', $id);
        $class = Classes::find($id);
        $classWeekday = $class->weekdays;
        $classShift = $class->shift;

        $all_invalid_class = Classes::query()
            ->addSelect('classes.*')
            ->orWhere(function ($query) use ($classWeekday, $classShift) {
                foreach ($classWeekday as $weekday) {
                    $query->orWhereJsonContains('weekdays', $weekday)
                        ->where('shift', $classShift);
                }
            })
            ->get('id');
        $user_not_valid = ClassStudent::query()
            ->addSelect('class_students.user_id')
            ->whereIn('class_id', $all_invalid_class)
            ->get('user_id');
        $userInClass = $class_student->get('user_id');
        $user = User::where('level', 1)
            ->whereNotIn('id', $userInClass)
            ->whereNotIn('id', $user_not_valid)
            ->limit(20);
        if(!empty($_GET['name'])){
            $user = $user->where('name', 'like', '%'.$_GET['name'].'%');
        }
        if(!empty($_GET['email'])){
            $user = $user->where('email', 'like', '%'.$_GET['email'].'%');
        }
        if(!empty($_GET['birthday'])){
            $user = $user->where('phone', 'like', '%'.$_GET['birthday'].'%');
        }
        if(!empty($_GET['id'])){
            $user = $user->where('id', $_GET['id']);
        }
        $user = $user->get();
        return DataTables::of($user)->make(true);
        // return 1;
    }

    public function excelToDateTime($value)
    {
        $UNIX_DATE = ($value- 25569) * 86400;
        return gmdate("Y-m-d", $UNIX_DATE);
    }

    public function import()
    {
        try {

            $user_id = auth()->user()->id;

            $import = Excel::toArray(new UsersImport, request()->file('user_file'));
            $import = $import[0];
            foreach ($import as $user){
                if(isset($user['email']) && isset($user['name']) && isset($user['birthday'])){
                    $birthday = $this->excelToDateTime($user['birthday']);
                    User::updateOrCreate(
                        [
                            'email' => $user['email'],
                        ],
                        [
                            'name' => $user['name'],
                            'level' => 1,
                            'birthday' => $birthday,
                            'password' => bcrypt($birthday),
                            'added_by' => $user_id,
                        ]
                    );
                }
            }
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

    public function advancedImport()
    {
        $user_id = auth()->user()->id;

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

            foreach ($import as $data) {

                $isValid = $this->getIdByName($subjects, $data['subject']);

                $current_class =  Classes::where('name', 'like', $data['subject'] . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                if (!empty($current_class)) {

                    $numberStudent = ClassStudent::where('class_id', $current_class->id)
                        ->get()
                        ->count();

                    if ($isValid != 0) {
                        $numberOfStudentPerClass = 15;
                        $user_birthday = $this->excelToDateTime($data['birthday']);
                        if ($numberStudent < $numberOfStudentPerClass) {
                            $user = User::updateOrCreate(
                                [
                                    'email' => $data['email'],
                                ],
                                [
                                    'name' => $data['name'],
                                    'level' => 1,
                                    'birthday' => $user_birthday,
                                    'password' => bcrypt($user_birthday),
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
                                    'birthday' => $user_birthday,
                                    'password' => bcrypt($user_birthday),
                                    'added_by' => $user_id,
                                ]
                            );
                            ClassStudent::create([
                                'user_id' => $user->id,
                                'class_id' => $classes->id,
                            ]);
                        }
                    }
                } else {
                    if ($isValid != 0) {
                        $user_birthday = $this->excelToDateTime($data['birthday']);
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
                                'birthday' => $user_birthday,
                                'password' => bcrypt($user_birthday),
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
            // return $import;
            return redirect()->back()->with('message', 'Success!!!');
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

    public function store(StoreRequest $request)
    {
        $added_by = auth()->user()->id;;
        $user = $request->validated();
        $user['password'] = bcrypt($user['birthday']);
        $user['added_by'] = $added_by;
        User::create($user);
        return redirect()->route('user.index')->with('message', 'Success!!!');
    }

    public function destroy(User $user)
    {
        
        if(ClassStudent::where('user_id', $user->id)->count() > 0 ){ 
            return redirect()->back()->with('message', 'Không thể xóa học viên đã đi học !');
        }else{
            $user->delete();
            return redirect()->route('user.index')->with('message', 'Success delete ' . $user->name . ' !!!');
            // return "deleted";
        }
        
    }

    public function clearAllNotifications($id)
    {
        Notification::where('user_id', $id)->delete();
    }

    public function show()
    {
        $user = auth()->user();
        return view('users.show')->with('user', $user);
    }

    public function storeUser(Request $request, $id)
    {
        $user = User::find($id);
        if(!empty($request->isChangePassword)){
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required',Rule::unique('users')->ignore($user->id), 'email', 'string', 'max:255'],
                'birthday' => 'required|date',
                'isChangePassword' => '',
                'currentPassword' => 'required|string',
                'password' => 'required|string',
                'rePassword' => 'required|string',
            ]);
            // return $request->all();
            if (Hash::check($request->currentPassword, $user->password)) {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->birthday = $request->birthday;
                $user->password = bcrypt($request->password);
                $user->save();
                return redirect()->back()->with('message', 'Success!!!');
            } else {
                return redirect()->back()->with('message', 'Mật khẩu hiện tại không đúng !');
            }
        }else{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required',Rule::unique('users')->ignore($user->id), 'email', 'string', 'max:255'],
                'birthday' => 'required|date',
            ]);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'birthday' => $request->birthday
            ]);
        }
        return redirect()->back()->with('message', 'Success!!!');
    }
}
