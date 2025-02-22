<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:roles,name',
            ],
            'code' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:roles,code',
            ],
            'description' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Role name is required.',
            'name.min' => 'Role name must be at least 3 characters.',
            'name.max' => 'Role name cannot exceed 50 characters.',
            'name.unique' => 'This role name is already taken.',
            'code.required' => 'Role code is required.',
            'code.min' => 'Role code must be at least 3 characters.',
            'code.max' => 'Role code cannot exceed 50 characters.',
            'code.unique' => 'This role code is already taken.',
            'description.max' => 'Role description cannot exceed 255 characters.',
        ];
    }
}