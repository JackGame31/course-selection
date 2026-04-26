<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // session for each courses
    DB::table('course__sessions')->insert([
      ['session_number' => 1, 'start_time' => '08:00', 'end_time' => '08:50'],
      ['session_number' => 2, 'start_time' => '08:55', 'end_time' => '09:45'],
      ['session_number' => 3, 'start_time' => '10:15', 'end_time' => '11:05'],
      ['session_number' => 4, 'start_time' => '11:10', 'end_time' => '12:00'],
      ['session_number' => 5, 'start_time' => '14:00', 'end_time' => '14:50'],
      ['session_number' => 6, 'start_time' => '14:55', 'end_time' => '15:45'],
      ['session_number' => 7, 'start_time' => '16:15', 'end_time' => '17:05'],
      ['session_number' => 8, 'start_time' => '17:10', 'end_time' => '18:00'],
      ['session_number' => 9, 'start_time' => '18:45', 'end_time' => '19:35'],
      ['session_number' => 10, 'start_time' => '19:40', 'end_time' => '20:30'],
      ['session_number' => 11, 'start_time' => '20:30', 'end_time' => '21:20'],
    ]);

    // 10 user
    User::factory(1)->create([
      'name' => 'User',
      'email' => 'user@gmail.com',
      'password' => bcrypt('password'),
    ]);
    User::factory(9)->create();

    // 10 admins + 2-3 courses each
    Admin::factory(1)->create([
      'name' => 'Admin',
      'email' => 'admin@gmail.com',
      'password' => bcrypt('password'),
    ]);
    Admin::factory(9)->create();
  }
}
