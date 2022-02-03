<?php

namespace App\Http\Controllers\Admin;
use App\Models\Brand;
use App\Helper\Datatable;
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

        $brand = Brand::with('category');
        $brand = Datatable::filter($brand,['name']);

        return  BrandResource::collection($brand)->response()
        ->setStatusCode(200);;
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
        $brand = new Brand();
        $brand->create([
            'name'=>$request->get('name'),
            'category_id' => $request->get('category_id'),
           // 'logo' => $request->get('logo'),
           // 'description' => $request->get('description'),
        ]);
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
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response()->json([
            'brand'=>$brand,
            'message'=>'Brand Deleted successfully'
        ]);
    }
}
