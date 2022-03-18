<?php

namespace App\Http\Controllers\Admin\Address;
use App\Models\LocalArea;
use App\Helper\Datatable;
use App\Http\Resources\Admin\Address\LocalAreaResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocalAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $local_area = LocalArea::join('cities','cities.id','local_areas.city_id')
                             ->select(
                                 'cities.name as city_name',
                                 'local_areas.city_id',
                                 'local_areas.id',
                                 'local_areas.name',
                                 'local_areas.price',
                                 'local_areas.created_at'
                             );
        $local_area = Datatable::filter($local_area,['local_areas.name','local_areas.district_id','cities.name']);
        return  LocalAreaResource::collection($local_area)->response()
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
            'city_id' => 'required',
            'name' => 'required | max:100 | unique:local_areas,name,'.$id,
            'devivery_charge' => 'gt:-1'
        ]);
        $delivery_charge =0;

        $delivery_cost = $request->get('delivery_charge');
        if(isset($delivery_cost)){
            $delivery_charge = $delivery_cost;
        }

        if(isset($id)){
            $local_area = LocalArea::findOrFail($id);
            $local_area->city_id = $request->city_id;
            $local_area->name = $request->name;
            $local_area->price = $delivery_charge;
            $local_area->update();
            $message = 'Local Area updated successfully.';
        }else{
            $LocalArea = new LocalArea();
            $LocalArea->city_id = $request->city_id;
            $LocalArea->name = $request->name;
            $LocalArea->price = $delivery_charge;
            $LocalArea->save();
            $message = 'Local Area added successfully.';
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
        $local_area = LocalArea::findOrFail($id);
        $local_area->delete();
        return response()->json([
            'message'=> 'Local Area deleted successfully',
        ],200);
    }
}
