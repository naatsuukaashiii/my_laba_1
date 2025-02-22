<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class UpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                'unique:permissions,name,' . $this->route('permission'),
            ],
            'code' => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                'unique:permissions,code,' . $this->route('permission'),
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
            'name.min' => 'Permission name must be at least 3 characters.',
            'name.max' => 'Permission name cannot exceed 50 characters.',
            'name.unique' => 'This permission name is already taken.',
            'code.min' => 'Permission code must be at least 3 characters.',
            'code.max' => 'Permission code cannot exceed 50 characters.',
            'code.unique' => 'This permission code is already taken.',
            'description.max' => 'Permission description cannot exceed 255 characters.',
        ];
    }
}