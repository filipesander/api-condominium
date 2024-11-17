<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'salary',
        'date_admission',
        'date_dismissal',
        'vacation',
        'position_id'
    ];
}