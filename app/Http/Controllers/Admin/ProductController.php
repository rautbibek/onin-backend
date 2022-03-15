<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ProductTag;
use App\Http\Controllers\Controller;
use App\Models\OptionValue;
use App\Helper\Datatable;
use App\Http\Helper\MediaHelper;
use App\Http\Resources\Admin\EditProductResource;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Variant;
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
        $product = Product::with(['category:id,name','brand:id,name','variant','optionValues'])->latest();
        $product = Datatable::filter($product,['title','search_text','discount','discount_type']);
        return  ProductResource::collection($product)->response()
        ->setStatusCode(200);
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
        //return response()->json($request->all(),500);
        $slug = Str::slug($request->title);
        $discount_type = $request->has('discount_value')?$request->get('discount_value'):null;
        $discount = $request->has('discount')?$request->get('discount'):null;
        $brand_id = $request->has('brand_id')?$request->get('brand_id'):null;
        
        try{
            DB::beginTransaction();
            $search_text = $request->get('search_text');
            $status = $request->get('status');
            $cover = MediaHelper::saveProductImage($request->cover,'product', $slug);
            $product = new product();
            $product->title = $request->title;
            $product->category_id = $request->category_id;
            $product->cover = $cover;
            if(isset($discount_type) && isset($discount)){
                $product->discount_type = $discount_type;
                $product->discount = (int) $discount;
            }
            if($request->has_color == "true"){
                $product->has_color = true;
            }else{
                $product->has_color = false;
            }
            if($request->has_size == "true"){
                $product->has_size = true;
            }else{
                $product->has_size = false;
            }
            if(isset($search_text)){
                $product->search_text = $request->search_text;
            }
            if($status == true || $status == 'true'){
                $product->status = true;
            }else{
                $product->status = false;
            }

            $product->brand_id = $brand_id;
            $product->description = $request->description;
            $product->short_description = $request->short_description;

            $product->meta_keyword = $request->get('meta_tags');
            $product->meta_title = $request->get('meta_title');
            $product->meta_description = $request->get('meta_description');
            // if(!empty($images)){
            //     $product->image = $images;
            // }
            $product->save();

            $product_option_values = json_decode($request->option_values);

            $product_attribute = json_decode($request->product_atributes);
            if($request->hasFile('product_images')){
                
                foreach($request->product_images as $image){
                    $file = MediaHelper::saveProductImage($image,'product', $product->slug);
                    $product->images()->create([
                        'file'=> $file,
                        'size'=> $image->getSize(),
                    ]);
                    
                }
            }

            if(!empty($product_option_values)){
                foreach($product_option_values as $key=>$options){
                    $option_value = new OptionValue();
                    $option_value->product_id = $product->id;
                    $option_value->option = $key;

                    $option_value->option_value = $options;
                    $option_value->save();
                }
            }


            if($request->has('product_collection')){
                $collection = $request->get('product_collection');
                $product->collection()->sync($collection);
            }
            if(!empty($product_attribute)){
                $qty = 0;
                foreach($product_attribute as $key => $attribute){
                    $p_variant = new Variant();

                    $p_variant->product_id = $product->id;
                    $p_variant->color = $attribute->color;
                    $p_variant->quantity = $attribute->stock;
                    $p_variant->price = $attribute->price;
                    $p_variant->sku = $attribute->sku;
                    //$p_variant->special_price = $attribute->special_price;
                    if(!empty($attribute->sizes)){
                        $p_variant->sizes = $attribute->sizes;
                        //return response()->json(gettype($attribute->sizes),500);
                    }
                    //return response()->json('error',500);
                    $p_variant->save();
                    $qty += $attribute->stock;
                }
                // $product->update([
                //     'inventory_track'=> $qty
                // ]);
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
            ], 500);
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
        $product = Product::with(['category:id,name,has_color,has_size','images'=>function($q){
            $q->select('id','product_id','file','size');
        },'variant','optionValues','collection:id'])
                           ->where('id',$id)
                           ->firstOrFail();
        return new EditProductResource($product);
        //return response()->json($product,200);
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
        
        $this->validate($request,[
            'title'=>'required | max:200',
            'search_text'=>'required|max:200',
            //'brand_id' =>'required',
            'short_description'=>'required',
            'description'=>'required'
        ]);

        $brand_id = $request->has('brand_id')?$request->get('brand_id'):null;
        $product = Product::findOrFail($id);
        $discount_type = $request->get('discount_value');
        $discount = $request->get('discount');
        $search_text = $request->get('search_text');
        if(isset($search_text)){
            $product->search_text = $search_text;
        }
        if(isset($discount_type) && isset($discount)){
            $product->discount_type = $discount_type;
            $product->discount = (int) $discount;
        }
        $product->meta_keyword = $request->get('meta_tags');
        $product->meta_title = $request->get('meta_title');
        $product->meta_description = $request->get('meta_description');
        $product->title = $request->title;
        $product->brand_id = $brand_id;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->meta_keyword = $request->get('meta_tags');
        $product->meta_title = $request->get('meta_title');
        $product->meta_description = $request->get('meta_description');
        $product->update();
        if($request->has('collection')){
            $collection = $request->get('collection');
            $product->collection()->sync($collection);
        }
        return response()->json([
            'message'=>'Basic information updated successfully'
        ],200);
    }

    public function updateProductOptions(Request $request){
        return response()->json($request->all(),500);
    }

    public function updateProductImage(Request $request){
        $this->validate($request,[
            'product_images' => 'required',
            'product_images.*' => 'mimes:jpeg,jpg,png,webp|max:5048'
        ]);
        try{
            $product = Product::findOrFail($request->id);
            DB::beginTransaction();
            if($request->hasFile('product_images')){
                
                foreach($request->product_images as $image){
                    $file = MediaHelper::saveProductImage($image,'product', $product->slug);
                    $product->images()->create([
                        'file'=> $file,
                        'size'=> $image->getSize(),
                    ]);
                    
                }
                DB::commit();
                return response()->json([
                    'message' => 'Image updated succefully'
                ]);

            }
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
                'error' =>$e->getMessage()
            ], 500);
        }
       
    }

    public function removeProductImage($id){
        try{
            $image = ProductImage::findOrFail($id);
        
            if (Storage::disk('public')->exists('product/'.$image->file)) {
                Storage::delete('product/'.$image->file);
            }

            if (Storage::disk('public')->exists('thumb/'.$image->file)) {
                Storage::delete('thumb/'.$image->file);
            }
            $image->delete();
            
            return response()->json([
                'message'=> 'Image deleted successfully.'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong',
                'error' =>$e->getMessage()
            ], 500);
        }
        
    }

    public function updateCover(Request $request,$id){
        $this->validate($request,[
            'cover' => 'required|mimes:jpeg,jpg,png,webp|max:5048'
        ]);
        try{
            $product = Product::findOrFail($id);
             Storage::delete('/product/'.$product->cover);
             Storage::delete('/thumb/'.$product->cover);
            //  return $file;

            DB::beginTransaction();
            $file = MediaHelper::saveProductImage($request->cover,'product', $product->slug);
            $product->update([
                'cover'=> $file,
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Cover updated succefully'
            ]);

            
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
                'error' =>$e->getMessage()
            ], 500);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'message'=>'Product deleted succefully'
        ]);
    }


    public function updateStatus($id){
        $product = Product::findOrFail($id);
        //return $product->status;
        $status = true;
        if($product->status == true){
            $status = false;
        }

       $product->update([
           'status'=>$status
       ]);
    }
}
