<?php

namespace App\Http\Requests\Admin\Surgery;

use Illuminate\Foundation\Http\FormRequest;

class SurgeryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow this request by default, or put your auth logic here
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_name' => 'required|string|max:100',
            'patient_national_code' => 'bail|required|integer|digits_between:1,20|min:1',
            'basic_insurance_id' => 'nullable|exists:insurances,id',
            'supp_insurance_id' => 'nullable|exists:insurances,id',
            'document_number' => 'bail|required|integer|unique:surgeries|min:1',
            'operation_id' => 'required|array',
            'operation_id.*' => 'exists:operations,id',
            'description' => 'nullable|string',
            'surgeried_at' => 'required|date',
            'released_at' => 'nullable|date|after_or_equal:surgeried_at',
            'doctor_roles' => 'required|array',
        ];
    }
}
