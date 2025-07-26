<?php

namespace App\Http\Requests\Admin\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class DoctorStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string|max:100|unique:doctors',
            'speciality_id' => 'required|exists:specialities,id',
            'national_code' => 'nullable|integer|digits_between:1,20|min:1',
            'medical_number' => 'nullable|integer|digits_between:1,191|min:1',
            'phone' => 'bail|required|integer|max:20|unique:doctors|min:1',
            'password' => 'bail|required|string|min:6',
            'status' => 'required|boolean',
            'roles' => 'sometimes|array',
            'roles.*' => 'exists:doctor_roles,id',
            ];
    }
}
