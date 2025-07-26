<?php

namespace App\Http\Requests\Admin\Insurance;

use Illuminate\Foundation\Http\FormRequest;

class InsuranceUpdateRequest extends FormRequest
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
        $insurance = $this->route('insurance');

        return [
            'name' => 'bail|required|string|max:100|unique:insurances,name,'.$insurance->id,
            'type' => 'required|in:basic,supplementary',
            'discount' => 'required|integer|min:0|max:100',
            'status' => 'boolean',
        ];
    }
}
