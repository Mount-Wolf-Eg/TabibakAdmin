<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class ComplaintResource extends BaseResource
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
            'title' => $this->title,
            'description' => $this->description,
        ];
        $this->mini = [
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
        $this->full = [
            'type' => [
                'value' => $this->type?->value,
                'label' => $this->type?->label(),
            ]
        ];
        $this->relations = [
            'user' => $this->relationLoaded('user') ? new UserResource($this->user) : null,
        ];
        return $this->getResource();
    }
}
