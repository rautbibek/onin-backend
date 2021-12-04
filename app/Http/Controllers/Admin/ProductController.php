<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\ProductTag;
use App\Http\Controllers\Controller;
use App\Models\OptionValue;
use App\Http\Helper\MediaHelper;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //return response()->json($request->all());
        //return gettype($request->product_images);
        $images=[];
            if($request->hasFile('product_images')){

                foreach($request->product_images as $image){
                    $mediaHelper = new MediaHelper;
                    $images= $mediaHelper->storeMedia($image,'product',true,false);
                }
            }
        return $images;
        try{
            DB::beginTransaction();
            $product = new product();
            $product->title = $request->title;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->meta_keyword = $request->get('meta_tags');
            $product->meta_title = $request->get('meta_title');
            $product->meta_description = $request->get('meta_description');
            $product->save();

            $product_option_values = json_decode($request->option_values);

            if(!empty($product_option_values)){
                foreach($product_option_values as $key=>$options){
                    $option_value = new OptionValue();
                    $option_value->product_id = $product->id;
                    $option_value->option = $key;
                    $option_value->option_value = $options;
                    $option_value->save();
                }
            }
            if(!empty($request->product_tags)){
                foreach($request->product_tags as $tag){
                    $product_tag= new ProductTag();
                    $product_tag->product_id = $product->id;
                    $product_tag->tag = $tag;
                    $product_tag->save();
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'New product added successfully !'
            ]);

        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'error' =>$e->getMessage()
            ], 202);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }
}
