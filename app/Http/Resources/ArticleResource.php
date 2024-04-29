<?php

namespace App\Http\Resources;


use \Illuminate\Http\Request;

class ArticleResource extends BaseResource
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
        ];
        $this->mini = [
            'is_active' => $this->is_active,
            'active_status' => $this->active_status,
            'active_class' => $this->active_class,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
        $this->full = [
            'content' => $this->content,
            'views' => $this->views,
            'likes' => $this->likes,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
        ];
        $this->relations = [
            'main_image' => $this->relationLoaded('mainImage') ? new FileResource($this->mainImage) : null,
            'images' => $this->relationLoaded('images') ? FileResource::collection($this->images) : null,
            'publisher' => $this->relationLoaded('publisher') ? new UserResource($this->publisher) : null,
            'author' => $this->relationLoaded('author') ? new UserResource($this->author) : null,
            'likes_count' => $this->relationLoaded('likes') ? $this->likes->count() : 0,
            'auth_like_status' => $this->relationLoaded('likes') ? $this->auth_like_status : false,
            'medical_speciality' => $this->relationLoaded('medicalSpeciality') ? new MedicalSpecialityResource($this->medicalSpeciality) : null,
        ];
        return $this->getResource();
    }
}
