<?php

namespace App\Http\Requests;

use App\DTO\RolesPermissions\RolesPermissionsDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRolesPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-role-permission');
    }

    public function rules(): array
    {
        return [
            'role_id' => ['sometimes', 'integer', Rule::exists('roles', 'id')],
            'permission_id' => ['sometimes', 'integer', Rule::exists('permissions', 'id')],
        ];
    }

    public function toDTO()
    {
        return new \App\DTO\RolesPermissionsDTO(
            $this->validated('role_id', isset($this->rolePermission) ? $this->rolePermission->role_id : null),
            $this->validated('permission_id', isset($this->rolePermission) ? $this->rolePermission->permission_id : null)
        );
    }
}
