<?php

namespace Blog\Http\Controllers\Admin;

use Blog\Http\Controllers\Controller;
use Blog\Repositories\UserRepository;
use Illuminate\Http\Request;
use Blog\UserResourceCollection;
use Blog\UserResource;
use Blog\User;

class UserController extends Controller
{
    public function index(UserRepository $userRepo, Request $request, $locale)
    {
        $params = $request->all() + $userRepo->params();

        $users = $userRepo->users($locale, $params, $request);
        if ($params['page']) {
            $users = new UserResourceCollection($users->paginate(10));
        } else {
            $users = new UserResourceCollection($users->get());
        }

        return $users->additional(compact('params'));
    }

    public function update(Request $request, $locale, User $user)
    {
        abort_unless(in_array($request->user()->role->id, [1, 3]), 403);

        $user->role()->associate($request->role_id);
        $user->save();

        return new UserResource($user);
    }
}
