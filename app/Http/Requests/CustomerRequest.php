<?php


namespace App\Http\Controllers\Api\Shop;


use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email',
            'photo' => 'image|max:' . (3 * 2014),
            'cidade_id' => 'required|exists:cidade,id',
            'address' => 'required',
            'number' => 'required|numeric',
            'province' => 'required',
            'cpf' => 'requirerd',
            'mobile' => 'required'
        ];
    }
}
