<?php

namespace App\Http\Resources;

use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $this->micro = [
            'id' => $this->id,
            'name'=> $this->name,// $this->original_name,
        ];

        $this->mini = [
            'type'   => $this->type,
            'fileable_type' => $this->fileable_type,
            'datetime'   => $this->created_at->format('d M, Y H:i'),
        ];

        $this->full = [
            'mime'   => $this->mime,
            'url'   => $this->asset_url,
            'width'  => $this->when(Str::contains($this->mime, 'image'), $this->width),
            'height' => $this->when(Str::contains($this->mime, 'image'), $this->height),
            'created_at'   =>  $this->created_at->format(config('app.datetime_format')),
            'ext' => $this->ext,
        ];

        $this->relations = [
            'user' => $this->relationLoaded('user') ? new UserResource($this->whenLoaded('user')) : null,
        ];

        return $this->getResource();
    }
}
