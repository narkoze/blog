<?php

namespace Blog\Repositories;

use Blog\Image;

class ImageRepository
{
    public function params(): array
    {
        return [
            'page' => null,
            'sortBy' => 'updated_at',
            'sortDirection' => 'desc',
            'search' => null,
        ];
    }

    public function images($params = [])
    {
        $params = $params + $this->params();

        $query = Image::select([
            'images.*',
        ])->with([
            'author',
        ]);

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("name ILIKE ?", "%$search%");
        }

        if ($params['sortBy'] == 'authors.name') {
            $query->leftJoin('users as authors', 'authors.id', '=', 'images.author_id');
        }

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
