<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradeResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=TestDataSeeder --env=testing');
    }

    public function test_validates_grade_form_input_correctly()
    {
        $superAdmin = User::where('email', 'superadmin@test.com')->first();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacher = Teacher::factory()->create();

        $this->actingAs($superAdmin);

        // Test valid submission
        $response = $this->post('/admin/grades', [
            'data' => [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'mark' => 85,
            ],
        ]);
        $response->assertRedirect();

        // Test invalid submission (mark too high)
        $response = $this->post('/admin/grades', [
            'data' => [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'mark' => 101,
            ],
        ]);
        $response->assertSessionHasErrors(['data.mark' => 'The grade must not exceed 100.']);

        // Test invalid submission (non-integer)
        $response = $this->post('/admin/grades', [
            'data' => [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'mark' => 'abc',
            ],
        ]);
        $response->assertSessionHasErrors(['data.mark' => 'The grade must be an integer.']);

        // Test required field
        $response = $this->post('/admin/grades', [
            'data' => [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
            ],
        ]);
        $response->assertSessionHasErrors(['data.mark' => 'The grade is required.']);
    }

    public function test_restricts_access_based_on_user_roles()
    {
        $superAdmin = User::where('email', 'superadmin@test.com')->first();
        $teacher = User::where('email', 'teacher@test.com')->first();
        $student = User::where('email', 'student@test.com')->first();
        $studentModel = Student::factory()->create();
        $subject = Subject::factory()->create();
        $teacherModel = Teacher::factory()->create();

        // Super Admin can access and create
        $this->actingAs($superAdmin);
        $response = $this->get('/admin/grades');
        $response->assertOk();

        $response = $this->post('/admin/grades', [
            'data' => [
                'student_id' => $studentModel->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacherModel->id,
                'mark' => 85,
            ],
        ]);
        $response->assertRedirect();

        // Teacher can access and create
        $this->actingAs($teacher);
        $response = $this->get('/admin/grades');
        $response->assertOk();

        $response = $this->post('/admin/grades', [
            'data' => [
                'student_id' => $studentModel->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacherModel->id,
                'mark' => 75,
            ],
        ]);
        $response->assertRedirect();

        // Student can view but not create
        $this->actingAs($student);
        $response = $this->get('/admin/grades');
        $response->assertOk(); // Assuming view_any permission allows list access

        $response = $this->post('/admin/grades', [
            'data' => [
                'student_id' => $studentModel->id,
                'subject_id' => $subject->id,
                'teacher_id' => $teacherModel->id,
                'mark' => 90,
            ],
        ]);
        $response->assertForbidden();
    }
}
