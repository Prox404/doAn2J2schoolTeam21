<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel as Excel;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as View;
use Yajra\DataTables\DataTables;

class UsersController extends Controller{

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
                if($object->level == 1)
                    return 'Sinh viên';
                else if ($object->level == 2 )
                    return 'Giảng viên';
                else if ($object->level == 3 )
                    return 'Giáo vụ';
                else if ($object->level == 4 )
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
        try{
            $import = Excel::import(new UsersImport, request()->file('user_file') );
            return redirect()->back()->with('message', 'Success!!!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('message', "File lỗi, vui lòng kiểm tra file !!");
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
        return redirect()->route('user.index')->with('message', 'Success delete ' . $user->name .' !!!');
    }

}
