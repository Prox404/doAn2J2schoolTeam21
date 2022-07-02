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
}
