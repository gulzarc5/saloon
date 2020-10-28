<?php

namespace App\Http\Controllers\Admin\ServiceCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Models\JobCategory;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\File;

class ServiceCategoryController extends Controller
{
    public function index(){
        return view('admin.service_category.index');
    }

    public function serviceCategory(){
        $query = JobCategory::latest();
        return datatables()->of($query->get())
        ->addIndexColumn()
        ->addColumn('category', function($row){
            $category = '';
            if($row->man == 1){
                $category.='<label class="label label-success">MAN</label>';
            }
            if($row->woman == 1){
                $category .=', <label class="label label-success">WOMAN</label>';
            }
            if($row->kids == 1){
                $category .=', <label class="label label-success">KIDS</label>';
            }
            return $category;
        })
        ->addColumn('photo', function($row){
            if($row->image){
                $image = '<img src="'.asset("admin/service_category/thumb/".$row->image).'" width="50"/>';
            }
            return $image;
        })
        ->addColumn('action', function($row){
            if($row->status == '1'){
                $action = '<a href="'.route('admin.service_category.status', ['id' => encrypt($row->id), 'status'=> 2]).'" class="btn btn-danger">Disable</a>';
            }else{
                $action = '<a href="'.route('admin.service_category.status', ['id' => encrypt($row->id), 'status'=> 1]).'" class="btn btn-primary">Enable</a>';
            }
                $action .= '<a  href="'.route('admin.service_category.edit', ['id' => encrypt($row->id)]).'" class="btn btn-info">Edit</a>';
            return $action;
        })
        ->rawColumns(['action', 'category', 'photo'])
        ->make(true);
    }

    public function edit($id){
        try{
            $id = decrypt($id);
        }catch(DecryptException $e) {
            abort(404);
        }
        $job_category = JobCategory::find($id);
        return view('admin.service_category.edit', compact('job_category'));
    }

    public function status($id, $status){
        try{
            $id = decrypt($id);
        }catch(DecryptException $e) {
            abort(404);
        }
        $job_category = JobCategory::find($id);
        $job_category->status = $status;
        if($job_category->save()){
            return redirect()->back()->with('message', 'Status updated Successfully!');
        }else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function updateServiceCategory(Request $request){
        $this->validate($request, [
            'name' => 'required',
        ]);
        $id     = $request->input('id');
        $name   = $request->input('name');
        $man    = $request->input('man');
        $woman  = $request->input('woman');
        $kids   = $request->input('kids');

        if($request->hasfile('image'))
        {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $destination = base_path().'/public/admin/service_category/';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now').time()).".".$image_extension;
            $original_path = $destination.$image_name;
            Image::make($image)->save($original_path);
            $thumb_path = base_path().'/public/admin/service_category/thumb/'.$image_name;
            Image::make($image)
            ->resize(346, 252)
            ->save($thumb_path);

            // Check wheather image is in DB
            $service_category = JobCategory::find($id);
            if($service_category->image){
                //Delete
                $image_path = "admin/service_category/thumb/".$service_category->image;  
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
                //Update
                $service_category->image = $image_name;
                $service_category->updated_at = Carbon::now();
                if($service_category->save()){
                    return redirect()->back()->with('message','Service Category Updated Successfully!');
                }else{
                    return redirect()->back()->with('error','Something Went Wrong Please Try Again');
                } 
            }else{
                //Update
                $service_category->image = $image_name;
                $service_category->updated_at = Carbon::now();
                if($service_category->save()){
                    return redirect()->back()->with('message','Service Category Updated Successfully!');
                }else{
                    return redirect()->back()->with('error','Something Went Wrong Please Try Again');
                } 
            }
        }
        $service_category = JobCategory::find($id);
        $service_category->name = $name;

        $man == null  ? $service_category->man = '2' : '1';
        $woman == null  ? $service_category->woman = '2' : '1';
        $kids == null  ? $service_category->kids = '2' : '1';

        $man == 2  ? $service_category->man = '1' : '2';
        $woman == 2  ? $service_category->woman = '1' : '2';
        $kids == 2  ? $service_category->kids = '1' : '2';

        if($service_category->save()){
            return redirect()->back()->with('message', 'Service Category Successfully Added');
        }else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function addServiceCategory(){
        return view('admin.service_category.service_category');
    }

    public function storeServiceCategory(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $name   = $request->input('name');
        $man    = $request->input('man');
        $woman  = $request->input('woman');
        $kids   = $request->input('kids');
        if($request->hasfile('image')){
            $image = $request->file('image');
            $destination = base_path().'/public/admin/service_category/';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now').time()).".".$image_extension;
            $original_path = $destination.$image_name;
            Image::make($image)->save($original_path);
            $thumb_path = base_path().'/public/admin/service_category/thumb/'.$image_name;
            Image::make($image)
            ->resize(346, 252)
            ->save($thumb_path);
        }
        $service_category = new JobCategory;
        $service_category->name = $name;
        $service_category->image = $image_name;
        
        $man == null ? $service_category->man = '2' : '1';
        $woman == null ? $service_category->woman = '2' : '1';
        $kids == null ? $service_category->kids = '2' : '1';

        $man == 2  ? $service_category->man = '1' : '2';
        $woman == 2  ? $service_category->woman = '1' : '2';
        $kids == 2  ? $service_category->kids = '1' : '2';
        if($service_category->save()){
            return redirect()->back()->with('message', 'Service Category Successfully Added');
        }else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
}
