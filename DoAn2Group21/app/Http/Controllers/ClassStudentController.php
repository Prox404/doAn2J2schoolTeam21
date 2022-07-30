<?php

namespace App\Http\Controllers;

use App\Imports\ClassStudentImport;
use App\Models\ClassStudent;
use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class ClassStudentController extends Controller
{
    public function import($id)
    {
        try{
            $import = Excel::toArray(new ClassStudentImport, request()->file('user_file') );
            // return redirect()->back()->with('message', 'Success!!!');
            $import = $import[0];
            $message = '';
            $count = 0;
            foreach ($import as $user_data) {
                $user = User::where('id', $user_data['user_id'])->where('level', 1)->first();
                if(!empty($user)){
                    $ClassStudent = new ClassStudent();
                    $ClassStudent->user_id = $user_data['user_id'];
                    $ClassStudent->class_id = $id;
                    $ClassStudent->save();
                    $count++;
                }else{
                    $message = $message . '<br>' .'Không tìm thấy sinh viên có mã: '.$user_data['user_id'];
                }
            }
            return redirect()->back()->with('message', 'Thành công thêm '.$count . ' sinh viên !' . $message);
            // return $import;
        }
        catch(\Exception $e){
            return redirect()->back()->with('message', "File lỗi, vui lòng kiểm tra file !!" . $e);
        }
        
    }

    public function destroy($id)
    {
        ClassStudent::where('user_id', $id)->delete();
        return redirect()->back()->with('message', 'Xóa thành công !');
    }
}
