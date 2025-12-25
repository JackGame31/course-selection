<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // 10 user
    User::factory(1)->create([
      'name' => 'User',
      'email' => 'user@gmail.com',
      'password' => bcrypt('password'),
    ]);
    User::factory(9)->create();

    // 10 admins
    Admin::factory(1)->create([
      'name' => 'Admin',
      'email' => 'admin@gmail.com',
      'password' => bcrypt('password'),
    ]);
    Admin::factory(9)->create();

    Course::factory(20)->create();
  }
}
