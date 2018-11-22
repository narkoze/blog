<?php

namespace Blog\Http\Controllers\Admin;

use Blog\Repositories\RoleRepository;
use Blog\Http\Controllers\Controller;
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

        $roles = new RoleResourceCollection($roleRepo->roles($locale, $params)->paginate(10));

        return $roles->additional(compact('params'));
    }

    public function show($locale, Role $role)
    {
        return new RoleResource($role);
    }

    public function store(Request $request)
    {
        $rules = $this->rules;
        $rules['name_en'][] = 'unique:roles,name_en';
        $rules['name_lv'][] = 'unique:roles,name_lv';
        $request->validate($rules);

        return new RoleResource(Role::create($request->all()));
    }

    public function update(Request $request, $locale, Role $role)
    {
        $rules = $this->rules;
        $rules['name_en'][] = "unique:roles,name_en,{$role->id}";
        $rules['name_lv'][] = "unique:roles,name_lv,{$role->id}";
        $request->validate($rules);

        $role->update($request->all());

        return new RoleResource($role);
    }

    public function destroy($locale, Role $role)
    {
        $role->delete();

        return response()->json();
    }
}
