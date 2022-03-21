<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function review($id){
        $rating = Review::where('product_id',$id)
                ->select(
                    DB::raw("COUNT(CASE WHEN rating = 1 THEN 1 END) as 1"),
                    DB::raw("COUNT(CASE WHEN rating = 2 THEN 1 END) as 2"),
                    DB::raw("COUNT(CASE WHEN rating = 3 THEN 1 END) as 3"),
                    DB::raw("COUNT(CASE WHEN rating = 4 THEN 1 END) as 4"),
                    DB::raw("COUNT(CASE WHEN rating = 5 THEN 1 END) as 5"),
                )               
                ->first();

                $review = Review::where('product_id',$id)
                ->rightJoin('users','users.id','reviews.id')
                ->select(
                    'reviews.id as id',
                    'users.name as reviewed_by',
                    'reviews.rating as rating',
                    'reviews.message as review_message',
                    'reviews.updated_at as reviewed_date'
                    )
               
                ->paginate(10);

        return response()->json([
            'review' => $review,
            'rating' =>$rating,
        ]);
    }
}
