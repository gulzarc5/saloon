<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobListResource extends JsonResource
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
            'description' => $this->description,
            'mrp' => $this->mrp,
            'price' => $this->price,
            'is_man' => $this->is_man,
            'is_woman' => $this->is_woman,
            'is_kids' => $this->is_kids,
            'user_data' => isset($this->clientData) ? new ClientJobListResource($this->clientData) : '',
        ];

    }
}
