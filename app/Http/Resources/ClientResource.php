<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'name' => $this->name,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'work_experience' => $this->work_experience,
            'state' => $this->state,
            'city' => $this->city,
            'address' => $this->address,
            'image' => $this->image,
            'gst' => $this->gst,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'client_type' => $this->clientType,
            'status' => $this->status,
            'profile_status' => $this->profile_status,
            'api_token' => $this->api_token,
        ];
    }
}
