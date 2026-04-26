<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Course_Schedule;
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

    // Course title + class code (A/B/C/D)
    $title = $this->faker->randomElement($courses)
      . ' (' . $this->faker->randomElement(['A', 'B', 'C', 'D']) . ')';

    return [
      'title' => $title,
      'admin_id' => $this->faker->numberBetween(1, 10),
    ];
  }

  /**
   * Configure the factory to create 1-2 course schedules
   */
  public function configure()
  {
    return $this->afterCreating(function (Course $course) {
      // Decide how many schedules to create: 1 or 2
      $numberOfSchedules = $this->faker->randomElement([1, 2]);

      // Common start week for all schedules of this course
      $sharedStartWeek = $this->faker->numberBetween(1, 19);

      // Weekdays only: Monday (1) to Friday (5)
      $availableDays = [1, 2, 3, 4, 5];

      // Session pairs: 1-2, 3-4, 5-6, 7-8, 9-10, 9-11
      $sessionPairs = [
        ['start' => 1, 'end' => 2],
        ['start' => 3, 'end' => 4],
        ['start' => 5, 'end' => 6],
        ['start' => 7, 'end' => 8],
        ['start' => 9, 'end' => 10],
        ['start' => 9, 'end' => 11],
      ];

      $usedDays = [];

      for ($i = 0; $i < $numberOfSchedules; $i++) {
        // Pick a day that hasn't been used yet
        $remainingDays = array_diff($availableDays, $usedDays);
        $day = $this->faker->randomElement($remainingDays);
        $usedDays[] = $day;

        // Pick a random session pair
        $pair = $this->faker->randomElement($sessionPairs);

        // For the first schedule, pick an end week >= start week
        // For subsequent schedules (i > 0), they can end 1 week earlier
        if ($i === 0) {
          $endWeek = $this->faker->numberBetween($sharedStartWeek, 19);
        } else {
          // Later schedule can end up to 1 week earlier, but not before start week
          $maxEndWeek = max($sharedStartWeek, $endWeek - 1);
          $endWeek = $this->faker->numberBetween($sharedStartWeek, $maxEndWeek);
        }

        // Create the schedule
        Course_Schedule::create([
          'course_id' => $course->id,
          'day_of_week' => $day,
          'start_session_id' => $pair['start'],
          'end_session_id' => $pair['end'],
          'start_week' => $sharedStartWeek,
          'end_week' => $endWeek,
        ]);
      }
    });
  }
}