<?php

namespace App\Http\Requests\Admin\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctor = $this->route('doctor');

        return [
            'name' => [
                'bail',
                'required',
                'string',
                'max:100',
                Rule::unique('doctors')->ignore($doctor?->id),
            ],
            'speciality_id' => 'required|exists:specialities,id',
            'national_code' => 'nullable|integer|digits_between:1,20|min:1',
            'medical_number' => 'nullable|integer|digits_between:1,191|min:1',
            'phone' => [
                'bail',
                'required',
                'string',
                'max:20',
                Rule::unique('doctors')->ignore($doctor?->id),
                'min:1',
            ],
            'password' => 'nullable|string|min:6',
            'status' => 'required|boolean',
            'roles' => 'sometimes|array',
            'roles.*' => 'bail|required|integer|min:1|exists:doctor_roles,id',
        ];
    }
}
