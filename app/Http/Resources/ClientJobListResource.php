<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientJobListResource extends JsonResource
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
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'client_type' => $this->clientType,     
            'ac' => $this->ac,
            'parking' => $this->parking,
            'wifi' => $this->wifi,
            'music' => $this->music,
            'avarage_rating' => isset($this->review) ? $this->review->avg('rating') : 0,
            'reviews' => isset($this->review) ? ReviewResource::collection($this->review) : [],
        ];
    }
}
