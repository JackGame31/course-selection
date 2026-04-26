<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course_Schedule>
 */
class CourseScheduleFactory extends Factory
{
  /**
   * Available session pairs: 1-2, 3-4, 5-6, 7-8, 9-10, 9-11
   */
  private static array $sessionPairs = [
    ['start' => 1, 'end' => 2],
    ['start' => 3, 'end' => 4],
    ['start' => 5, 'end' => 6],
    ['start' => 7, 'end' => 8],
    ['start' => 9, 'end' => 10],
    ['start' => 9, 'end' => 11],
  ];

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    // Weekdays only: Monday (1) to Friday (5)
    $weekdays = [1, 2, 3, 4, 5];
    $pair = $this->faker->randomElement(self::$sessionPairs);
    $startWeek = $this->faker->numberBetween(1, 19);
    $endWeek = $this->faker->numberBetween($startWeek, 19);

    return [
      'day_of_week' => $this->faker->randomElement($weekdays),
      'start_session_id' => $pair['start'],
      'end_session_id' => $pair['end'],
      'start_week' => $startWeek,
      'end_week' => $endWeek,
      'course_id' => null, // Will be set by CourseFactory
    ];
  }

  /**
   * Create a schedule with a specific start week and day
   */
  public function forCourseWithWeekAndDay($startWeek, $day, $endWeek = null)
  {
    return $this->state(function (array $attributes) use ($startWeek, $day, $endWeek) {
      return [
        'day_of_week' => $day,
        'start_week' => $startWeek,
        'end_week' => $endWeek ?? $this->faker->numberBetween($startWeek, 19),
      ];
    });
  }
}
