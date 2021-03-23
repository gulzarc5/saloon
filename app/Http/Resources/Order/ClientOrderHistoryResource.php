<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Order\ClientOrderResource;
use App\Http\Resources\Order\OrderDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientOrderHistoryResource extends JsonResource
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
            'discount' => $this->discount,
            'wallet_pay' => $this->wallet_pay,
            'online_pay' => $this->online_pay,
            'amount' => $this->amount,
            'advance_amount' => $this->advance_amount,
            'payment_method' => $this->payment_method,
            'payment_id' => $this->payment_id,
            'payment_request_id' => $this->payment_request_id,
            'payment_status' => $this->payment_status,
            'order-status' => $this->order_status,
            'service_time' => $this->service_time,
            'refund_request' => $this->refund_request,
            'vendor_cancel_status' => $this->vendor_cancel_status,
            'vendor_cancel_reason' => $this->vendor_cancel_reason,
            'created_at' => $this->created_at,
            'vendor_details' => isset($this->client) ? new ClientOrderResource($this->client) : null,
            'customer_address' => isset($this->address) ? $this->address : null,
            'service_data' => isset($this->services) ? OrderDetailResource::collection($this->services) : null,
        ];
    }
}
