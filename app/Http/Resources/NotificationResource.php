<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        $this->micro = [
            'id' => $this->id,
            'title' => $this->title
        ];
        $this->mini = [
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
        $this->full = [
            'body' => $this->body,
            'type' => [
                'value' => $this->type?->value,
                'label' => $this->type?->label(),
            ],
            'redirect_module' => $this->redirect_type,
            'redirect_id' => $this->redirect_id,
            'data' => $this->data,
        ];
        $this->relations = [
        ];
        return $this->getResource();
    }
}
