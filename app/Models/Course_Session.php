<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_Session extends Model
{
  protected $fillable = [
    'session_number',
    'start_time',
    'end_time',
  ];
}
