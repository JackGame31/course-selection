<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Admin;
use Illuminate\Http\Request;

class CourseController extends Controller
{
  public function get()
  {
    $admin_id = request('admin_id');
    $courses = Course::where('admin_id', $admin_id)->get();
    return response()->json($courses);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string',
      'day' => 'required|integer',
      'startTime' => 'required|integer',
      'endTime' => 'required|integer',
      'admin_id' => 'required|exists:admins,id',
      'color' => 'string',
    ]);

    $course = Course::create($validated);

    return response()->json($course, 201);
  }

  public function update(Request $request, $id)
  {
    $course = Course::findOrFail($id);

    $course->update($request->all());

    return response()->json($course);
  }

  public function destroy($id)
  {
    $course = Course::findOrFail($id);
    $course->delete();

    return response()->json(null, 204);
  }
}
