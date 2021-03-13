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
            'pin' => $this->pin,
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
            'verify_status' => $this->verify_status,
            'address_proof' => $this->address_proof,
            'address_proof_file' => $this->address_proof_file,
            'photo_proof' => $this->photo_proof,
            'photo_proof_file' => $this->photo_proof_file,
            'business_proof' => $this->business_proof,
            'business_proof_file' => $this->business_proof_file,
            'ac' => $this->ac,
            'parking' => $this->parking,
            'wifi' => $this->wifi,
            'music' => $this->music,
            'image_upload_left_count' =>  (12 - $this->images->count()),            
            'avarage_rating' => isset($this->review) ? $this->review->avg('rating') : 0,
            'distance' => $this->distance ?? 0,
            'reviews' => isset($this->review) ? ReviewResource::collection($this->review) : [],
            'client_schedules' => isset($this->clientSchedules) ? $this->clientSchedules : [],
            'images' => isset($this->images) ? $this->images : [],
            'services' => isset($this->jobs) ? ClientJobResource::collection($this->jobs) : [],
        ];
    }
}
