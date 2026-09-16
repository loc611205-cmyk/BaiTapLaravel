<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::paginate();

        return [
         'success' => true,
        'message' => 'Lay danh sach thanh cong',
        'data' => $subjects->items()
        ];
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $subject = Subject::create([
            'subject_name' => $data['subject_name'],
            'credits' => $data['credits']
        ]);

        return [
            'success' => true,
            'message' => 'Tao thanh cong',
            'data' => $subject
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
