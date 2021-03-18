<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category_name' => $this->category->name ?? null ,
            'sub_category_id' => $this->sub_category_id,
            'sub_category_name' => $this->subCategory->name ?? null ,
            'third_category_id' => $this->third_category_id,
            'third_category_name' => $this->ThirdCategory->third_level_category_name ?? null ,
            'range_type' => $this->range_type,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'total_user' => $this->total_user,
            'offer_received_user' => $this->offer_received_user,
            'description' => $this->description,
            'image' => $this->image,
            'status' => $this->status,
            'offer_salons' => $this->salons
        ];
    }
}
