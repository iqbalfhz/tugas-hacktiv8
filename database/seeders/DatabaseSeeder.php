<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ]);
        //create 1 academic year 2024
        \App\Models\AcademicYear::factory()->create([
            'year' => '2024',
        ]);

        //create 2 levels 1 and 2
        \App\Models\Level::factory()->create([
            'name' => '1',
        ]);
        \App\Models\Level::factory()->create([
            'name' => '2',
        ]);

        //create 2 classrooom Andalusia and Cordoba
        \App\Models\Classroom::factory()->create([
            'name' => 'Andalusia',
            'academic_year_id' => 1,
            'level_id' => 1,
        ]);
        \App\Models\Classroom::factory()->create([
            'name' => 'Cordoba',
            'academic_year_id' => 1,
            'level_id' => 2,
        ]);

        //create 2 courses Motorik Kasar and Motorik Halus
        \App\Models\Course::factory()->create([
            'name' => 'Motorik Kasar',
        ]);

        \App\Models\Course::factory()->create([
            'name' => 'Motorik Halus',
        ]);

        //create 2 students Budi and Siti
        \App\Models\Student::factory()->create([
            'name' => 'Budi',
            'user_id' => 1,
        ]);

        \App\Models\Student::factory()->create([
            'name' => 'Siti',
            'user_id' => 1,
        ]);

        //create 1 student Didi
        \App\Models\Student::factory()->create([
            'name' => 'Didi',
            'user_id' => 2,
        ]);

        //enroll Budi and Situ to Andalusia
        \App\Models\Enrollment::factory()->create([
            'student_id' => 1,
            'classroom_id' => 1,
        ]);

        \App\Models\Enrollment::factory()->create([
            'student_id' => 2,
            'classroom_id' => 1,
        ]);

        \App\Models\Enrollment::factory()->create([
            'student_id' => 3,
            'classroom_id' => 2,
        ]);

        //create one Motorik Halus course session
        \App\Models\CourseSession::factory()->create([
            'name' => 'Motorik Halus Pertemuan 1',
            'course_id' => 2,
            'classroom_id' => 1,
        ]);

        //call BookSeeder
        $this->call(
            [
                BookSeeder::class,
                ContactSeeder::class,
            ]
        );
    }
}
