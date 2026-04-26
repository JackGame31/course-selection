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
    $courses = Course::where('admin_id', $admin_id)->with('schedules')->get();
    return response()->json($courses);
  }

  public function store(Request $request)
  {
    if ($request->filled('schedules')) {
      $validated = $request->validate([
        'title' => 'required|string',
        'admin_id' => 'required|exists:admins,id',
        'schedules' => 'required|array|min:1',
        'schedules.*.day_of_week' => 'required|integer|between:0,6',
        'schedules.*.start_session_id' => 'required|exists:course__sessions,id',
        'schedules.*.end_session_id' => 'required|exists:course__sessions,id',
        'schedules.*.start_week' => 'required|integer|min:1|max:19',
        'schedules.*.end_week' => 'required|integer|min:1|max:19',
      ]);

      $schedules = $validated['schedules'];
      unset($validated['schedules']);

      $course = Course::create($validated);
      $course->schedules()->createMany($schedules);

      return response()->json($course->load('schedules'), 201);
    }

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

    if ($request->filled('schedules')) {
      $validated = $request->validate([
        'title' => 'required|string',
        'admin_id' => 'required|exists:admins,id',
        'schedules' => 'required|array|min:1',
        'schedules.*.day_of_week' => 'required|integer|between:1,5',
        'schedules.*.start_session_id' => 'required|exists:course__sessions,id',
        'schedules.*.end_session_id' => 'required|exists:course__sessions,id',
        'schedules.*.start_week' => 'required|integer|min:1|max:19',
        'schedules.*.end_week' => 'required|integer|min:1|max:19',
      ]);

      $course->update($request->only(['title', 'admin_id']));
      $course->schedules()->delete();
      $course->schedules()->createMany($validated['schedules']);

      return response()->json($course->load('schedules'));
    }

    $validated = $request->validate([
      'title' => 'required|string',
      'day' => 'required|integer',
      'startTime' => 'required|integer',
      'endTime' => 'required|integer',
      'admin_id' => 'required|exists:admins,id',
      'color' => 'string',
    ]);

    $course->update($validated);

    return response()->json($course);
  }

  public function destroy($id)
  {
    $course = Course::findOrFail($id);
    $course->delete();

    return response()->json(null, 204);
  }
}
