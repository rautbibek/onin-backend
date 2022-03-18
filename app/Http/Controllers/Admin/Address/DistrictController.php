<?php

namespace App\Http\Controllers\Admin\Address;
use App\Models\District;
use App\Helper\Datatable;
use App\Http\Resources\Admin\Address\DistrictResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
       
        $district = District::join('states','states.id','districts.state_id')
                             ->select(
                                 'states.name as state_name',
                                 'districts.state_id',
                                 'districts.id','districts.name',
                                 'districts.created_at'
                             );
        $district = Datatable::filter($district,['districts.name','districts.state_id','states.name']);
        return  DistrictResource::collection($district)->response()
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
        $id = $request->get('id');
        $this->validate($request,[
            'state_id' =>'required',
            'name' => 'required| max:100 | unique:districts,name,'.$id,
        ]);
        
        if(isset($id)){
            $district = District::findOrFail($id);
            $district->state_id = $request->state_id;
            $district->name = $request->name;
            $district->update();
            $message = "District updated successfully.";
        }else{
            $district = new District;
            $district->state_id = $request->state_id;
            $district->name = $request->name;
            $district->save();
            $message = "New district added successfully.";
        }
        return response()->json([
            'message' => $message,
        ],200);
        
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
        $district = District::findOrFail($id);
        $district->delete();
        return response()->json([
            'message' => 'District deleted successfully',
        ]);
    }
}
