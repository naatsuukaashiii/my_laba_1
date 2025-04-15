<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-role');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('roles')->ignore($this->role)],
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('roles')->ignore($this->role)],
            'description' => ['nullable', 'string'],
        ];
    }

    public function toDTO()
    {
        return new \App\DTO\RoleDTO(
            $this->validated('name', isset($this->role) ? $this->role->name : null),
            $this->validated('code', isset($this->role) ? $this->role->code : null),
            $this->validated('description', isset($this->role) ? $this->role->description : null)
        );
    }
}
