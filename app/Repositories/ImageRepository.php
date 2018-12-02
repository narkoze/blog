<?php

namespace Blog\Repositories;

use Illuminate\Support\Carbon;
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
            'from' => null,
            'to' => null,
            'authorId' => null,
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

        if ($params['from']) {
            $from = Carbon::parse($params['from'])->toDateString();
            $query->whereRaw('updated_at::date >= ?', $from);
        }

        if ($params['to']) {
            $to = Carbon::parse($params['to'])->toDateString();
            $query->whereRaw('updated_at::date <= ?', $to);
        }

        if ($params['authorId']) {
            $query->where('author_id', $params['authorId']);
        }

        if ($params['sortBy'] == 'authors.name') {
            $query->leftJoin('users as authors', 'authors.id', '=', 'images.author_id');
        }

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
