<?php

namespace App\Http\Controllers\Admin\Address;
use App\Models\City;
use App\Helper\Datatable;
use App\Http\Resources\Admin\Address\CityResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cities = City::join('districts','districts.id','cities.district_id')
                             ->select(
                                 'districts.name as district_name',
                                 'cities.district_id',
                                 'cities.id',
                                 'cities.name',
                                 'cities.price',
                                 'cities.created_at'
                             );
        $cities = Datatable::filter($cities,['cities.name','cities.district_id','districts.name']);
        return  CityResource::collection($cities)->response()
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
            'district_id' => 'required|unique:cities,name,'.$id,
            'name' => 'required | max:100',
            'devivery_charge' => 'gt:-1'
        ]);
        $delivery_charge =0;

        $delivery_cost = $request->get('delivery_charge');
        if(isset($delivery_cost)){
            $delivery_charge = $delivery_cost;
        }

        if(isset($id)){
            $city = City::findOrFail($id);
            $city->district_id = $request->district_id;
            $city->name = $request->name;
            $city->price = $delivery_charge;
            $city->update();
            $message = 'City updated successfully.';
        }else{
            $city = new City();
            $city->district_id = $request->district_id;
            $city->name = $request->name;
            $city->price = $delivery_charge;
            $city->save();
            $message = 'City added successfully.';
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
        $city= City::findOrFail($id);
        if($city->singleLocalArea){
            return response()->json([
                'message'=> 'Warning, Please delete the local area belongs to this city before deleting the district',
            ],422);
        }else{
            $city->delete();
            return response()->json([
                'message'=> 'City deleted successfully.'
            ],200);
        }
        
    }
}
