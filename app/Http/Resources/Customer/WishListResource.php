<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WishListResource extends JsonResource
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
            'vendor_details' => $this->client,
            'vendor_details' => isset($this->client) ? new ClientResource($this->client) : null,
        ];
    }
}
