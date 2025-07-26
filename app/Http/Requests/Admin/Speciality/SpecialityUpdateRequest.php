<?php

namespace App\Http\Requests\Admin\Speciality;

use Illuminate\Foundation\Http\FormRequest;

class SpecialityUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $speciality = $this->route('speciality');

        return [
            'title'  => 'required|string|max:191|unique:specialities,title,' . $speciality->id,
            'status' => 'boolean',
        ];
    }
}
