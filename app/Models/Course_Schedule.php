<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Schedule extends Model
{
  /** @use HasFactory<\Database\Factories\CourseScheduleFactory> */
  use HasFactory;

  protected $table = 'course__schedules';

  protected $fillable = [
    'course_id',
    'day_of_week',
    'start_session_id',
    'end_session_id',
    'start_week',
    'end_week',
  ];

  protected $casts = [
    'day_of_week' => 'integer',
    'start_session_id' => 'integer',
    'end_session_id' => 'integer',
    'start_week' => 'integer',
    'end_week' => 'integer',
  ];

  public function course()
  {
    return $this->belongsTo(Course::class);
  }

  public function startSession()
  {
    return $this->belongsTo(Course_Session::class, 'start_session_id');
  }

  public function endSession()
  {
    return $this->belongsTo(Course_Session::class, 'end_session_id');
  }
}
