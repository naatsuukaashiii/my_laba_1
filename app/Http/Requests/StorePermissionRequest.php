<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StorePermissionRequest extends FormRequest
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
                'unique:permissions,name',
            ],
            'code' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'unique:permissions,code',
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
            'name.required' => 'Permission name is required.',
            'name.min' => 'Permission name must be at least 3 characters.',
            'name.max' => 'Permission name cannot exceed 50 characters.',
            'name.unique' => 'This permission name is already taken.',
            'code.required' => 'Permission code is required.',
            'code.min' => 'Permission code must be at least 3 characters.',
            'code.max' => 'Permission code cannot exceed 50 characters.',
            'code.unique' => 'This permission code is already taken.',
            'description.max' => 'Permission description cannot exceed 255 characters.',
        ];
    }
}