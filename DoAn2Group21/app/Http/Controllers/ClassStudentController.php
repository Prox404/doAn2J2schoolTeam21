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
                    $numberOfStudent = ClassStudent::where('class_id', $id)->count();
                    if($numberOfStudent < 15){
                        ClassStudent::updateOrCreate(
                            ['user_id' => $user_data['user_id'], 'class_id' => $id],
                            ['user_id' => $user_data['user_id'], 'class_id' => $id]
                        );
                        $count++;
                    }else{
                        $message = 'Số lượng học viên trong lớp đã đạt đủ 15 học viên';
                    }
                    
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

    public function store(Request $request, $id)
    {
        try {

            $users = $request->students;
            $numberOfStudent = ClassStudent::where('class_id', $id)->count();
            $teacher = ClassStudent::query()->where('class_id', $id)->leftJoin('users', 'users.id', '=', 'class_students.user_id')->where('users.level', 2)->first();
            if(!empty($teacher)){
                $count = $numberOfStudent - 1;
            }else{
                $count = $numberOfStudent;
            }
            
            $message = '';
            $numberAdded = 0;

            foreach ($users as $user) {
                if($count < 15){
                    ClassStudent::updateOrCreate(
                        ['user_id' => $user['id'], 'class_id' => $id],
                        ['user_id' => $user['id'], 'class_id' => $id]
                    );
                    $count++;
                    $numberAdded++;
                }else{
                    $message = 'Số lượng học viên trong lớp đã đạt đủ 15 học viên';
                    break;
                }
            }
            if(!empty($message)){

                return [
                    'status' => 'success',
                    'message' => 'Thêm thành công ' . $numberAdded . ' sinh viên ! ' . $message,
                ];
            }
            return [
                'status' => 'success',
                'message' => 'Thêm thành công !'
            ];
        } catch (\Exception $e) {
           return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
        
    }
}
