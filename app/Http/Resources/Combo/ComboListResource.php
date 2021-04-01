<?php

namespace App\Http\Resources\Combo;

use Illuminate\Http\Resources\Json\JsonResource;

class ComboListResource extends JsonResource
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
            'category_name' => $this->jobCategory->name ?? null,
            'combo_name' => $this->description,
            'main_image' => $this->main_image,
            'mrp' => $this->mrp,
            'price' => $this->price,
            'status' => $this->status,
            'combo_services'=> $this->clientJobs,
        ];
    }
}
