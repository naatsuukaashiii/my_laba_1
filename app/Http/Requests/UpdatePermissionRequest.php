<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-permission');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('permissions')->ignore($this->permission)],
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('permissions')->ignore($this->permission)],
            'description' => ['nullable', 'string'],
        ];
    }

    public function toDTO()
    {
        return new \App\DTO\PermissionDTO(
            $this->validated('name', $this->permission->name),
            $this->validated('code', $this->permission->code),
            $this->validated('description', $this->permission->description)
        );
    }
}
