<?php

namespace App\Http\Controllers\Api\V1\Customer;
use App\Models\Address;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $address = Address::where('user_id',auth()->id())->get();
        return response()->json($address);
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
        $message = "";
        $this->validate($request,[
            'state_id'=> 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'local_area_id' => 'required',
            'landmark' => 'required| max:255',
            'address_type' => 'required|in:Home,Office,Other',
        ]);
        try{
            
            $localarea = DB::table('local_areas')
                            ->leftJoin('cities','cities.id','=','local_areas.city_id')
                            ->leftJoin('districts','districts.id','=','cities.district_id')
                            ->leftJoin('states','states.id','=','districts.state_id')
                            ->select(
                                'local_areas.id as local_area_id',
                                'cities.id as city_id',
                                'districts.id as district_id',
                                'states.id as state_id',
                                'local_areas.name as local_area_name',
                                'cities.name as city_name',
                                'districts.name as district_name',
                                'states.name as state_name'
                            )
                            ->where('local_areas.id',$request->local_area_id)
                            ->first();
            if(!$localarea){
                return response()->json([
                    'message' => 'Local area not found'
                ],422);
            }
            $full_address = $request->get('landmark').','.$localarea->local_area_name.','.$localarea->city_name.','.$localarea->district_name.','.$localarea->state_name;
            
            DB::beginTransaction();

            if(isset($id)){
                
                $address = Address::findOrFail($id);
                
                if($address->address_type == $request->address_type){

                }else{
                    $add = Address::where('user_id',auth()->id())->where('address_type',$request->get('address_type'))->first();
                    if($add){
                        return response()->json([
                            'message'=>$request->address_type.' type address is already exista',
                        ],422);
                    }
                }
                $address->update([
                    
                    'state_id'=> $localarea->state_id,
                    'district_id' => $localarea->district_id,
                    'city_id' => $localarea->city_id,
                    'local_area_id' =>  $localarea->local_area_id,
                    'full_address' => $full_address,
                    'landmark' => $request->landmark,
                    'address_type' => $request->address_type,
                ]);
                $message = 'Address updated succefully.';
            }else{
                $add = Address::where('user_id',auth()->id())->where('address_type',$request->get('address_type'))->first();
                if($add){
                    return response()->json([
                        'message'=>$request->address_type.' type address is already exista',
                    ],422);
                }
                Address::create([
                    'user_id' => auth()->id(),
                    'state_id'=> $localarea->state_id,
                    'district_id' => $localarea->district_id,
                    'city_id' => $localarea->city_id,
                    'local_area_id' =>  $localarea->local_area_id,
                    'full_address' => $full_address,
                    'landmark' => $request->landmark,
                    'address_type' => $request->address_type,
                ]);
                $message = 'New address added succefully.';

            }            
            DB::commit();
            return response()->json([
                'message'=> $message
            ],200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'message' => 'something went wrong.',
                'error' => $e->getMessage(),
            ],500);
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
        $address = Address::findOrFail($id);
        if(!(auth()->id() == $address->user_id)){
            return response()->json([
                'message'=> 'Forbidden request',
            ],403);
        }
        $address->delete();
        return response()->json([
            'message'=> 'Address deleted succefully',
        ],200);
    }
}
