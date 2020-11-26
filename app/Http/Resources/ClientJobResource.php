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
            'mrp' => $this->mrp,
            'price' => $this->price,
            'is_man' => $this->is_man,
            'is_women' => $this->is_woman,
            'is_kids' => $this->is_kids,
            'status' => $this->status,
            'description' => $this->description,
            'job_schedules' => $this->jobSchedule,
        ];
    }
}
