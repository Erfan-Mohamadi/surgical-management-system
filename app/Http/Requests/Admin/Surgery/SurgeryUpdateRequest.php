<?php

namespace App\Http\Requests\Admin\Surgery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SurgeryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add auth logic if needed
    }

    public function rules(): array
    {
        $surgeryId = $this->route('surgery')->id ?? null;

        return [
            'patient_name' => 'required|string|max:100',
            'patient_national_code' => 'bail|required|integer|digits_between:1,20|min:1',
            'basic_insurance_id' => 'nullable|exists:insurances,id',
            'supp_insurance_id' => 'nullable|exists:insurances,id',
            'document_number' => [
                'bail',
                'required',
                'integer',
                Rule::unique('surgeries')->ignore($surgeryId),
                'min:1',
            ],
            'operation_id' => 'required|array',
            'operation_id.*' => 'exists:operations,id',
            'description' => 'nullable|string',
            'surgeried_at' => 'required|date',
            'released_at' => 'nullable|date|after_or_equal:surgeried_at',
            'doctor_roles' => 'required|array',
        ];
    }
}
