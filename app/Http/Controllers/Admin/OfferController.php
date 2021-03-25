<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\JobCategory;
use App\Models\Offer;
use App\Models\OfferSalon;
use Illuminate\Http\Request;
use File;
use Intervention\Image\Facades\Image;

class OfferController extends Controller
{
    public function offerList()
    {
        $offers = Offer::orderByDesc('id')->get();
        return view('admin.offers.list',compact('offers'));
    }

    public function addOfferForm()
    {
        $category = JobCategory::where('status','1')->get();
        return view('admin.offers.add_offer',compact('category'));
    }

    public function insertOffer(Request $request)
    {
        $this->Validate($request, [
            'name' => 'required|string',
            'category' => 'required|numeric',
            'sub_category' => 'nullable|numeric',
            'third_category' => 'nullable|numeric',
            'range_type' => 'required|in:1,2',
            'from_date' => 'required_if:range_type,2',
            'to_date' => 'required_if:range_type,2',
            'images' => 'nullable|image|mimes:jpeg,jpg,png,jpg,gif,svg|max:2048',
            'total_user' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $offer = new Offer();
        if ($offer) {
            $offer->name = $request->input('name');
            $offer->category_id = $request->input('category');
            $offer->sub_category_id = $request->input('sub_category');
            $offer->third_category_id = $request->input('third_category');
            $offer->range_type = $request->input('range_type');
            $offer->from_date = $request->input('from_date');
            $offer->to_date = $request->input('to_date');
            $offer->total_user = $request->input('total_user');
            $offer->price = $request->input('price');
            $offer->description = $request->input('description');  
            
            if ($request->hasFile('image')) {
                $image_name = $this->offerImageUpload( $request->file('image'));
                $offer->image = $image_name;
            }
            $offer->save();
            return back()->with('message','Offer updated successfully');
        }else{
            return back()->with('error','Something went wrong please try again');
        }
    }

    public function editOfferForm($offer_id)
    {
        $category = JobCategory::where('status','1')->get();
        $offer = Offer::findOrFail($offer_id);
        return view('admin.offers.edit_offer',compact('category','offer'));
    }

    public function updateOffer(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'category' => 'required|numeric',
            'sub_category' => 'nullable|numeric',
            'third_category' => 'nullable|numeric',
            'range_type' => 'required|in:1,2',
            'from_date' => 'required_if:range_type,2',
            'to_date' => 'required_if:range_type,2',
            'images' => 'nullable|image|mimes:jpeg,jpg,png,jpg,gif,svg|max:2048',
            'total_user' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $offer = Offer::findOrFail($request->input('offer_id'));
        if ($offer) {
            $offer->name = $request->input('name');
            $offer->category_id = $request->input('category');
            $offer->sub_category_id = $request->input('sub_category');
            $offer->third_category_id = $request->input('third_category');
            $offer->range_type = $request->input('range_type');
            $offer->from_date = $request->input('from_date');
            $offer->to_date = $request->input('to_date');
            $offer->total_user = $request->input('total_user');
            $offer->description = $request->input('description');  
            $offer->price = $request->input('price');         

            
            if ($request->hasFile('image')) {
                $image_name = $this->offerImageUpload( $request->file('image'));
                $offer->image = $image_name;
            }
            $offer->save();
            return back()->with('message','Offer updated successfully');
        }else{
            return back()->with('error','Something went wrong please try again');
        }
    }

    private function offerImageUpload($image){
        $path = public_path().'/images/offer/';
        File::exists($path) or File::makeDirectory($path, 0777, true, true);
        $path_thumb = public_path().'/images/offer/thumb/';
        File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);

        $image_name = time().date('Y-M-d').uniqid().'.'.$image->getClientOriginalExtension();

        $destinationPath =public_path().'/images/offer';
        $img = Image::make($image->getRealPath());
        $img->save($destinationPath.'/'.$image_name);
        //Product Thumbnail
        $destination = public_path().'/images/offer/thumb';
        $img = Image::make($image->getRealPath());
        $img->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destination.'/'.$image_name);
        return $image_name;
    }

    public function editSalon($offer_id)
    {
        $offer_salon = OfferSalon::where('offer_id',$offer_id)->get();
        return view('admin.offers.salon_list',compact('offer_id','offer_salon'));
    }

    public function addSalon($offer_id)
    {
        return view('admin.offers.offer_salon_add',compact('offer_id'));
    }

    public function salonDataFetch($mobile)
    {
        return Client::select('name','mobile')->where('mobile',$mobile)->where('clientType',2)->first();
    }

    public function insertOfferSalon(Request $request)
    {
        $this->Validate($request,[
            'salon_mobile' => 'required|numeric|digits:10',
            'offer_id' => 'required',
        ]);
        $salon = Client::select('id')->where('mobile',$request->input('salon_mobile',))->where('clientType',2)->first();
        if ($salon) {
            $check_salon = OfferSalon::where('client_id',$salon->id)->where('offer_id',$request->input('offer_id'))->count();
            if ($check_salon == 0) {
                $offer_salon = new OfferSalon();
                $offer_salon->client_id = $salon->id;
                $offer_salon->offer_id = $request->input('offer_id');
                $offer_salon->save();
            }
            return back()->with('message','Salon Added Successfully in this offer');
        }else{
            return back()->with('error','Sorry Salon Not Found');
        }        
    }

    public function removeOfferSalon($offer_salon_id)
    {
        $offer_salon = OfferSalon::where('id',$offer_salon_id)->delete();
        return back()->with('message','Salon Removed Successfully from this offer');
    }

}
