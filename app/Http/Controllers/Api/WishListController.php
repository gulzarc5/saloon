<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\WishListResource;
use App\Models\WishList;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    public function add(Request $request,$vandor_id)
    {
        $check = WishList::where('user_id',$request->user()->id)->where('vandor_id',$vandor_id)->count();
        if ($check == 0) {
            $wish_list = new WishList();
            $wish_list->user_id = $request->user()->id;
            $wish_list->vandor_id = $vandor_id;
            $wish_list->save();
        }

        $response = [
            'status' => true,
            'message' => 'Wish List Added Successfully',
        ];
        return response()->json($response, 200);
    }

    public function list(Request $request)
    {
        $wish_list = WishList::where('user_id',$request->user()->id)->get();

        $response = [
            'status' => true,
            'message' => 'Wish List Data',
            'data' => WishListResource::collection($wish_list),
        ];
        return response()->json($response, 200);
    }

    public function remove($wish_list_id)
    {
        $wish_list = WishList::where('id',$wish_list_id)->delete();

        $response = [
            'status' => true,
            'message' => 'Removed Successfully From Wish List',
        ];
        return response()->json($response, 200);
    }
}
