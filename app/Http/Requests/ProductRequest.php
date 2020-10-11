<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required|max:20|unique:products,product_name',
            'product_code' => 'required',
            'description' => 'required',
            'stock' => (int)'required|integer|min:1',
            'price' => (float)'required|numeric',
            'active' => (bool)'boolean',
            'category_id' => 'required|exists:categories,id'
        ];
    }
}
