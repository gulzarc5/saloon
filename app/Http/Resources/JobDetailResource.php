<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobDetailResource extends JsonResource
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
            'sub_category_id' => $this->sub_category,
            'sub_category' => $this->subCategory->name ?? null,
            'last_category_id' => $this->last_category,
            'last_category' => $this->lastCategory->third_level_category_name ?? null,
            'description' => $this->description,
            'mrp' => $this->mrp,
            'price' => $this->price,
            'user_data' => isset($this->clientData) ? new ClientResource($this->clientData) : '',
        ];
    }
}
