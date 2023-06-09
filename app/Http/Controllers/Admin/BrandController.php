<?php

namespace App\Http\Controllers\Admin;
use App\Models\Brand;
use App\Helper\Datatable;
use Illuminate\Support\Facades\Storage;
use App\Http\Helper\MediaHelper;
use App\Http\Resources\Admin\BrandResource;
use App\Http\Requests\BrandRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $brand = Brand::whereHas('category', function($q) {
            $q->where('deleted_at',null);
         })->with('category')->latest();
        $brand = Datatable::filter($brand,['name']);

        return  BrandResource::collection($brand)->response()
        ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        //return response()->json($request->all(),500);
        if(!isset($request->category_id) ||$request->category_id == 'undefined' || $request->category_id == 'NULL'){
            return response()->json([
                'message'=>'invalide value',
                'errors'=>[
                    'category_id'=>'undefined category id'
                ]
            ],422);
        }
        
        $logo = null;
        $id = $request->get('id');
        
        
        if(isset($id)){
            $brand = Brand::findOrFail($id);
            if(isset($request->logo)){
                Storage::delete('/brand/'.$brand->logo);
                Storage::delete('/thumb/'.$brand->logo);
                $this->validate($request,[
                    'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                ]);    
                $mediaHelper = new MediaHelper;
                $logo =  $mediaHelper->storeMedia($request->logo,'brand',true,false,true);
            }else{
                $logo = $brand->logo;
            }
            

            $brand->update([
                'name'=>$request->get('name'),
                'logo' => $logo,
                'category_id' => $request->get('category_id'),
            ]);
        }else{
            $brand = new Brand();
            if(isset($request->logo)){
                $this->validate($request,[
                    'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                ]);    
                $mediaHelper = new MediaHelper;
                $logo =  $mediaHelper->storeMedia($request->logo,'brand',true,false,true);
            }
            $brand->create([
                'name'=>$request->get('name'),
                'logo' => $logo,
                'category_id' => $request->get('category_id'),

            ]);
        }
        
        return response()->json([
            'message'=>'New brand added succefully.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand,200);
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
        $brand = Brand::withCount('product')->findOrFail($id);
        if($brand->product_count>0){
            return response()->json([
                'message'=>'Forbidden : this brand has '.$brand->product_count.' products',
            ],422);
        }
        $brand->delete();
        return response()->json([
            'brand'=>$brand,
            'message'=>'Brand Deleted successfully'
        ]);
    }
}
