<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientOrderResource extends JsonResource
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
            'image' => $this->image,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'state' => $this->state,
            'city' => $this->city,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'client_type' => $this->clientType,
            'service_city_id' => $this->service_city_id,
            'ac' => $this->ac,
            'parking' => $this->parking,
            'wifi' => $this->wifi,
            'music' => $this->music,
        ];
    }
}
