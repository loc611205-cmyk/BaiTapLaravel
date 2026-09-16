<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::paginate();
    return [
        'success' => true,
        'message' => 'Lay danh sach thanh cong',
        'data' => $students->items()
    ];
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $student = Student::create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'subject_id' => $data['subject_id']
        ]);

        return [
            'success' => true,
            'message' => 'Tao thanh cong',
            'data' => $student
        ];
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return [
                'success' => false,
                'message' => 'Khong tim thay tai nguyen',
                'errors' => null
            ];
        }

        return [
            'success' => true,
            'message' => 'Lay chi tiet thanh cong',
            'data' => $student
        ];
    }

    public function update($id, Request $request)
    {
        $student = Student::find($id);

        if (!$student) {
            return [
                'success' => false,
                'message' => 'Khong tim thay tai nguyen',
                'errors' => null
            ];
        }

        $data = $request->all();

        $student->update([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'subject_id' => $data['subject_id']
        ]);

        return [
            'success' => true,
            'message' => 'Cap nhat thanh cong',
            'data' => $student
        ];
    }

    public function delete($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return [
                'success' => false,
                'message' => 'Khong tim thay tai nguyen',
                'errors' => null
            ];
        }

        $student->delete();

        return [
            'success' => true,
            'message' => 'Xoa thanh cong',
            'data' => null
        ];
    }
}
