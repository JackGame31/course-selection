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
    Schema::create('course__sessions', function (Blueprint $table) {
      $table->id();
      $table->integer('session_number')->unique(); // 1-11
      $table->time('start_time'); // e.g., '08:00:00'
      $table->time('end_time'); // e.g., '08:50:00'
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('course__sessions');
  }
};
