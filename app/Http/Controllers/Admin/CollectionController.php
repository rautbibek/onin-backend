<?php

namespace App\Http\Controllers\Admin;
use App\Models\Collection;
use App\Helper\Datatable;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CollectionResource;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collection = new Collection();
        $collection = Datatable::filter($collection,['name']);
        return  CollectionResource::collection($collection)->response()
        ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request,[
            'name'=> 'required',
        ]);
        $id = $request->get('id');
        if(isset($id)){
            $collection = Collection::findOrFail($id);
            $collection->update([
                'name' => $request->get('name'),
                // 'discount_type' => $request->get('discount_type'),
                // 'discount' => $request->get('discount'),
                'expire_at' => $request->expire_at,
            ]);
            $message = 'Collection Updated Successfully';
        }else{
            $collection = new Collection();
            $collection->create([
                'name' => $request->get('name'),
                // 'discount_type' => $request->get('discount_type'),
                'discount' => $request->get('discount')
            ]);
            $message = 'New Collection Successfully';
        }
        return response()->json([
            'message'=>$message
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
        $collection = Collection::select('id','name')->where('id',$id)->with('product:id,title,cover,status')->first();
        return response()->json($collection);
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
        $collection = Collection::findOrFail($id);
        $status = !$collection->status;
        
        $collection->update([
                'status' => $status
        ]);

        return response()->json([
            'message' => "Status Updated succefully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);
        $collection->delete();
        return response()->json([
            'brand'=>$collection,
            'message'=>'Collection Deleted successfully'
        ]);
    }
    public function removeProductFromCollection($id){
        return $id;
        $cllection = Collection::findOrFail($id);
        $cllection->product()->detach();
    }

}
