<?php

namespace Blog;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'name_en' => $this->name_en,
            'name_lv' => $this->name_lv,
            'description_en' => $this->description_en,
            'description_lv' => $this->description_lv,
            // 'created_at' => (string) $this->created_at,
            // 'updated_at' => (string) $this->updated_at,
        ];
    }
}
