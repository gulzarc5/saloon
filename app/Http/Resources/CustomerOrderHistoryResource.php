<?php

namespace App\Http\Resources;

use App\Http\Resources\Order\ClientOrderResource;
use App\Http\Resources\Order\OrderDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderHistoryResource extends JsonResource
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
            'amount' => $this->amount,
            'payment_id' => $this->payment_id,
            'payment_request_id' => $this->payment_request_id,
            'payment_status' => $this->payment_status,
            'order-status' => $this->order_status,
            'service_time' => $this->service_time,
            'refund_request' => $this->refund_request,
            'created_at' => $this->created,
            'vendor_details' => isset($this->client) ? new ClientOrderResource($this->client) : null,
            'service_data' => isset($this->services) ? OrderDetailResource::collection($this->services) : null,
        ];
    }
}
