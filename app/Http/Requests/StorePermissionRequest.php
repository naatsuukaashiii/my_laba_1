<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\DTO\Permission\PermissionDTO;

class StorePermissionRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user() ? $this->user()->can('create-permission') : false;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')],
            'code' => ['required', 'string', 'max:255', Rule::unique('permissions')],
            'description' => ['nullable', 'string'],
        ];
    }

    public function toDTO()
    {
        $validated = $this->validated();

        return new PermissionDTO(
            (string) $validated['name'],
            (string) $validated['code'],
            isset($validated['description']) ? (string) $validated['description'] : null
        );
    }
}
