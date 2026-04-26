<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('course__schedules', function (Blueprint $table) {
      $table->id();
      $table->tinyInteger('day_of_week'); // 0-6 (Sunday=0)
      $table->foreignId('start_session_id')->constrained('course__sessions');
      $table->foreignId('end_session_id')->constrained('course__sessions');
      $table->integer('start_week'); // 1-19
      $table->integer('end_week'); // 1-19

      // for now, no classroom assignment
      // $table->foreignId('classroom_id')->constrained()->onDelete('set null');
      $table->foreignId('course_id')->constrained()->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('course__schedules');
  }
};
