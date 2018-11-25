<?php

namespace Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment,
            'post' => new PostResource($this->post),
            'author' => new UserResource($this->author),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'is_updated' => $this->isUpdated
        ];
    }
}
