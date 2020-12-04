<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClientJobResource;

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
            'description' => $this->description,
            'client_type' => $this->clientType,
            'status' => $this->status,
            'profile_status' => $this->profile_status,
            'api_token' => $this->api_token,
            'service_city_id' => $this->service_city_id,
            'job_status' => $this->job_status,
            'image_upload_left_count' =>  (12 - $this->images->count()),
            'images' => $this->images,
            'services' => ClientJobResource::collection($this->jobs),
        ];
    }
}
