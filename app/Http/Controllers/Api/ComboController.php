<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\Combo\ComboAddRequest;
use App\Http\Resources\Combo\ComboListResource;
use App\Models\ComboService;
use App\Models\Job;
use Illuminate\Http\Request;
use File;
use Image;

class ComboController extends Controller
{
    public function add(ComboAddRequest $request)
    {
        if ($request->input('combo_id')) {
            $job = Job::find($request->input('combo_id'));
        } else {
            $job = new Job();
        }
        
        $this->serviceSave($job,$request);
        
        if ($request->user()->job_status !=2) {
            $client = Client::find($request->user()->id);
            $client->job_status = 2;
            $client->save();
        }

        $response = [
            'status' => true,
            'message' => 'Combo Added Successfully',
            'error_code' => false,
            'error_message' => null,

        ];
        return response()->json($response, 200);
    }


    private function serviceSave(Job $job,$request){
        $job->user_id = $request->user()->id;
        $job->description = $request->input('combo_name');
        $job->job_category = $request->input('main_category');
        $job->product_type = 2;
  
        if($job->save()){
            $service_name = $request->input('service_name');
            $price = $request->input('price');
            $mrp = $request->input('mrp');
            $service_id = $request->input('service_id');

            if ($request->hasfile('image')) { 
                $image = $request->file('image');
                $this->comboImageAdd($job,$image);
            }

            $total_mrp = 0;
            $total_price = 0;
            for ($i = 0; $i < count($service_name); $i++){
                if (isset($service_id[$i] ) && !empty( $service_id[$i])) {
                    $comboService = ComboService::find($service_id[$i]);
                }else{
                    $comboService = new ComboService();
                }
                if ($comboService && isset($service_name[$i]) && isset($price[$i]) && isset($mrp[$i])) {
                    $this->comboServiceAdd($comboService,$job->id,$service_name[$i],$price[$i],$mrp[$i]);
                    $total_mrp += $mrp[$i];
                    $total_price += $price[$i];
                }
            }

            $job->mrp = $total_mrp ;
            $job->price = $total_price;
            $job->save();
        }
        return true;
    }

    private function comboServiceAdd(ComboService $comboService,$job_id,$service_name,$price,$mrp)
    {
        $comboService->job_id = $job_id;
        $comboService->name = $service_name;
        $comboService->price = $price;
        $comboService->mrp = $mrp;
        $comboService->save();
        return true;
    }

    private function comboImageAdd(Job $job,$image)
    {
        $path = public_path() . '/images/client/thumb/';
        File::exists($path) or File::makeDirectory($path, 0777, true, true);
        $path_thumb = public_path() . '/images/client/';
        File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);

        // $image = $request->file('images');
        $image_name = time() .uniqid(). date('Y-M-d') . '.' . $image->getClientOriginalExtension();

        if (!empty($job->main_image)) {
            $this->comboImageDelete($job->main_image);
        }
        //Product Original Image
        $destination = public_path() . '/images/client/';
        $img = Image::make($image->getRealPath());
        $img->save($destination . '/' . $image_name);

        //Product Thumbnail
        $destination = public_path() . '/images/client/thumb';
        $img = Image::make($image->getRealPath());
        $img->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destination . '/' . $image_name);

        $job->main_image = $image_name;
        $job->save();            
       
        return true;
    }


    private function comboImageDelete($image_name)
    {
        $path = public_path() . '/images/client/' . $image_name;
        $thumb_path = public_path() . '/images/client/thumb/' . $image_name;

        if (File::exists($path)) {
            File::delete($path);
        }

        if (File::exists($thumb_path)) {
            File::delete($thumb_path);
        }
        return true;
    }

    public function list(Request $request)
    {
        $job = Job::where('user_id', $request->user()->id)->where('product_type',2)->get();
        $response = [
            'status' => true,
            'message' => "combo Product List",
            'data' => ComboListResource::collection($job),

        ];
        return response()->json($response, 200);
    }
}
