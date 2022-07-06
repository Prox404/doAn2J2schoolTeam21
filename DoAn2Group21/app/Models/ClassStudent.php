<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    use HasFactory;

    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'class_id',
    ];

    public function attendance()
    {
        return $this->hasMany('App\Models\Attendance', 'user_id');
    }
}
