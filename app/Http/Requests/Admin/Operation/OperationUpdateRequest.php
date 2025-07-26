<?php

namespace App\Http\Requests\Admin\Operation;

use Illuminate\Foundation\Http\FormRequest;

class OperationUpdateRequest extends FormRequest
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
        $operation = $this->route('operation');

        return [
            'name' => 'required|string|max:100|unique:operations,name,'.$operation->id,
            'price' => 'required|integer|min:0',
            'status' => 'sometimes|boolean',
        ];
    }
}
