<?php

namespace App\Http\Controllers;
use App\Models\Option;
use App\Models\Category;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function options(){
        $options = Option::all();
        return response()->json($options);
    }

    public function updateCategoryOption(Request $request){
        //return $request->all();
        $has_color = $request->has('has_color')?$request->has_color:false;
        $id = $request->get('id');
        $has_size = $request->has('has_size')?$request->has_size:false;
        $options = $request->has('category_options')?$request->get('category_options'):[];
        $category = Category::where('id',$id)->first();
        $category->has_color = $has_color;
            $category->has_size = $has_size;
            $category->update();
        
        if(isset($options) && $category){
            
            $category->options()->sync($options);
        }

        return response()->json([
            'message'=> 'Category option updated successfully',
            'code' => 200
        ],200);

    }
}
