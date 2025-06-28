<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseSession;
use Illuminate\Database\Seeder;

class CourseSessionSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        $classrooms = Classroom::all();

        if ($courses->count() > 0 && $classrooms->count() > 0) {
            // Create sessions with existing courses and classrooms
            foreach ($courses as $course) {
                foreach ($classrooms->random(rand(1, 3)) as $classroom) {
                    CourseSession::factory()->create([
                        'course_id' => $course->id,
                        'classroom_id' => $classroom->id,
                    ]);
                }
            }
        } else {
            // Create sessions with new courses and classrooms
            CourseSession::factory(10)->create();
        }
    }
}
