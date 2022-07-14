<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    public $timestamps = false; 
    
    protected $fillable = [	
        'name',	
        'subject_id',
    ];

    public function ClassWeekday()
    {
        return $this->hasMany('App\Models\Schedules', 'class_id');
    }

    public function Schedule()
    {
        return $this->hasMany('App\Models\Schedules', 'class_id');
    }
}
