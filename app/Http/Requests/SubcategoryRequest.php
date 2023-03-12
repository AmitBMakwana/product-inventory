<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubcategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'cat_id' => 'required|exists:categories,id',
            'name' => 'required|min:5|max:20|unique:subcategories,name',
            'status' => 'required|in:active,inactive',
        ];

        if ($this->getMethod() == 'PATCH') {
            $rules['name'] .= ',' . $this->route('subcategory')->id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'cat_id.required' => 'The category field is required.',
            'cat_id.exists' => 'The selected category is invalid.',
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 5 characters.',
            'name.max' => 'The name may not be greater than 20 characters.',
            'name.unique' => 'The name has already been taken.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status field must be either active or inactive.',
        ];
    }
}