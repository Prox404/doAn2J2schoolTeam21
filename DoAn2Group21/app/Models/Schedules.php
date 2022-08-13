<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;

    protected $fillable = [	
        'weekday_id',	
        'date',
        'subject_id',
    ];

    public $timestamps = false;

    public function classes()
    {
        return $this->belongsTo('App\Models\Classes', 'class_id');
    }

    public function attendance()
    {
        return $this->hasOne('App\Models\Attendance', 'schedule_id');
    }
}
