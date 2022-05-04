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
            'category_id' =>'required',
            'title'=>'required | max:200',
            'description' => 'required',
            'short_description'=>'required',
            'product_images.*' => 'required|mimes:jpeg,png,jpg| image|max:10048',
            'cover' => 'required|mimes:jpeg,png,jpg| image|max:10048'
        ];
    }
}
