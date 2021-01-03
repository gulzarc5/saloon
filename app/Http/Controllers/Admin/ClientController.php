<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Client;
use App\Models\ClientImage;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\ServiceCity;
use File;
use Image;

class ClientController extends Controller
{
    public function freelancerList(){
        return view('admin.client.freelancer');
    }
    public function freelancerListAjax(Request $request){
        return datatables()->of(Client::where('clientType',1)->orderBy('id','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.client_details',['client_id'=>$row->id]).'" class="btn btn-info btn-sm" target="_blank">View</a>';
                if ($row->status == '1') {
                    $btn .='<a href="'.route('admin.customer_status_update',['id'=>$row->id,2]).'" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="'.route('admin.customer_status_update',['id'=>$row->id,1]).'" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })
            ->addColumn('service_city_name', function($row){
                if (!empty($row->service_city_id)) {
                    return $row->serviceCity->name;
                }else{
                    return "";
                }
            })
            ->rawColumns(['action','service_city_name'])
            ->make(true);
    }

    public function clientDetails($client_id)
    {
        $client = Client::findOrFail($client_id);
        return view('admin.client.client_details',compact('client'));
    }

    public function clientEdit($client_id)
    {
        $client = Client::findOrFail($client_id);
        $service_city = ServiceCity::where('status',1)->get();
        return view('admin.client.client_edit',compact('client','service_city'));
    }

    public function clientUpdate(Request $request,$client_id)
    {
        $this->validate($request, [
            'name'             => ['required', 'string', 'max:255'],
            'mobile'           => 'required|digits:10|numeric|unique:clients,id,'.$client_id,
            'service_city_id' => 'required',
        ]);

        $client = Client::find($client_id);
        $client->name = $request->input('name');
        $client->mobile = $request->input('mobile');
        $client->email = $request->input('email');
        $client->work_experience = $request->input('work_experience');
        $client->state = $request->input('state');
        $client->city = $request->input('city');
        $client->service_city_id = $request->input('service_city_id');
        $client->address = $request->input('address');
        $client->gst = $request->input('gst');
        $client->opening_time = $request->input('opening_time');
        $client->closing_time = $request->input('closing_time');
        $client->profile_status = 2;
        $client->save();
        // return redirect()->back()->with('message','Client Data Updated Successfully');
        return redirect()->route('admin.client_details',['client_id'=>$client_id]);
    }

    public function clientImages($client_id)
    {
        $client = Client::findOrFail($client_id);
        $client_images = ClientImage::where('client_id',$client_id)->get();
        return view('admin.client.client_images',compact('client','client_images'));
    }

    public function clientImagesCover($client_id,$image_id)
    {
        $image = ClientImage::findOrFail($image_id);
        $client = Client::findOrFail($client_id);
        $client->image = $image->image;
        $client->save();
        return redirect()->back();
    }

    public function clientImagesDelete($image)
    {
        $image_data = ClientImage::findOrFail($image);
        $path = public_path().'/images/client/'.$image_data->image;
        $thumb_path = public_path().'/images/client/thumb/'.$image_data->image;
        if (File::exists($path)) {
            File::delete($path);
        }
        if (File::exists($thumb_path)) {
            File::delete($thumb_path);
        }
        $image_data->delete();
        return redirect()->back();
    }

    public function clientServicesEdit($client_id)
    {
        $client_services = Job::where('user_id',$client_id)->get();
        $service_category = JobCategory::where('status',1)->get();
        return view('admin.client.client_services',compact('client_services','service_category','client_id'));
    }

    public function shop(){
        return view('admin.client.shop');
    }

    public function shopListAjax(Request $request){
        return datatables()->of(Client::where('clientType',2)->orderBy('id','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.client_details',['client_id'=>$row->id]).'" class="btn btn-info btn-sm" target="_blank">View</a>';
                if ($row->status == '1') {
                    $btn .='<a href="'.route('admin.client_status_update',['id'=>$row->id,2]).'" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="'.route('admin.client_status_update',['id'=>$row->id,1]).'" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })
            ->addColumn('service_city_name', function($row){
                if (!empty($row->service_city_id)) {
                    return $row->serviceCity->name;
                }else{
                    return "";
                }
            })
            ->rawColumns(['action','service_city_name'])
            ->make(true);
    }

    public function updateClientStatus($id,$status)
    {
        $client = Client::findOrFail($id);
        $client->status = $status;
        $client->save();
        return redirect()->back();
    }

    public function updateClientVerifyStatus($id,$status)
    {
        $client = Client::findOrFail($id);
        $client->verify_status = $status;
        $client->save();
        return redirect()->back();
    }

}
