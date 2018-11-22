<?php

namespace Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title_en' => $this->title_en,
            'title_lv' => $this->title_lv,
            'content_en' => $this->content_en,
            'content_lv' => $this->content_lv,
            'author' => new UserResource($this->author),
            'published_at' => (string) $this->published_at,
            // 'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
