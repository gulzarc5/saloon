<?php

namespace App\Http\Controllers\Admin\ServiceCategory;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\SubCategory;
use App\Models\ThirdLevelCategory;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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
        $man    = $request->input('man');
        $woman  = $request->input('woman');
        $kids   = $request->input('kids');

        $third = new ThirdLevelCategory();
        $third->top_category_id = $request->input('category');
        $third->sub_category_id = $request->input('sub_category');
        $third->third_level_category_name = $request->input('name');
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
        $third->image = $image_name;
        $man == null ? $third->man = '2' : '1';
        $woman == null ? $third->woman = '2' : '1';
        $kids == null ? $third->kids = '2' : '1';

        $man == 2  ? $third->man = '1' : '2';
        $woman == 2  ? $third->woman = '1' : '2';
        $kids == 2  ? $third->kids = '1' : '2';

        if ($third->save()) {
            // if ($this->checkCategory($category)) {
            return redirect()->back()->with('message', 'Sub Category Successfully Added');
            // }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fetchSubCategory(Request $request)
    {
        $category_id = $request->input('category_id');
        $sub_categories = SubCategory::whereStatus(1)->where('category_id', $category_id)->latest()->get();
        $html = '<select name="sub_category" id="sub_category" class="form-control">
        <option value="" selected disabled>--Select Sub Category--</option>
        </select>';
        if (isset($sub_categories) && !empty($sub_categories) && count($sub_categories) > 0) {
            foreach ($sub_categories as  $value) {
                $html = '<select name="sub_category" id="sub_category" class="form-control">
                <option value="" selected disabled>--Select Sub Category--</option>
                <option value="' . $value->id . '">' . $value->name . '</option>
                </select>';
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
                        $action = '<a href="' . route('category.show', $row) . '" class="btn btn-danger">Disable</a>';
                    } else {
                        $action = '<a href="' . route('category.show', $row) . '" class="btn btn-primary">Enable</a>';
                    }
                    $action .= '<a  href="' . route('category.edit', $row) . '" class="btn btn-info">Edit</a>';
                    return $action;
                })
                ->rawColumns(['action', 'category', 'photo', 'top_category', 'sub_category'])
                ->make(true);
        }
    }
}
