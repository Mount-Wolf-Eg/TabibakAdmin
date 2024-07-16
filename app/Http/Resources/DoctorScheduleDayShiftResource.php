<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class DoctorScheduleDayShiftResource extends BaseResource
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
            'from_time' => $this->from_time->format('H:i'),
            'to_time' => $this->to_time->format('H:i')
        ];
        $this->mini = [
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
        $this->full = [
        ];
        $this->relations = [
            'available_slots' => $this->relationLoaded('availableSlots') ? DoctorScheduleDayShiftResource::collection($this->availableSlots) : null,
            'day' => $this->relationLoaded('day') ? new DoctorScheduleDayResource($this->day) : null,
        ];
        return $this->getResource();
    }
}
