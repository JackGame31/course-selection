<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
  public function definition(): array
  {
    $courses = [
      'Introduction to Computer Science',
      'Data Structures and Algorithms',
      'Web Development Fundamentals',
      'Database Systems',
      'Software Engineering',
      'Operating Systems',
      'Computer Networks',
      'Artificial Intelligence',
      'Machine Learning',
      'Discrete Mathematics',
      'Linear Algebra',
      'Calculus I',
      'Calculus II',
      'Statistics and Probability',
      'Digital Logic Design',
      'Human-Computer Interaction',
      'Cybersecurity Basics',
      'Mobile Application Development',
      'Cloud Computing',
      'Project Management',
    ];

    // Day (1–7)
    $day = $this->faker->numberBetween(0, 6);

    // Possible start times in 30-minute steps
    // 08:00 (480) → 19:00 (1140)
    $startTime = $this->faker->randomElement(
      range(0, 1140, 30)
    );

    // Duration: 1h or 2h
    $duration = $this->faker->randomElement([60, 120, 150, 180, 210, 240]);

    // End time (rounded naturally since duration is multiple of 30)
    $endTime = $startTime + $duration;

    // Ensure not past 23:00 (1380)
    if ($endTime > 1380) {
      $endTime = 1380;
      $startTime = $endTime - $duration;
    }

    // Course title + class code (A/B)
    $title = $this->faker->randomElement($courses)
      . ' (' . $this->faker->randomElement(['A', 'B', 'C', 'D']) . ')';

    return [
      'title' => $title,
      'day' => $day,
      'startTime' => $startTime,
      'endTime' => $endTime,
      'color' => $this->randomDarkColor(),
      'admin_id' => $this->faker->numberBetween(1, 10),
    ];
  }

  /**
   * Generate dark color safe for white text
   */
  private function randomDarkColor(): string
  {
    do {
      $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    } while ($this->luminance($color) > 180);

    return $color;
  }

  /**
   * Perceived luminance
   */
  private function luminance(string $hex): float
  {
    $r = hexdec(substr($hex, 1, 2));
    $g = hexdec(substr($hex, 3, 2));
    $b = hexdec(substr($hex, 5, 2));

    return 0.299 * $r + 0.587 * $g + 0.114 * $b;
  }
}