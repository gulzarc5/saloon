<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\JobCategory;
use App\Models\Offer;
use App\Models\OfferSalon;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function offerList()
    {
        $offers = Offer::orderByDesc('id')->get();
        return view('admin.offers.list',compact('offers'));
    }

    public function editForm($offer_id)
    {
        $category = JobCategory::where('status','1')->get();
        return view('admin.offers.edit_offer',compact('category'));
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
            $offer_salon = new OfferSalon();
            $offer_salon->client_id = $salon->id;
            $offer_salon->offer_id = $request->input('offer_id');
            $offer_salon->save();
            return back()->with('message','Salon Added Successfully in this offer');
        }else{
            return back()->with('error','Sorry Salon Not Found');
        }        
    }
}
