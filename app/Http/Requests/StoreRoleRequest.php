<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\DTO\RoleDTO;

class StoreRoleRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user()->can('create-role');
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')],
            'code' => ['required', 'string', 'max:255', Rule::unique('roles')],
            'description' => ['nullable', 'string']
        ];
    }

    public function toDTO()
    {
        return new RoleDTO(
            $this->validated('name'),
            $this->validated('code'),
            $this->validated('description')
        );
    }
}
