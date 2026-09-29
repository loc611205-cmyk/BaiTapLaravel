<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
{
    $query = Subject::query();

    if ($request->filled('q')) {
        $query->where('subject_name', 'like', '%' . $request->query('q') . '%');
    }

    if ($request->filled('credits')) {
        $query->where('credits', $request->query('credits'));
    }

    $allowedSorts = ['created_at', 'subject_name', 'credits'];

    $sort = in_array($request->query('sort'), $allowedSorts, true)
        ? $request->query('sort')
        : 'created_at';

    $order = $request->query('order', 'desc');

    $subjects = $query
        ->orderBy($sort, $order)
        ->paginate($request->integer('per_page', 10));

    return [
        'success' => true,
        'message' => 'Lay danh sach thanh cong',
        'data' => $subjects->items(),
        'meta' => [
            'total' => $subjects->total(),
            'per_page' => $subjects->perPage(),
            'current_page' => $subjects->currentPage(),
            'last_page' => $subjects->lastPage()
        ]
    ];
}

    public function show($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return [
                'success' => false,
                'message' => 'Khong tim thay tai nguyen',
                'errors' => null
            ];
        }

        return [
            'success' => true,
            'message' => 'Lay chi tiet thanh cong',
            'data' => $subject
        ];
    }

    public function update($id, Request $request)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return [
                'success' => false,
                'message' => 'Khong tim thay tai nguyen',
                'errors' => null
            ];
        }

        $data = $request->all();

        $subject->update([
            'subject_name' => $data['subject_name'],
            'credits' => $data['credits']
        ]);

        return [
            'success' => true,
            'message' => 'Cap nhat thanh cong',
            'data' => $subject
        ];
    }

    public function delete($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return [
                'success' => false,
                'message' => 'Khong tim thay tai nguyen',
                'errors' => null
            ];
        }

        $subject->delete();

        return [
            'success' => true,
            'message' => 'Xoa thanh cong',
            'data' => null
        ];
    }
}
