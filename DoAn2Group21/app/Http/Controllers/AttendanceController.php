<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassWeekday;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    public function index()
    {
        $subjects = Subjects::query()
        ->addSelect('subjects.*')
        ->get();

        return view('attendance.index')->with('subjects', $subjects);
    }

    public function api()
    {
        $query = Classes::query()
            ->addSelect('classes.*')
            ->addSelect('subjects.name as subject_name')
            ->leftJoin('subjects', 'classes.subject_id', 'subjects.id');
        
        return DataTables::of($query)
        ->addColumn('history', function ($object) {
            return route('attendance.history', $object);
        })
        ->addColumn('destroy', function ($object) {
            return route('attendance.destroy', $object);
        })
        ->make(true);
    }
}
