<?php

namespace Blog\Repositories;

use Illuminate\Support\Carbon;
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
            'from' => null,
            'to' => null,
            'authorId' => null,
            'tags' => [],
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
            'tags'
        ]);

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("title_$locale ILIKE ?", "%$search%");
        }

        if ($params['from']) {
            $from = Carbon::parse($params['from'])->toDateString();
            $query->whereRaw('COALESCE(published_at::date, posts.updated_at::date) >= ?', $from);
        }

        if ($params['to']) {
            $to = Carbon::parse($params['to'])->toDateString();
            $query->whereRaw('COALESCE(published_at::date, posts.updated_at::date) <= ?', $to);
        }

        if ($params['authorId']) {
            $query->where('author_id', $params['authorId']);
        }

        if ($params['tags']) {
            $tags = $params['tags'];

            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('id', $tags);
            });
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
