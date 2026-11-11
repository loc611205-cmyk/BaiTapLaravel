<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('subject');

        if ($request->filled('q')) {
            $query->where(
                'full_name',
                'like',
                '%' . $request->query('q') . '%'
            );
        }

        if ($request->filled('subject_id')) {
            $query->where(
                'subject_id',
                $request->query('subject_id')
            );
        }

        $allowedSorts = [
            'created_at',
            'full_name',
            'phone'
        ];

        $sort = in_array(
            $request->query('sort'),
            $allowedSorts,
            true
        )
            ? $request->query('sort')
            : 'created_at';

        $order = $request->query('order', 'desc');

        $students = $query
            ->orderBy($sort, $order)
            ->paginate(
                $request->integer('per_page', 10)
            );

        return [
            'success' => true,
            'message' => 'Lay danh sach thanh cong',
            'data' => $students->items(),
            'meta' => [
                'total' => $students->total(),
                'per_page' => $students->perPage(),
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage()
            ]
        ];
    }

    public function store(StudentRequest $request)
    {
        $data = $request->validated();

        $student = Student::create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'subject_id' => $data['subject_id']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tao thanh cong',
            'data' => $student
        ], 200);
    }

    public function show($id)
    {
        $student = Student::with('subject')->find($id);

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

    public function update($id, StudentRequest $request)
    {
        $student = Student::find($id);

        if (!$student) {
            return [
                'success' => false,
                'message' => 'Khong tim thay tai nguyen',
                'errors' => null
            ];
        }

        $data = $request->validated();

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




