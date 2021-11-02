<?php

namespace App\Http\Controllers\Admin;
use App\Models\Category;
// use App\Helper\Datatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
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
        $categories = Category::where('parent_id',null)->with('children')->get();
        //$categories = Datatable::filter($categories,['name']);
        return $categories;
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
        try{
            $message = "New category Added successfully !";
            DB::beginTransaction();
            $id = $request->get('id');
            if (isset($id) && $id != 'undefined') {
                $category = Category::findorfail($id);
                $category->update([
                    'parent_id'=> request()->get('parent_id'),
                    'name'=> request()->get('name'),
                    'icon'=>request()->get('icon'),
                    //'is_featured' => request()->get('is_featured')
                ]);
                $message = "Category updated successfully !";
            }else{
                $category = new Category();
                $category->create([
                    'parent_id'=> request()->get('parent_id'),
                    'name'=> request()->get('name'),
                    'icon'=>request()->get('icon'),
                    //'is_featured' => false
            ]);
            }
            DB::commit();
            return response()->json([
                'message'=>$message,
            ]);
        }catch(\Exception $exception){
            Log::channel('slack')->error($exception);
            DB::rollBack();
            return response()->json(array(
                'code' => 500,
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
        //
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

        $category->delete();
        return response()->json([
            'message'=>'Category deleted succefully',
        ],200);
    }
}
