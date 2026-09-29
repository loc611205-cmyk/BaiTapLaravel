<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Ten sinh vien la bat buoc',
            'phone.required' => 'SDT la bat buoc',
            'subject_id.required' => 'Mon hoc la bat buoc',
            'subject_id.exists' => 'Mon hoc khong ton tai',
        ];
    }
}

