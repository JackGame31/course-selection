<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
  public function calendar()
  {
    // Check if the user has any selected courses (pivot exists)
    $isFinish = auth()->user()->courses()->exists();
    $courses = $isFinish ? auth()->user()->courses->load('teacher') : collect();

    return view('student.calendar', compact(['courses', 'isFinish']));
  }

  public function submitCourseSelection()
  {
    // validate
    $validated = request()->validate([
      'selectedCourses' => 'required|array',
      'selectedCourses.*' => 'exists:courses,id',
    ]);

    // find course
    $user = auth()->user();
    foreach ($validated['selectedCourses'] as $id) {
      $user->courses()->attach($id);
    }

    return to_route('student.calendar')->with('success', 'Course selection submitted successfully.');
  }

  public function resetCourseSelection()
  {
    $user = auth()->user();

    // Detach all courses
    $user->courses()->detach();

    return to_route('student.calendar')->with('success', 'All courses have been removed.');
  }

  // register
  public function register()
  {
    return view('student.register');
  }

  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => ['required', 'min:4', 'max:255', 'unique:users'],
      'email' => 'required|email:dns|unique:users',
      'password' => 'required|min:5|max:255',
      'confirm-password' => 'required|same:password',
      'terms' => 'accepted'
    ]);

    $validatedData['password'] = Hash::make($validatedData['password']);
    User::create($validatedData);
    return redirect(route('login'))->with('success', 'Registration success! Please login');
  }

  // login
  public function login()
  {
    return view('student.login');
  }

  public function authenticate(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $remember = $request->has('remember');

    if (auth()->attempt($credentials, $remember)) {
      $request->session()->regenerate();
      return redirect()->intended(route('student.calendar'))->with('success', 'Login successful!');
    }

    return back()->with('error', 'Login failed!');
  }

  public function logout(Request $request)
  {
    auth()->logout();
    $request->session()->invalidate();
    request()->session()->regenerateToken();

    return to_route('login')->with('success', 'Logout successful!');
  }
}
