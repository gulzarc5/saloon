<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\State;
use App\Models\City;
use DataTables;

class ConfigurationController extends Controller
{
    public function state(){
        return view('admin.configuration.state');
    }

    public function stateListAjax(Request $request)
    {
        return datatables()->of(State::orderBy('id','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.edit_state',['id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit</a>';
                if ($row->status == '1') {
                    $btn .='<a href="'.route('admin.update_status_state',['id'=>$row->id,2]).'" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="'.route('admin.update_status_state',['id'=>$row->id,1]).'" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addState(Request $request)
    {
        return view('admin.configuration.add_new_state');
    }

    public function insertState(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        State::create(['name'=>$request->input('name')]);
        return redirect()->back()->with('message','State Added successfully');
    }

    public function editState($id)
    {
        $state = State::find($id);
        return view('admin.configuration.add_new_state',compact('state'));
    }

    public function updateState(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $state = State::find($id);
        $state->name = $request->input('name');
        $state->save();
        return redirect()->back()->with('message','State Updated successfully');
    }

    public function updateStatusState($id,$status)
    {
        $state = State::find($id);
        $state->status = $status;
        $state->save();
        return redirect()->back();
    }

    public function city(){
        return view('admin.configuration.city');
    }

    public function cityListAjax(Request $request)
    {
        return datatables()->of(City::orderBy('id','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.edit_city',['id'=>$row->id]).'" class="btn btn-warning btn-sm" target="_blank">Edit</a>';
                if ($row->status == '1') {
                    $btn .='<a href="'.route('admin.update_status_city',['id'=>$row->id,2]).'" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="'.route('admin.update_status_city',['id'=>$row->id,1]).'" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })->addColumn('state_name', function($row){
                return $row->state->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addCity()
    {
        $state = State::where('status',1)->get();
        return view('admin.configuration.add_new_city',compact('state'));
    }

    public function insertCity(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'state_id' => 'required',
        ]);
        City::create(['name'=>$request->input('name'),'state_id'=>$request->input('state_id')]);
        return redirect()->back()->with('message','City Added successfully');
    }

    public function editCity($id)
    {
        $city = City::find($id);
        $state = State::where('status',1)->get();
        return view('admin.configuration.add_new_city',compact('city','state'));
    }

    public function updateCity(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required',
            'state_id' => 'required',
        ]);
        $state = City::find($id);
        $state->name = $request->input('name');
        $state->state_id = $request->input('state_id');
        $state->save();
        return redirect()->back()->with('message','City Updated successfully');
    }

    public function updateStatusCity($id,$status)
    {
        $state = City::find($id);
        $state->status = $status;
        $state->save();
        return redirect()->back();
    }

    public function cityListByState ($state_id)
    {
        $city = City::where('state_id',$state_id)->where('status',1)->get();
        return $city;
    }
}
