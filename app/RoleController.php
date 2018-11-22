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

    public function store(Request $request)
    {
        $rules = $this->rules;
        $rules['name_en'][] = 'unique:roles,name_en';
        $rules['name_lv'][] = 'unique:roles,name_lv';
        $request->validate($rules);

        // $role = new Role();
        // $role->fill($request->all());
        // $role->createdBy()->associate(auth()->user());
        // $role->save();

        // return response()->json($role);
        return new RoleResource(Role::create($request->all()));
    }

    public function update(Request $request, Role $role)
    {
        // $rules = $this->rules;
        // $rules['name'][] = "unique:blog_roles,name,{$role->id}";
        // $request->validate($rules);

        // $role->fill($request->all());
        // $role->save();

        // return response()->json($role);
    }

    public function destroy(Role $role)
    {
        // $role->delete();

        // return response()->json();
    }
}
