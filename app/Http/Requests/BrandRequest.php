<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
        $id = $this->id;
        $category_id = $this->category_id;
        //dd($category_id);
        $rules = [
            'category_id'=>'required',
        ];
        if(isset($id)){
            $rules = [
                // 'category_id'=>'required',
                'name'=> 'required | unique:brands,id,'.$id,
                //'logo'=>'sometimes | mimes:jpeg,png,jpg,svg|max:2048',
            ];
        }else{
            $rules =[
                //'logo'=>'required | mimes:jpeg,png,jpg,svg|max:2048',
                'name'=> 'required',
            ];
        }

        return $rules;
    }
}
