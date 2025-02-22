<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreRolePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }
    public function rules()
    {
        return [
            'permission_id' => 'required|exists:permissions,id',
        ];
    }
    public function toResource()
    {
        return new \App\DTO\RolePermissionDTO(
            role_id: null,
            permission_id: $this->input('permission_id')
        );
    }
}