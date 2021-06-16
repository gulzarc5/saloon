<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\AppDescription;
use Illuminate\Http\Request;
use Validator;
use App\Models\State;
use App\Models\City;
use App\Models\ServiceCity;
use DataTables;
use App\Models\InvoiceSetting;
use File;
use Intervention\Image\Facades\Image;

class ConfigurationController extends Controller
{
    public function state()
    {
        return view('admin.configuration.state');
    }

    public function stateListAjax(Request $request)
    {
        return datatables()->of(State::orderBy('id', 'desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('admin.edit_state', ['id' => $row->id]) . '" class="btn btn-warning btn-sm" target="_blank">Edit</a>';
                if ($row->status == '1') {
                    $btn .= '<a href="' . route('admin.update_status_state', ['id' => $row->id, 2]) . '" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .= '<a href="' . route('admin.update_status_state', ['id' => $row->id, 1]) . '" class="btn btn-primary btn-sm" >Enable</a>';
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
        State::create(['name' => $request->input('name')]);
        return redirect()->back()->with('message', 'State Added successfully');
    }

    public function editState($id)
    {
        $state = State::find($id);
        return view('admin.configuration.add_new_state', compact('state'));
    }

    public function updateState(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $state = State::find($id);
        $state->name = $request->input('name');
        $state->save();
        return redirect()->back()->with('message', 'State Updated successfully');
    }

    public function updateStatusState($id, $status)
    {
        $state = State::find($id);
        $state->status = $status;
        $state->save();
        return redirect()->back();
    }

    public function city()
    {
        return view('admin.configuration.city');
    }

    public function cityListAjax(Request $request)
    {
        return datatables()->of(City::orderBy('id', 'desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('admin.edit_city', ['id' => $row->id]) . '" class="btn btn-warning btn-sm" target="_blank">Edit</a>';
                if ($row->status == '1') {
                    $btn .= '<a href="' . route('admin.update_status_city', ['id' => $row->id, 2]) . '" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .= '<a href="' . route('admin.update_status_city', ['id' => $row->id, 1]) . '" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })->addColumn('state_name', function ($row) {
                return $row->state->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function addCity()
    {
        $state = State::where('status', 1)->get();
        return view('admin.configuration.add_new_city', compact('state'));
    }

    public function insertCity(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'state_id' => 'required',
        ]);
        City::create(['name' => $request->input('name'), 'state_id' => $request->input('state_id')]);
        return redirect()->back()->with('message', 'City Added successfully');
    }

    public function editCity($id)
    {
        $city = City::find($id);
        $state = State::where('status', 1)->get();
        return view('admin.configuration.add_new_city', compact('city', 'state'));
    }

    public function updateCity(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'state_id' => 'required',
        ]);
        $state = City::find($id);
        $state->name = $request->input('name');
        $state->state_id = $request->input('state_id');
        $state->save();
        return redirect()->back()->with('message', 'City Updated successfully');
    }

    public function updateStatusCity($id, $status)
    {
        $state = City::find($id);
        $state->status = $status;
        $state->save();
        return redirect()->back();
    }

    public function cityListByState($state_id)
    {
        $city = City::where('state_id', $state_id)->where('status', 1)->get();
        return $city;
    }

    public function serviceCity()
    {
        $serviceCity = ServiceCity::orderBy('id', 'desc')->get();
        return view('admin.configuration.serviceCity.service_city', compact('serviceCity'));
    }

    public function addServiceCity()
    {
        return view('admin.configuration.serviceCity.service_city_add_form');
    }

    public function insertServiceCity(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        ServiceCity::create(['name' => $request->input('name')]);
        return redirect()->back()->with('message', 'State Added successfully');
    }

    public function editServiceCity($id)
    {
        $serviceCity = ServiceCity::find($id);
        return view('admin.configuration.serviceCity.service_city_add_form', compact('serviceCity'));
    }

    public function updateServiceCity(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $state = ServiceCity::find($id);
        $state->name = $request->input('name');
        $state->save();
        return redirect()->back()->with('message', 'Service City Updated successfully');
    }

    public function updateStatusServiceCity($id, $status)
    {
        $state = ServiceCity::find($id);
        $state->status = $status;
        $state->save();
        return redirect()->back();
    }

    public function invoiceForm()
    {
        $invoice = InvoiceSetting::find(1);
        return view('admin.invoice_setting.invoice_setting_form', compact('invoice'));
    }

    public function invoiceUpdate(Request $request)
    {
        $this->validate($request, [
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'note1' => 'required',
            'note2' => 'required',
            'note3' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $invoice = InvoiceSetting::find(1);
        $invoice->address = $request->input('address');
        $invoice->phone = $request->input('phone');
        $invoice->gst = $request->input('gst');
        $invoice->email = $request->input('email');
        $invoice->note1 = $request->input('note1');
        $invoice->note2 = $request->input('note2');
        $invoice->note3 = $request->input('note3');

        if ($request->hasfile('image')) {

            $image = $request->file('image');
            $destination = public_path() . '/images/';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now') . time()) . "-" . uniqid() . "." . "$image_extension";
            $original_path = $destination . $image_name;
            Image::make($image)->save($original_path);

            $prev_img_delete_path = public_path() . '/images/' . $invoice->image;
            if (File::exists($prev_img_delete_path)) {
                File::delete($prev_img_delete_path);
            }

            $invoice->image = $image_name;
        }

        $invoice->save();
        return redirect()->back()->with('message', 'invoice Data Updated Successfully');
    }

    public function appSettingUserForm(Request $request)
    {
        $description = AppDescription::findOrFail(1);
        return view('admin.configuration.app_setting_user',compact('description'));
    }
    public function appSettingVendorForm(Request $request)
    {
        $description = AppDescription::findOrFail(2);
        return view('admin.configuration.app_setting_vendor',compact('description'));
    }

    public function appSettingUpdate(Request $request)
    {
        $this->validate($request, [
            'set_id' => 'required|numeric',
            'about_us' => 'required',
            'refund' => 'required',
            'disclaimers' => 'required',
            'privacy_policy' => 'required',
            'tc' => 'required',
            'faq' => 'required'
        ]);

        $id = $request->input('set_id');
        $description = AppDescription::findOrFail($id);
        $description->about_us = $request->input('about_us');
        $description->refund_cancellation = $request->input('refund');
        $description->disclaimers = $request->input('disclaimers');
        $description->privacy_policy = $request->input('privacy_policy');
        $description->tc = $request->input('tc');
        $description->faq = $request->input('faq');

        $description->save();
        return back()->with('message','Data Updated Successfully');
    }
}
