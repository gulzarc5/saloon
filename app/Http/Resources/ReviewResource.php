<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'client_id' => $this->client_id,
            'customer_name' => isset($this->customer->name) ? $this->customer->name : null,
            'gender' => isset($this->customer->gender) ? $this->customer->gender : null,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
        ];
    }
}
