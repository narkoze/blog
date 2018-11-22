<?php

namespace Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            // 'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            // 'email_verified_at' => $this->email_verified_at,
            'email_verified' => (bool) $this->email_verified_at,
            // 'created_at' => (string) $this->created_at,
            // 'updated_at' => (string) $this->updated_at,
            'image' => $this->image,
            'images' => $this->images,
            'locale' => $this->locale,
        ];
    }
}
