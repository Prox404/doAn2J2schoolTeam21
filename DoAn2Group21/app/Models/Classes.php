<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [	
        'name',	
        'subject_id',
    ];

    public function ClassWeekDay()
    {
        $this->hasMany(ClassesWeekday::class, 'class_id', 'id');
    }
}
