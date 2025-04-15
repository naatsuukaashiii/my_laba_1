<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\DTO\RolesPermissions\RolesPermissionsDTO;

class StoreRolesPermissionsRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('assign-permission');
    }

    public function rules()
    {
        return [
            'role_id' => ['required', 'integer', Rule::exists('roles', 'id')],
            'permission_id' => ['required', 'integer', Rule::exists('permissions', 'id')]
        ];
    }

    public function toDTO()
    {
        return new RolesPermissionsDTO(
            $this->validated('role_id'),
            $this->validated('permission_id')
        );
    }
}
