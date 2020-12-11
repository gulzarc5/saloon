<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'order_id' => $this->order_id,
            'service_id' => $this->job_id,
            'service_name' => isset($this->job->jobCategory->name) ? $this->job->jobCategory->name : null,
            'service_for' => $this->service_for,
            'amount' => $this->amount,
        ];
    }
}
