<?php

namespace App\Http\Resources\AppSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class DealJobListResource extends JsonResource
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
            'category_id' => $this->job_category,
            'category_name' => isset($this->jobCategory->name) ? $this->jobCategory->name : null,
            'sub_category_name' => isset($this->subCategory->name) ? $this->subCategory->name : null,
            'last_category_name' => isset($this->lastCategory->third_level_category_name) ? $this->lastCategory->third_level_category_name : null,
            'description' => $this->description,
            'mrp' => $this->mrp,
            'price' => $this->price,
            'is_deal' => $this->is_deal,
            'expire_date' => $this->expire_date,
            'discount' => $this->discount,
        ];
    }
}
