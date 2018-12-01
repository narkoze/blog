<?php

namespace Blog\Repositories;

use Blog\Post;

class PostRepository
{
    public function params(): array
    {
        return [
            'page' => null,
            'sortBy' => 'dates',
            'sortDirection' => 'desc',
            'state' => null,
            'search' => null,
        ];
    }

    public function posts($locale, $params = [])
    {
        $params = $params + $this->params();

        $query = Post::select([
            'posts.*',
        ])
        ->with([
            'author',
        ]);

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("title_$locale ILIKE ?", "%$search%");
        }

        switch ($params['state']) {
            case 'published':
                $query->whereNotNull('published_at');
                break;
            case 'draft':
                $query->whereNull('published_at');
                break;
        }

        if ($params['sortBy'] == 'authors.name') {
            $query->leftJoin('users as authors', 'authors.id', '=', 'author_id');
        }

        $sortDirection  = strtolower($params['sortDirection'] == 'asc' ? 'asc' : 'desc');
        if ($params['sortBy'] == 'dates') {
            $query->orderByRaw("COALESCE(published_at, updated_at) $sortDirection");
        } else {
            $query->orderBy($params['sortBy'], $params['sortDirection']);
        }

        return $query;
    }
}
