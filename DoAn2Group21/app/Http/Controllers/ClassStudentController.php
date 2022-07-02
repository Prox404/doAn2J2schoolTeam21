<?php

namespace App\Http\Controllers;

use App\Imports\ClassStudentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClassStudentController extends Controller
{
    public function import()
    {
        try{
            $import = Excel::import(new ClassStudentImport, request()->file('user_file') );
            return redirect()->back()->with('message', 'Success!!!');
        }
        catch(\Exception $e){
            return redirect()->back()->with('message', "File lỗi, vui lòng kiểm tra file !!" . $e);
        }
    }

    public function destroy($id)
    {
        
    }
}
