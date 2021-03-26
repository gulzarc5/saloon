<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientJobResource extends JsonResource
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
            'main_category_id' => $this->job_category,
            'main_category' => $this->jobCategory->name ?? null,
            'main_category_image' => $this->jobCategory->image ?? null,
            'sub_category_id' => $this->sub_category,
            'sub_category' => $this->subCategory->name ?? null,
            'sub_category_image' => $this->subCategory->image ?? null,
            'last_category_id' => $this->last_category,
            'last_category' => $this->lastCategory->third_level_category_name ?? null,
            'last_category_image' => $this->lastCategory->image ?? null,
            'description' => $this->description,
            'mrp' => $this->mrp,
            'price' => $this->price,
            'is_deal' => $this->is_deal,
            'expire_date' => $this->expire_date,
            'discount' => $this->discount,
            'status' => $this->status,
        ];
    }
}
