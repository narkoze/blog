<?php

namespace Blog\Repositories;

use Blog\User;

class UserRepository
{
    public function params(): array
    {
        return [
            'page' => null,
            'sortBy' => 'name',
            'sortDirection' => 'asc',
            'search' => null
        ];
    }

    public function users($locale, $params = [], $request = null)
    {
        $params = $params + $this->params();

        $query = User::select([
            'users.*',
        ])->withCount('posts');

        $search = trim($params['search']);
        if ($search) {
            $query->whereRaw("users.name ILIKE ?", "%$search%");

            if ($request and in_array($request->user()->role->id, [1, 3])) {
                $query->orWhereRaw("users.email ILIKE ?", "%$search%");
            }
        }

        if (in_array($params['sortBy'], ['roles.name_en', 'roles.name_lv'])) {
            $query->leftJoin('roles', 'roles.id', '=', 'users.role_id');
        }

        $sortDirection  = strtolower($params['sortDirection'] == 'asc' ? 'asc' : 'desc');
        if ($params['sortBy'] == 'name') {
            $query->orderByRaw("unaccent(users.name) $sortDirection");
        } else {
            $query->orderBy($params['sortBy'], $sortDirection);
        }
        return $query;
    }
}
