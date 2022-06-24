<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassWeekday extends Model
{
    use HasFactory;
    
    public $timestamps = false; 

    protected $fillable = [
        'weekday_id',
        'class_id',
        'shift',
    ];

    public function WeekDays()
    {
        $this->belongsTo(WeekDays::class, 'weekday_id', 'id');
    }
}
