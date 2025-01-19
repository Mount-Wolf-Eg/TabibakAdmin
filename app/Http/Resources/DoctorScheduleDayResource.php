<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class DoctorScheduleDayResource extends BaseResource
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
            'date' => $this->date?->format('Y-m-d'),
            'name' => $this->day_name,
        ];
        $this->mini = [
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
        $this->full = [
        ];
        $this->relations = [
            'shifts' => $this->relationLoaded('shifts') ? DoctorScheduleDayShiftResource::collection($this->shifts) : null,
            'available_slots' => $this->relationLoaded('availableSlots') ? DoctorScheduleDayShiftResource::collection($this->availableSlots) : null,
            'nearest_available_slot' => $this->relationLoaded('nearestAvailableSlot') ? new DoctorScheduleDayShiftResource($this->nearestAvailableSlot) : null,
        ];
        return $this->getResource();
    }
}
