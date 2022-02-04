<?php

namespace App\Http\Controllers\Admin;
use App\Models\ColorFamily;
use App\Helper\Datatable;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ColorFamilyResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColorFamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = new ColorFamily();
        $colors = Datatable::filter($colors,['name']);
        return  ColorFamilyResource::collection($colors)->response()
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
        $this->validate($request,[
            'name' => 'required| max:100',
            'code' => 'required| max:100',
        ]);
        $id = $request->id;
        $message = "";
        DB::beginTransaction();
        try{
            if(isset($id)){
                $color = ColorFamily::findOrFail($id);
                $color->name = $request->name;
                $color->code = $request->code;
                $color->update();
                $message = "Color Family updated successfullty";
            }else{
                $color = new ColorFamily();
                $color->name = $request->name;
                $color->code = $request->code;
                $color->save();
                $message = "New color added successfully";
            }
        DB::commit();
        return response()->json([
            'message' => $message,

        ],200);
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
