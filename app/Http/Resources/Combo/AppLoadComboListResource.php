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
            'client_name' => $this->clientData->name ?? null,
            'client_mobile' => $this->clientData->mobile,
            'client_state' => $this->clientData->state,
            'client_address' => $this->clientData->address,
            'client_pin' => $this->clientData->pin,
            'client_work_experience' => $this->clientData->work_experience,
            'client_image' => $this->clientData->image,
            'distance' => $this->distance,
            'combo_services'=> $this->clientJobs,
        ];
    }
}
