<?php

namespace Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            'filename' => $this->filename,
            'size' => $this->size,
            'type' => $this->type,
            'width' => $this->width,
            'height' => $this->height,
            'model' => $this->model,
            'author' => new UserResource($this->author),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'images' => $this->images,
            'dimensions' => $this->dimensions,
        ];
    }
}
