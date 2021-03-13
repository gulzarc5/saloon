<?php

namespace App\Http\Controllers\Admin\ServiceCategory;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.service_category.subcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service_categories = JobCategory::whereStatus(1)->latest()->get();
        return view('admin.service_category.subcategory.create', compact('service_categories'));
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
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $category   = $request->input('category');
        $name   = $request->input('name');
        
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $destination = base_path() . '/public/admin/service_category/sub_category';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now') . time()) . "." . $image_extension;
            $original_path = $destination . $image_name;
            Image::make($image)->save($original_path);
            $thumb_path = base_path() . '/public/admin/service_category/sub_category/thumb/' . $image_name;
            Image::make($image)
                ->resize(346, 252)
                ->save($thumb_path);
        }

        $sub_category = new SubCategory();
        $sub_category->category_id = $category;
        $sub_category->name = $name;
        $sub_category->image = $image_name;

        if ($sub_category->save()) {
            if ($this->checkCategory($category)) {
                return redirect()->back()->with('message', 'Sub Category Successfully Added');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->status == 1 ? $subCategory->status = 2 : $subCategory->status = 1;
        if ($subCategory->save()) {
            if ($this->checkCategory($subCategory->category_id)) {
                return redirect()->back()->with('message', 'Updated Successfully!');
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sub_category = SubCategory::findOrFail($id);
        $service_categories = JobCategory::whereStatus(1)->latest()->get();
        return view('admin.service_category.subcategory.create', compact('sub_category', 'service_categories'));
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
        $this->validate($request, [
            'category' => 'required|numeric',
            'name' => 'required|string'
        ]);
        $category = $request->input('category');
        $name   = $request->input('name');
        $sub_category = SubCategory::findOrFail($id);
        $sub_category->category_id = $category;
        $sub_category->name = $name;

        if ($request->hasfile('image')) {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $image = $request->file('image');
            $destination = base_path() . '/public/admin/service_category/sub_category';
            $image_extension = $image->getClientOriginalExtension();
            $image_name = md5(date('now') . time()) . "." . $image_extension;
            $original_path = $destination . $image_name;
            Image::make($image)->save($original_path);
            $thumb_path = base_path() . '/public/admin/service_category/sub_category/thumb/' . $image_name;
            Image::make($image)
                ->resize(346, 252)
                ->save($thumb_path);

            // Check wheather image is in DB
            $sub_category = SubCategory::findOrFail($id);
            if ($sub_category->image) {
                //Delete
                $image_path = "admin/service_category/sub_category/thumb/" . $sub_category->image;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $sub_category->image = $image_name;
        }      

        if ($sub_category->save()) {
            return redirect()->back()->with('message', 'Sub Category Successfully Added');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function ajaxData(Request $request)
    {
        if ($request->ajax()) {
            $query = SubCategory::latest();
            return datatables()->of($query->get())
                ->addIndexColumn()
                ->addColumn('service_category', function ($row) {
                    return '<label class="label label-success">' . $row->serviceCategory->name . '</label>' ?? '';
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
                        $action = '<a href="' . route('category.show', $row) . '" class="btn btn-danger">Disable</a>';
                    } else {
                        $action = '<a href="' . route('category.show', $row) . '" class="btn btn-primary">Enable</a>';
                    }
                    $action .= '<a  href="' . route('category.edit', $row) . '" class="btn btn-info">Edit</a>';
                    return $action;
                })
                ->rawColumns(['action', 'category', 'photo', 'service_category'])
                ->make(true);
        } else {
            return 1;
        }
    }

    private function checkCategory($category_id)
    {
        $service_category = JobCategory::findOrFail($category_id);
        if($this->checkSubCategory($service_category)){
            $service_category->is_subcategory = 1;
            $service_category->save();
            return true;
        }else{
            $service_category->is_subcategory = 2;
            $service_category->save();
            return true;
        }
    }

    private function checkSubCategory($service_category){
        $sub_category = $service_category->subCategoryWithStatus;
        if($sub_category->count() < 1){
            return true;
        }
    }
}
