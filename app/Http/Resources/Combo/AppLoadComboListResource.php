<?php

namespace App\Http\Resources\Combo;

use Illuminate\Http\Resources\Json\JsonResource;

class AppLoadComboListResource extends JsonResource
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
            'client_id' => $this->user_id,
            'category_id' => $this->job_category,
            'category_name' => $this->jobCategory->name ?? null,
            'combo_name' => $this->description,
            'mrp' => $this->mrp,
            'price' => $this->price,
            'status' => $this->status,
            'client_name' => $this->client_name,
            'client_mobile' => $this->client_mobile,
            'client_state' => $this->client_state,
            'client_address' => $this->client_address,
            'client_pin' => $this->client_pin,
            'client_work_experience' => $this->client_work_experience,
            'client_image' => $this->client_image,
            'distance' => $this->distance,
            'combo_services'=> $this->clientJobs,
        ];
    }
}
