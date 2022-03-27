<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $review = Review::leftJoin('products','products.id','reviews.product_id')
                        ->select(
                            'reviews.id',
                            'reviews.product_id',
                            'reviews.rating',
                            'reviews.message',
                            'reviews.created_at',
                            'reviews.updated_at',
                            'products.title',
                            'products.slug',
                            'products.cover'
                        )
                        ->where('reviews.user_id',auth()->id())
                        ->paginate(20);
        return $review;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'product_id'=> 'required',
            'rating'    => 'required|numeric|lt:6',
            'message'   => 'required',
        ]);
        $review = new Review();
        DB::beginTransaction();
        try{
            $review->create([
                'product_id' => $request->product_id,
                'user_id' =>Auth::guard('sanctum')->id(),
                'rating' => $request->rating,
                'message' => $request->message,
            ]);
        DB::commit();
            return response()->json([
                'message'=>'Thank you for your review.'
            ]);

        }catch(\Exception $e){
            DB::rollback();
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
        //$review = Review::where('user_id',Auth::guard('sanctum')->id())->where
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
            'rating'    => 'required|numeric|lt:6',
            'message'   => 'required',
        ]);
        $review = Review::findOrFail($id);
        DB::beginTransaction();
        try{
            $review->update([
                'rating' => $request->rating,
                'message' => $request->message,
            ]);
        DB::commit();
            return response()->json([
                'message'=>'Review updated successfully.'
            ]);

        }catch(\Exception $e){
            DB::rollback();
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
        
        $review = Review::findOrFail($id);
        $review->delete();
        return response()->json([
            'message'=> 'Review deleted succefully',
        ]);
    }
}
