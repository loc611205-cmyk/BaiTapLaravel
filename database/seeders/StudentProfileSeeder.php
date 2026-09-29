<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;

class StudentProfileSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Student::all() as $student) {
            StudentProfile::factory()->create([
                'student_id' => $student->id
            ]);
        }
    }
}
