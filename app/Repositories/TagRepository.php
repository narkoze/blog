<?php

namespace Blog\Repositories;

use Blog\Tag;

class TagRepository
{
    public function params(): array
    {
        return [
            'page' => null,
            'sortBy' => 'created_at',
            'sortDirection' => 'desc',
            'limit' => null,
            'search' => null,
        ];
    }

    public function tags($locale, $params = [])
    {
        $params = $params + $this->params();

        $query = Tag::select([
            'tags.*',
        ]);

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("name_$locale ILIKE ?", "%$search%");
        }

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        if ($params['limit']) {
            $query->limit($params['limit']);
        }

        return $query;
    }
}
