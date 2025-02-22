<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class DestroyUserRoleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }
    public function rules()
    {
        return [
            'role_id' => 'required|exists:roles,id',
        ];
    }
    public function toResource()
    {
        return new \App\DTO\UserRoleDTO(
            user_id: null,
            role_id: $this->input('role_id')
        );
    }
}