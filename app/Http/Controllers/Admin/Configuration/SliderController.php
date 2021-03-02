<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use File;
use Image;

class SliderController extends Controller
{
    public function appSliderList()
    {
        $sliders = Slider::get();
        return view('admin.app_slider.app_slider_list', compact('sliders'));
    }

    public function appSliderAddForm()
    {
        return view('admin.app_slider.new_app_slider_form');
    }

    public function insertAppSlider(Request $request)
    {
        $this->validate($request, [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasfile('images')) {
            $path = public_path() . '/images/slider/thumb/';
            File::exists($path) or File::makeDirectory($path, 0777, true, true);
            $path_thumb = public_path() . '/images/slider/';
            File::exists($path_thumb) or File::makeDirectory($path_thumb, 0777, true, true);

            for ($i = 0; $i < count($request->file('images')); $i++) {
                $image = $request->file('images')[$i];
                $image_name = $i . time() . date('Y-M-d') . '.' . $image->getClientOriginalExtension();

                //Product Original Image
                $destination = public_path() . '/images/slider/';
                $img = Image::make($image->getRealPath());
                $img->save($destination . '/' . $image_name);

                //Product Thumbnail
                $destination = public_path() . '/images/slider/thumb';
                $img = Image::make($image->getRealPath());
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destination . '/' . $image_name);

                $sliders = new Slider;
                $sliders->image = $image_name;
                $sliders->save();
            }
        }
        return redirect()->back()->with('message', 'Slider Added Successfull');
    }

    public function SliderDelete($id)
    {
        $prev_image = Slider::where('id', $id)->first();

        $prev_img_delete_path = public_path() . '/images/slider/' . $prev_image->image;
        $prev_img_delete_path_thumb = public_path() . '/images/slider/thumb/' . $prev_image->image;
        if (File::exists($prev_img_delete_path)) {
            File::delete($prev_img_delete_path);
        }

        if (File::exists($prev_img_delete_path_thumb)) {
            File::delete($prev_img_delete_path_thumb);
        }

        Slider::where('id', $id)
            ->delete();
        return redirect()->back();
    }
}
