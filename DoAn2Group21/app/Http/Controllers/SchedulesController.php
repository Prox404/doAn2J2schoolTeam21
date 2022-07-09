<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SchedulesController extends Controller
{
    public function index()
    {
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
                return route('class.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('class.destroy', $object);
            })
            ->addColumn('autoSchedule', function ($object) {
                if(isset($object->schedule()->where('subject_id', $object->subject_id)->first()->id)) {
                    return 1;
                } else {
                    return route('class.autoSchedule', $object);
                }
            })
            ->make(true);
    }
}
