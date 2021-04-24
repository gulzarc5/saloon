<?php

namespace App\Http\Controllers\Admin\ServiceCategory;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\SubCategory;
use App\Models\ThirdLevelCategory;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use File;

class ThirdLevelCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.service_category.thirdlevelcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service_categories = JobCategory::whereStatus(1)->latest()->get();
        $sub_categories = SubCategory::whereStatus(1)->latest()->get();
        return view('admin.service_category.thirdlevelcategory.create', compact('service_categories', 'sub_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category' => 'required|numeric',
            'sub_category' => 'required|numeric',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
       
        $third = new ThirdLevelCategory();
        $third->top_category_id = $request->input('category');
        $third->sub_category_id = $request->input('sub_category');
        $third->third_level_category_name = $request->input('name');
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $destination = public_path() . '/admin/service_category/sub_category';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now') . time()) . "." . $image_extension;
            $original_path = $destination . $image_name;
            Image::make($image)->save($original_path);
            $thumb_path = public_path() . '/admin/service_category/sub_category/thumb/' . $image_name;
            Image::make($image)
                ->resize(346, 252)
                ->save($thumb_path);
            $third->image = $image_name;
        }

        if ($third->save()) {
            // if ($this->checkCategory($category)) {
            return redirect()->back()->with('message', 'Sub Category Successfully Added');
            // }
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    

    public function fetchSubCategory(Request $request)
    {
        $category_id = $request->input('category_id');
        $sub_categories = SubCategory::whereStatus(1)->where('category_id', $category_id)->latest()->get();
        $html = '<option value="" selected disabled>--Select Sub Category--</option>';
        if (isset($sub_categories) && !empty($sub_categories) && count($sub_categories) > 0) {
            foreach ($sub_categories as  $value) {
                $html .= '<option value="' . $value->id . '">' . $value->name . '</option>';
            }
        }
        return response()->json($html, 200);
    }
    public function fetchThirdCategoryAjax(Request $request)
    {
        $category_id = $request->input('category_id');
        $sub_categories = ThirdLevelCategory::whereStatus(1)->where('sub_category_id', $category_id)->latest()->get();
        $html = '<option value="" selected disabled>--Select Third Category--</option>';
        if (isset($sub_categories) && !empty($sub_categories) && count($sub_categories) > 0) {
            foreach ($sub_categories as  $value) {
                $html .= '
                <option value="' . $value->id . '">' . $value->third_level_category_name . '</option>';
            }
        }
        return response()->json($html, 200);
    }

    public function fetchThirdCategory(Request $request)
    {
        if ($request->ajax()) {
            $query = ThirdLevelCategory::latest();
            return datatables()->of($query->get())
                ->addIndexColumn()
                ->addColumn('top_category', function ($row) {
                    return '<label class="label label-success">' . $row->subCategory->serviceCategory->name . '</label>' ?? '';
                })
                ->addColumn('sub_category', function ($row) {
                    return '<label class="label label-success">' . $row->subCategory->name . '</label>' ?? '';
                })
                ->addColumn('category', function ($row) {
                    $category = '';
                    if ($row->man == 2) {
                        $category .= '<label class="label label-success">MAN</label>';
                    }
                    if ($row->woman == 2) {
                        $category .= ', <label class="label label-success">WOMAN</label>';
                    }
                    if ($row->kids == 2) {
                        $category .= ', <label class="label label-success">KIDS</label>';
                    }
                    return $category;
                })
                ->addColumn('photo', function ($row) {
                    if ($row->image) {
                        $image = '<img src="' . asset("admin/service_category/sub_category/thumb/" . $row->image) . '" width="50"/>';
                    }
                    return $image;
                })
                ->addColumn('action', function ($row) {
                    if ($row->status == '1') {
                        $action = '<a href="' . route('admin.third_category_status_update', $row->id) . '" class="btn btn-danger">Disable</a>';
                    } else {
                        $action = '<a href="' . route('admin.third_category_status_update', $row->id) . '" class="btn btn-primary">Enable</a>';
                    }
                    $action .= '<a  href="' . route('admin.categoryEdit', $row->id) . '" class="btn btn-info">Edit</a>';
                    return $action;
                })
                ->rawColumns(['action', 'category', 'photo', 'top_category', 'sub_category'])
                ->make(true);
        }
    }

    public function statusUpdate($id)
    {
        $category = ThirdLevelCategory::findOrFail($id);
        $category->status = $category->status == 1 ? 2 : 1;
        $category->save();
        return back();
    }

    public function categoryEdit($id)
    {
        $thirdCategory = ThirdLevelCategory::with('subCategory')->findOrFail($id);
        // dd($thirdCategory);
        $service_categories = JobCategory::whereStatus(1)->latest()->get();
        $sub_category = SubCategory::where('category_id',$thirdCategory->top_category_id)->whereStatus(1)->get();
        return view('admin.service_category.thirdlevelcategory.create', compact('thirdCategory', 'service_categories','sub_category'));
    }

    public function categoryUpdate(Request $request,$category_id)
    {
        $this->validate($request, [
            'category' => 'required|numeric',
            'sub_category' => 'required|numeric',
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $third = ThirdLevelCategory::findOrFail($category_id);
        $third->top_category_id = $request->input('category');
        $third->sub_category_id = $request->input('sub_category');
        $third->third_level_category_name = $request->input('name');
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $destination = public_path() . '/admin/service_category/sub_category';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now') . time()) . "." . $image_extension;
            $original_path = $destination . $image_name;
            Image::make($image)->save($original_path);
            $thumb_path = public_path() . '/admin/service_category/sub_category/thumb/' . $image_name;
            Image::make($image)
                ->resize(346, 252)
                ->save($thumb_path);

            if ($third->image) {
                //Delete
                $image_path = "/admin/service_category/sub_category/thumb/" . $third->image;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $image_path = "/admin/service_category/sub_category/" . $third->image;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $third->image = $image_name;
        }

        if ($third->save()) {
            return redirect()->back()->with('message', 'Third Category Successfully Updated');
        }else{
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
}
