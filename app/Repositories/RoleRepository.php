<?php

namespace Blog\Repositories;

use Blog\Role;

class RoleRepository
{
    public function params(): array
    {
        return [
            'page' => null,
            'sortBy' => 'created_at',
            'sortDirection' => 'desc',
            'search' => null,
        ];
    }

    public function roles($locale, $params = [])
    {
        $params = $params + $this->params();

        $query = Role::select([
            'roles.*',
        ])->withCount('users');

        $search = trim($params['search']);
        if ($search) {
            $query->where(function ($query) use ($search, $locale) {
                $query->whereRaw("name_$locale ILIKE ?", "%$search%")
                    ->orWhereRaw("description_$locale ILIKE ?", "%$search%");
            });
        }

        $query->orderBy($params['sortBy'], $params['sortDirection']);

        return $query;
    }
}
