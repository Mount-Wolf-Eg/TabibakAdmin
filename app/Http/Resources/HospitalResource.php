<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class HospitalResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request) : array
    {
        $this->micro = [
            'id' => $this->id,
            'name' => $this->name,
        ];
        $this->mini = [
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
        $this->full = [
            'start_date' => $this->pivot?->start_date,
            'end_date' => $this->pivot?->end_date,
        ];
        $this->relations = [
        ];
        return $this->getResource();
    }
}
