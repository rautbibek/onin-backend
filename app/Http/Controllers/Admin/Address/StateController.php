<?php

namespace App\Http\Controllers\Admin\Address;
use App\Models\State;
use App\Helper\Datatable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $states = State::latest();
        $states = Datatable::filter($states,['name']);
        return response()->json($states);
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
        if(isset($id)){
            $this->validate($request,[
                'name' => 'required|max:100|unique:states,name,'.$id,
            ]);
        }else{
            $this->validate($request,[
                'name' => 'required|max:100|unique:states,name',
            ]);
        }
        
        
        if(isset($id)){

            $state = State::findOrFail($id);
            $state->name = $request->name;
            $state->update();
            $message = "State updated successfully";

            
        }else{
            $state = new State();
            $state->name = $request->name;
            $state->save();
            $message = "State saved successfully";
        }
        return response()->json([
            'message'=> $message,
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
        $state = State::findOrFail($id);
        //return $state;
        if($state->singleDistrict){
            return response()->json([
                'message'=> 'Warning, Please delete the district belongs to this state before deleting the state',
            ],422);
        }else{
            $state->delete();
            return response()->json([
                'message'=>'State deleted successfully.'
            ],200);
        }
        
    }
}
