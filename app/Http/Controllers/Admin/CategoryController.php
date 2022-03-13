<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
use App\Helper\Datatable;
use App\Models\CategoryOption;
use App\Http\Helper\MediaHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\Admin\CategorySelectResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('parent_id',null)
        ->with('children.children','categoryOptions')
        ->orderBy('id','desc')->get();
        // return $categories;
        // $categories = Datatable::filter($categories,['name']);
        return  CategoryResource::collection($categories)->response()
        ->setStatusCode(200);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //return $request->all();
        $parent_id = $request->has('parent_id')?(int) $request->get('parent_id'):null;
        $category_image = $request->file('image')?$request->get('image'):null;
        
        if($parent_id == 0){
            $parent_id = null;
        }
        $category_image = null;
        
        if($request->file('image')){
            $cat_id= $request->get('id');
            $cover = $request->get('cover')?$request->get('cover'):null;
            
            $this->validate($request,[
                'image' => 'image|mimes:jpeg,png,jpg|max:5048',
            ]); 
            if(isset($cat_id) && $request->get('id') != 'undefined'){
                
                if(isset($cover) && $request->cover != null && $request->cover != 'null'){
                    //return "found cover";
                    Storage::delete('/category/'.$request->cover);
                    Storage::delete('/thumb/'.$request->cover);
                }
                //return "found id";
            }  
            //return "nothing"; 
            $mediaHelper = new MediaHelper;
            $category_image =  $mediaHelper->storeMedia($request->image,'category',true,false,true);
        }
        
        $level = 1;
        try{
            DB::beginTransaction();
            if(isset($parent_id) && $parent_id != 'undefined'){
                $cat = Category::where('id',$parent_id)->first();
                if($cat){
                    $level = $level+1;
                    $cat->last_child = false;
                    $cat->update();
                }
            }
            
            
            $message = "New category Added successfully !";
            
            $id = $request->get('id');
            if (isset($id) && $id != 'undefined') {
                $category = Category::findorfail($id);
                $data = [
                    'parent_id'=> $parent_id,
                    'name'=> request()->get('name'),
                    'icon'=>request()->get('icon'),
                    
                    'lvl' => (int) $level
                ];
                if($request->file('image')){
                    $data['cover']= $category_image;
                }
                $category->update($data);

            
            
            $message = "Category updated successfully !";
            }else{
                $category = new Category();
                
                $category->create([
                    'parent_id'=> $parent_id,
                    'name'=> request()->get('name'),
                    'icon'=>request()->get('icon'),
                    'lvl' => $level,
                    'cover'=> $category_image,
                ]);
                
                
            }
            DB::commit();
            cache()->forget('public-category');
            return response()->json([
                'message'=>$message,
            ]);
        }catch(\Exception $exception){

            Log::channel('slack')->error($exception);
            DB::rollBack();
            return $exception;
            return response()->json(array(
                'code' => 500,
                'error'=> $exception,
                'message' => 'something went wrong'
            ), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = CategoryOption::where('category_id',$id)
        ->select('category_id','option_id')
        ->get();
        return $category->map(function($item){
            return $item->option_id;
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::with('children')->findOrFail($id);
        //return $category;
        if($category->children->count()>0){
            return response()->json([
                'error'=>'Please delete subcategory before deleting parent category'
            ],422);
        }
        if($category->cover){
            Storage::delete('/category/'.$category->cover);
            Storage::delete('/thumb/'.$category->cover);
        }
        

        $category->delete();
        cache()->forget('public-category');
        return response()->json([
            'message'=>'Category deleted succefully',
        ],200);
    }


    public function getParentData(){
        // select option in create category table
        $category = Category::where('parent_id',null)
        ->select('id','parent_id','name','last_child')
        ->with('children:id,parent_id,name')->orderBy('name','asc')->get();
        //return $category;
        return  CategorySelectResource::collection($category)->response()
        ->setStatusCode(200);
        //return response()->json($category,200);
    }
    public function getSelectableCategory(){
        $category = Category::where('last_child',true)
        ->select('id','parent_id','has_color','has_size','name','last_child','lvl')
        ->with(['parent'=>function($query){
            
            $query->select('id','parent_id','name')->with(['parent:id,parent_id,name']);
        }])->orderBy('parent_id','asc')->get();
        // $category = Category::where('id',9)->with('parent.parent')->first();
        return response()->json($category,200);
    }
}
