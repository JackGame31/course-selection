<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Course extends Model
{
  /** @use HasFactory<\Database\Factories\CourseFactory> */
  use HasFactory;

  protected $fillable = [
    'title',
    'admin_id'
  ];

  public function teacher()
  {
    return $this->belongsTo(Admin::class, 'admin_id');
  }

  public function schedules()
  {
    return $this->hasMany(Course_Schedule::class);
  }

  public function students()
  {
    return $this->belongsToMany(User::class)->withTimestamps();
  }
}