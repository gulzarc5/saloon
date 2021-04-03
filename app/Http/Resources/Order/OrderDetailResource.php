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
            'category_name' => isset($this->job->jobCategory->name) ? $this->job->jobCategory->name : null,
            'sub_category_name' => isset($this->job->subCategory->name) ? $this->job->subCategory->name : null,
            'third_category_name' => isset($this->job->lastCategory->third_level_category_name) ? $this->job->lastCategory->third_level_category_name : null,
            'service_type' => $this->job->product_type ?? null,
            'combo_services' => $this->job->clientJobs ?? [],
            'amount' => $this->amount,
        ];
    }
}
