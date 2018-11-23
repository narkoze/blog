<?php

namespace Blog\Http\Controllers\Admin;

use Blog\Http\Controllers\Controller;
use Blog\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Blog\RoleResourceCollection;
use Blog\RoleResource;
use Blog\Role;

class RoleController extends Controller
{
    protected $rules = [
        'name_en' => [
            'required',
            'max:255',
        ],
        'name_lv' => [
            'required',
            'max:255',
        ],
        'description_en' => 'required',
        'description_lv' => 'required',
    ];

    public function index(RoleRepository $roleRepo, Request $request, $locale)
    {
        $params = $request->all() + $roleRepo->params();

        $roles = $roleRepo->roles($locale, $params);
        if ($params['page']) {
            $roles = new RoleResourceCollection($roles->paginate(10));
        } else {
            $roles = new RoleResourceCollection($roles->get());
        }

        return $roles->additional(compact('params'));
    }

    public function show($locale, Role $role)
    {
        return new RoleResource($role);
    }

    public function store(Request $request)
    {
        abort_unless(in_array($request->user()->role->id, [1, 3]), 403);

        $rules = $this->rules;
        $rules['name_en'][] = 'unique:roles,name_en';
        $rules['name_lv'][] = 'unique:roles,name_lv';
        $request->validate($rules);

        return new RoleResource(Role::create($request->all()));
    }

    public function update(Request $request, $locale, Role $role)
    {
        abort_unless(in_array($request->user()->role->id, [1, 3]), 403);

        $rules = $this->rules;
        $rules['name_en'][] = "unique:roles,name_en,{$role->id}";
        $rules['name_lv'][] = "unique:roles,name_lv,{$role->id}";
        $request->validate($rules);

        $role->update($request->all());

        return new RoleResource($role);
    }

    public function destroy($locale, Role $role)
    {
        abort_unless(in_array($request->user()->role->id, [1, 3]), 403);

        $role->delete();

        return response()->json();
    }
}
