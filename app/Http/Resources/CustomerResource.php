<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'states' => $this->states,
            'city' => $this->city,
            'address' => $this->address,
            'pin' => $this->pin,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'dob' => $this->dob,
            'status' => $this->status,
        ];
    }
}
