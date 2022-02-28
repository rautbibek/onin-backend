<?php

namespace App\Http\Controllers\Admin;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helper\Datatable;
use App\Http\Helper\MediaHelper;
use App\Http\Resources\Admin\BannerResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banner = Banner::latest();
        $banner = Datatable::filter($banner,['title','subtitle','type']);
        return  BannerResource::collection($banner)->response()
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
        // return response()->json($request->all(),500);
        $this->validate($request,[
            'type' =>'required',
            'title'=>'max:220',
            'subtitle'=>'max:220',
            'link'=>'sometimes|url',
        ]);
        $id = $request->get('id');
        if($id){
            $this->validate($request,[
                'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            ]);
        }else{
            $this->validate($request,[
                'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            ]);
        }
        $message = "";

        try{
            DB::beginTransaction();
            
            if(isset($id)){
                $banner = Banner::findOrFail($id);
                if(isset($request->image)){
                    Storage::delete('/banner/'.$banner->image);
                    Storage::delete('/thumb/'.$banner->image);
                    $file = MediaHelper::saveProductImage($request->image,'banner', 'banner');
                }
                
                $banner->update([
                    'type'=> $request->get('type'),
                    'title' => $request->get('title'),
                    'subtitle' => $request->get('subtitle'),
                    'link' => $request->get('link'),
                    'image' => $file

                ]);
                $message = 'Banner updated succefully';
            }else{
                $banner = new Banner();
                $file = MediaHelper::saveProductImage($request->image,'banner', 'banner');
                $banner->create([
                    'type'=> $request->get('type'),
                    'title' => $request->get('title'),
                    'subtitle' => $request->get('subtitle'),
                    'link' => $request->get('link'),
                    'image' => $file

                ]);
                $message = 'Banner created succefully';
                
            }
            DB::commit();
            return response()->json([
                'message'=>$message
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
        $banner = Banner::findOrFail($id);
        Storage::delete('/banner/'.$banner->image);
        Storage::delete('/thumb/'.$banner->image);
        $banner->delete();
        return response()->json([
            'message'=>'Banner deleted succefullty',
        ]);
    }
}
