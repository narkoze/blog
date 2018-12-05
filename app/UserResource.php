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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when(true, function () use ($request) {
                if ($request->user('api') and
                    (
                        in_array($request->user('api')->role->id, [1, 3]) or
                        $request->user('api')->id == $this->id
                    )
                ) {
                    return $this->email;
                }
                return $this->email_masked;
            }),
            'email_verified' => (bool) $this->email_verified_at,
            // 'created_at' => (string) $this->created_at,
            // 'updated_at' => (string) $this->updated_at,
            'image' => $this->image,
            'images' => $this->images,
            'locale' => $this->locale,
            'role' => new RoleResource($this->role),
            'posts_count' => $this->posts()->count(),
        ];
    }
}
