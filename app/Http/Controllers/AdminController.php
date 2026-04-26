<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  // calendar
  public function calendar()
  {
    $courses = Course::where('admin_id', auth()->guard('admin')->id())->get();
    // return view('teacher.calendar', compact('courses'));
    return view('teacher.test', compact('courses'));
  }

  // register
  public function register()
  {
    return view('teacher.register');
  }

  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => ['required', 'min:4', 'max:255', 'unique:admins'],
      'email' => 'required|email:dns|unique:admins',
      'password' => 'required|min:5|max:255',
      'confirm-password' => 'required|same:password',
      'terms' => 'accepted'
    ]);

    $validatedData['password'] = Hash::make($validatedData['password']);
    Admin::create($validatedData);
    return redirect(route('teacher.login'))->with('success', 'Registration success! Please login');
  }

  // login
  public function login()
  {
    return view('teacher.login');
  }

  public function authenticate(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $remember = $request->has('remember');

    if (auth()->guard('admin')->attempt($credentials, $remember)) {
      $request->session()->regenerate();
      return redirect()->intended(route('teacher.calendar'))->with('success', 'Login successful!');
    }

    return back()->with('error', 'Login failed!');
  }

  public function logout(Request $request)
  {
    auth()->guard('admin')->logout();
    $request->session()->invalidate();
    request()->session()->regenerateToken();

    return to_route('teacher.login')->with('success', 'Logout successful!');
  }
}
