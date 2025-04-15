<?php

namespace App\Http\Requests;

use App\DTO\Role\RoleDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-user-role');
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'integer', Rule::exists('users', 'id')],
            'role_id' => ['sometimes', 'integer', Rule::exists('roles', 'id')],
        ];
    }

    public function toDTO()
    {
        $user_id = $this->validated('user_id', isset($this->userRole) ? $this->userRole->user_id : null);
        $role_id = $this->validated('role_id', isset($this->userRole) ? $this->userRole->role_id : null);

        return new \App\DTO\Role\RoleDTO($user_id, $role_id);
    }
}
