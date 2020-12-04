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
            'service_category' => $this->job_category,
            'service_category_name' => isset($this->jobCategory->name) ? $this->jobCategory->name : null,
            'is_man' => $this->is_man,
            'man_mrp' => $this->man_mrp,
            'man_price' => $this->man_price,
            'is_women' => $this->is_woman,
            'woman_mrp' => $this->woman_mrp,
            'woman_price' => $this->woman_price,
            'is_kids' => $this->is_kids,
            'kids_mrp' => $this->kids_mrp,
            'kids_price' => $this->kids_price,
            'status' => $this->status,
            'description' => $this->description,
            'job_schedules' => $this->jobSchedule,
        ];
    }
}
