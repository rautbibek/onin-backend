<?php

namespace App\Http\Controllers;
use App\Models\Option;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Helper\Datatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Admin\OptionResource;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function options(){
        $options = Option::all();
        return response()->json($options);
    }
    public function index()
    {
        $options = Option::latest();
        $options = Datatable::filter($options,['key','code','type']);
        return  OptionResource::collection($options)->response()
        ->setStatusCode(200);
    }

    public function save(Request $request){
        // return response()->json($request->all(),500);
        $this->validate($request,[
            'type'=>'required',
            'name' => 'required',
            'values' => 'required_if:type,select'
        ]);
        
        $filterable = $request->has('is_filterable')?$request->get('is_filterable'):false;
        
        
        if(!isset($filterable) && $filterable == 'null'){
            $filterable = false;
        }
        
        $id = $request->id;
        $message = "";
        $slug = Str::slug($request->name, '_');
        
        try{
            DB::beginTransaction();
            if(isset($id)){
                $option = Option::findOrFail($id);
                $option->code = $request->name;
                
                $option->type = $request->type;
                $option->key = $slug;
                if($request->type != 'select'){
                    $option->values = [];
                    $option->is_filterable = false;
                }else{
                    $option->values = $request->values;
                    $option->is_filterable = $request->is_filterable;
                }
                $option->update();
                $message = "Category option updated successfully";
            }else{
                $option = new Option();
                $option->code = $request->name;
                //$option->is_filterable = $request->is_filterable;
                $option->type = $request->type;
                $option->key = $slug;
                if($request->type != 'select'){
                    
                    $option->is_filterable = false;
                }else{
                    
                    $option->is_filterable = $request->is_filterable;
                }
                $option->values = $request->values;
                $option->save();
                $message = "New category option created successfully";
            }
            DB::commit();
            return response()->json([
                'message'=> $message
            ],200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(array(
                'code' => $e,
                'message' => 'something went wrong'
            ), 500);
        }
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

    public function delete($id){
        
        try{
            $option = Option::findOrFail($id);
            $option->delete();
            return response()->json([
                'message'=> 'Category option deleted successfully'
            ]);
        }catch(\Exception $e){
            return response()->json(array(
                'code' => 500,
                'message' => 'Cannot delet the option  option used by various categories'
            ), 500);
        }
        $option->delete();

        
    }

    
}
