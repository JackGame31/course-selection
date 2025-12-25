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
    'day',
    'startTime',
    'endTime',
    'color',
    'admin_id'
  ];

  protected $casts = [
    'day' => 'integer',
    'startTime' => 'integer',
    'endTime' => 'integer',
  ];

  public function teacher()
  {
    return $this->belongsTo(Admin::class, 'admin_id');
  }

  public function students()
  {
    $this->belongsToMany(User::class)->withTimestamps();
  }
}