<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class ContactResource extends BaseResource
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
            'email' => $this->email,
            'message' => $this->message,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
        $this->full = [
        ];
        //$this->relationLoaded()
        $this->relations = [
        ];
        return $this->getResource();
    }
}
