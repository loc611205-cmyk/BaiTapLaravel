<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject_name' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }
    public function messages(): array
    {
        return [
            'subject_name.required' => 'Ten mon hoc la bat buoc',
            'subject_name.string' => 'Ten mon hoc phai la chuoi',
            'subject_name.max' => 'Ten mon hoc khong duoc qua 255 ky tu',
            'credits.required' => 'So tin chi la bat buoc',
            'credits.integer' => 'So tin chi phai la so nguyen',
            'credits.min' => 'So tin chi toi thieu la 1',
            'credits.max' => 'So tin chi toi da la 5',
        ];
    }

}
